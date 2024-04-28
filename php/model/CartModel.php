<?php
require_once "../config.php";

class CartModel {
    private $db;

    public function __construct() {
        $this->initializeDatabaseConnection();
    }

    private function initializeDatabaseConnection() {
        $this->db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->db->connect_error) {
            die("連接失敗: " . $this->db->connect_error);
        }
    }

    public function closeConnection() {
        mysqli_close($this->db);
    }

    public function findOrCreateCart($userId) {
        $sql = "SELECT id FROM cart WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['id'];
        } else {
            $insertSql = "INSERT INTO cart (user_id) VALUES (?)";
            $insertStmt = $this->db->prepare($insertSql);
            $insertStmt->bind_param("i", $userId);
            $insertStmt->execute();
            return $this->db->insert_id;
        }
    }

    public function addItemToCart($cartId, $productId, $quantity, $price) {
        $checkSql = "SELECT id, quantity FROM cart_item WHERE cart_id = ? AND product_id = ?";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bind_param("ii", $cartId, $productId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $newQuantity = $row['quantity'] + $quantity;
            $updateSql = "UPDATE cart_item SET quantity = ?, price = ? WHERE id = ?";
            $updateStmt = $this->db->prepare($updateSql);
            $updateStmt->bind_param("idi", $newQuantity, $price, $row['id']);
            $updateStmt->execute();
        } else {
            $insertSql = "INSERT INTO cart_item (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $insertStmt = $this->db->prepare($insertSql);
            $insertStmt->bind_param("iiid", $cartId, $productId, $quantity, $price);
            $insertStmt->execute();
        }
    }

    public function getProductPrice($productId) {
        $sql = "SELECT price FROM product WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['price'] ?? 0;
    }

    public function getCartItems($userId) {
        $sql = "SELECT ci.id AS cart_item_id, ci.product_id, ci.quantity, p.price, p.name, p.image 
                FROM cart_item ci 
                JOIN product p ON ci.product_id = p.id 
                WHERE ci.cart_id = (SELECT id FROM cart WHERE user_id = ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

    public function addHistory($userId, $name, $phone, $address, $account, $remark, $tradeNo, $total, $date, $shippingFee, $orderStatus) {
        $sql = "INSERT INTO history (user_id, name, phone, address, account, remark, trade_no, total, date, shipping_fee, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issssssisis", $userId, $name, $phone, $address, $account, $remark, $tradeNo, $total, $date, $shippingFee, $orderStatus);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function addHistoryItems($historyId, $productId, $quantity, $price) {
        $sql = "INSERT INTO history_item (history_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiid", $historyId, $productId, $quantity, $price);
        $stmt->execute();
    }

    public function clearUserCart($userId) {
        $sql = "DELETE FROM cart_item WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }
}
?>
