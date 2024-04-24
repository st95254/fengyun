<?php 
session_start();
if(!isset($_SESSION['id'])){
    echo "<script>alert('請先登入會員！');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

// 從URL參數獲取商品ID和數量
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

require_once "config.php";
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$user_id = $_SESSION['id'];

// 檢查購物車是否已存在該用戶的記錄
$sql_cart = "SELECT id FROM cart WHERE user_id = '$user_id'";
$result_cart = mysqli_query($conn, $sql_cart);
$cart_id = 0;
if(mysqli_num_rows($result_cart) > 0){
    // 購物車已存在，獲取購物車ID
    $row_cart = mysqli_fetch_assoc($result_cart);
    $cart_id = $row_cart['id'];
} else {
    // 購物車不存在，創建新的購物車
    $sql_insert_cart = "INSERT INTO cart (user_id) VALUES ('$user_id')";
    mysqli_query($conn, $sql_insert_cart);
    $cart_id = mysqli_insert_id($conn); // 獲取新創建的購物車ID
}

// 根據商品ID檢查購物車項目是否已存在
$sql_cart_item = "SELECT id, quantity FROM cart_item WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
$result_cart_item = mysqli_query($conn, $sql_cart_item);

// 獲取最新的產品價格
$sql_product = "SELECT price FROM product WHERE id = '$product_id'";
$result_product = mysqli_query($conn, $sql_product);
$row_product = mysqli_fetch_assoc($result_product);
$product_price = $row_product ? $row_product['price'] : 0;

if(mysqli_num_rows($result_cart_item) > 0){
    // 購物車項目已存在，更新數量和總價格
    $row_cart_item = mysqli_fetch_assoc($result_cart_item);
    $new_quantity = $row_cart_item['quantity'] + $quantity;
    // 更新購物車項目
    $sql_update_cart_item = "UPDATE cart_item SET quantity = '$new_quantity', price = '$product_price' WHERE id = '" . $row_cart_item['id'] . "'";
    mysqli_query($conn, $sql_update_cart_item);
} else {
    // 購物車項目不存在，添加新項目
    if ($result_product) {
        if ($row_product) {
            // 插入新的購物車項目
            $sql_insert_cart_item = "INSERT INTO cart_item (cart_id, product_id, quantity, price) VALUES ('$cart_id', '$product_id', '$quantity', '$product_price')";
            mysqli_query($conn, $sql_insert_cart_item);
        }
    } else {
        // 處理錯誤情況
        echo "Error: " . mysqli_error($conn);
        exit; // 終止腳本
    }
}

// 重定向到購物車頁面或商品頁面
header("Location: cart.php");
exit();
?>