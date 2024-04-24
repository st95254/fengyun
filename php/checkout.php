<?php
session_start();

require_once "config.php";
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// 接收 POST 表單數據
$name = mysqli_real_escape_string($conn, $_POST['name']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$account = mysqli_real_escape_string($conn, $_POST['account']);
$remark = mysqli_real_escape_string($conn, $_POST['remark'] ?? ''); // 備註可能是空的
$trade_no = date('YmdHis') . $user_id; // Generate trade_no using the current date and time and user_id
$total = (int) $_POST['totalInput'];
$date = date('Y-m-d H:i:s');
$shippingFee = $_POST['shippingFeeInput'] === 'true' ? 1 : 0;  // 從表單中獲取運費布林值，轉換為整數
$orderStatus = $_POST['orderStatusInput'];  // 從表單中獲取訂單狀態

// 驗證輸入數據
if (!preg_match('/^\d{8,15}$/', $phone)) {
    echo json_encode(["error" => "Invalid phone number format"]);
    exit;
}

if (!preg_match('/^\d{5}$/', $account)) {
    echo json_encode(["error" => "Account number must be 5 digits"]);
    exit;
}

// 插入購物歷史記錄到 history 表
$sql_history = "INSERT INTO history (user_id, name, phone, address, account, remark, trade_no, total, date, shipping_fee, status) VALUES ('$user_id', '$name', '$phone', '$address', '$account', '$remark', '$trade_no', $total, '$date', '$shippingFee', '$orderStatus')";
if (mysqli_query($conn, $sql_history)) {
    $history_id = mysqli_insert_id($conn);

    // 此處假設 $cart_items 是從購物車頁面或 sessionStorage/localStorage 中以 JSON 形式傳遞而來
    $cart_items = json_decode($_POST['cart_items'], true);

    // 循環遍歷 $cart_items 並插入到 history_item 表中
    foreach ($cart_items as $item) {
        $product_id = $item['productId'];
        $quantity = $item['count'];
        $price = $item['price'];

        $sql_history_item = "INSERT INTO history_item (history_id, product_id, quantity, price) VALUES ('$history_id', '$product_id', '$quantity', '$price')";

        if (!mysqli_query($conn, $sql_history_item)) {
            echo json_encode(["error" => "Error in inserting history item: " . mysqli_error($conn)]);
            exit;
        }
    }

    // 清空購物車
    $sql_clear_cart = "DELETE FROM cart_item WHERE cart_id = (SELECT id FROM cart WHERE user_id = '$user_id')";
    if (!mysqli_query($conn, $sql_clear_cart)) {
        echo json_encode(["error" => "Error clearing cart: " . mysqli_error($conn)]);
        exit;
    }

    echo json_encode(["success" => "Checkout successful", "history_id" => $history_id]);
} else {
    echo json_encode(["error" => "Error in inserting history: " . mysqli_error($conn)]);
}

mysqli_close($conn);

// After successfully inserting everything and clearing the cart
header('Location: order.php'); // Redirect to the success page
exit;
?>
