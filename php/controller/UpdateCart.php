<?php
session_start();
require_once "../config.php";
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    die(json_encode(['error' => "Connection failed: " . mysqli_connect_error()]));
}

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$action = $_POST['action'];
$cart_item_id = intval($_POST['cart_item_id']);
$quantity = intval($_POST['quantity']);

switch ($action) {
    case 'update':
        // 更新數量
        $sql = "UPDATE cart_item SET quantity = '$quantity' WHERE id = '$cart_item_id' AND cart_id = (SELECT id FROM cart WHERE user_id = '$user_id')";
        break;
    case 'delete':
        // 刪除項目
        $sql = "DELETE FROM cart_item WHERE id = '$cart_item_id' AND cart_id = (SELECT id FROM cart WHERE user_id = '$user_id')";
        break;
}

if (isset($sql)) {
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
}

mysqli_close($conn);
?>
