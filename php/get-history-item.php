<?php
session_start();
require_once "config.php";

header('Content-Type: application/json');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$user_id = $_SESSION['id'] ?? null;
$history_id = $_GET['history_id'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'Unauthorized: User not logged in.']);
    exit;
}

if (!$history_id) {
    echo json_encode(['error' => 'Missing history ID.']);
    exit;
}

// Fetch items and shipping fee
$sql = "SELECT hi.id, hi.history_id, hi.product_id, hi.quantity, p.name, hi.price, p.image, h.shipping_fee
        FROM history_item hi
        JOIN product p ON hi.product_id = p.id
        JOIN history h ON hi.history_id = h.id
        WHERE hi.history_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $history_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $history_items = [];
    $shipping_fee = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        if ($shipping_fee === 0) {
            $shipping_fee = $row['shipping_fee'] ? 60 : 0;
        }
        $row['total'] = $row['price'] * $row['quantity'];
        $history_items[] = $row;
    }

    if (empty($history_items)) {
        echo json_encode(['no_records' => true, 'message' => 'No history records found.']);
    } else {
        echo json_encode(['no_records' => false, 'items' => $history_items, 'shipping_fee' => $shipping_fee]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Failed to prepare SQL statement.']);
}

mysqli_close($conn);
?>