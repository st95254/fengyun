<?php
require_once '../model/CartModel.php';

class CartController {
    private $cartModel;

    public function __construct() {
        $this->cartModel = new CartModel();
    }

    public function addToCart($userId, $productId, $quantity) {
        $cartId = $this->cartModel->findOrCreateCart($userId);
        $productPrice = $this->cartModel->getProductPrice($productId);
        $this->cartModel->addItemToCart($cartId, $productId, $quantity, $productPrice);
        header("Location: ../view/cart.php");
        exit();
    }

    public function getCartItems($userId) {
        return $this->cartModel->getCartItems($userId);
    }

    public function checkout($userId, $formData, $cartItems) {
        $tradeNo = date('YmdHis') . $userId;
        $date = date('Y-m-d H:i:s');
        $shippingFee = $formData['shippingFeeInput'] === 'true' ? 1 : 0;

        // 驗證手機號碼和帳戶號碼
        if (!preg_match('/^\d{8,15}$/', $formData['phone']) || !preg_match('/^\d{5}$/', $formData['account'])) {
            return ["error" => "Invalid input data"];
        }

        $historyId = $this->cartModel->addHistory($userId, $formData['name'], $formData['phone'], $formData['address'], $formData['account'], $formData['remark'] ?? '', $tradeNo, $formData['totalInput'], $date, $shippingFee, $formData['orderStatusInput']);

        foreach ($cartItems as $item) {
            $this->cartModel->addHistoryItems($historyId, $item['productId'], $item['count'], $item['price']);
        }

        $this->cartModel->clearUserCart($userId);

        header("Location: ../view/order.php");
        exit;
    }
}
?>
