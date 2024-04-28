<?php
session_start();
require_once "HistoryController.php";

header('Content-Type: application/json');

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    echo json_encode(['error' => 'Unauthorized: User not logged in.']);
    exit;
}

$controller = new HistoryController();
$history_items = $controller->getHistory($user_id);

if (empty($history_items)) {
    echo json_encode(['no_records' => true, 'message' => 'No history records found.']);
} else {
    echo json_encode(['no_records' => false, 'items' => $history_items]);
}

$controller->close(); // 關閉資料庫連接
?>
