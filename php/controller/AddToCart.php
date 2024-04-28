<?php
session_start();
require_once "CartController.php";

if (!isset($_SESSION['id'])) {
    echo "<script>alert('請先登入會員！');</script>";
    echo "<script>window.location.href='../view/login.php';</script>";
    exit;
}

$userId = $_SESSION['id'];
$productId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

$controller = new CartController();
$controller->addToCart($userId, $productId, $quantity);
?>