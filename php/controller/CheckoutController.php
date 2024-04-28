<?php
session_start();
require_once '../controller/CartController.php';

if (!isset($_SESSION['id'])) {
    echo "<script>alert('使用者尚未登入'); window.location.href = 'login.php';</script>";
    exit;
}

$controller = new CartController();
$userId = $_SESSION['id'];
$formData = [
    'name' => $_POST['name'],
    'phone' => $_POST['phone'],
    'address' => $_POST['address'],
    'account' => $_POST['account'],
    'remark' => $_POST['remark'],
    'totalInput' => $_POST['totalInput'],
    'shippingFeeInput' => $_POST['shippingFeeInput'],
    'orderStatusInput' => $_POST['orderStatusInput']
];
$cartItems = json_decode($_POST['cart_items'], true);

$result = $controller->checkout($userId, $formData, $cartItems);
if (isset($result['error'])) {
    echo json_encode($result);
} else {
    header("Location: ../view/order.php");
}
