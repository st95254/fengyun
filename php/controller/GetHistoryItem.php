<?php
session_start();
require_once "HistoryController.php";

header('Content-Type: application/json');

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

$controller = new HistoryController();
$details = $controller->getHistoryItems($history_id);

if (!$details || empty($details['items'])) {
    echo json_encode(['no_records' => true, 'message' => 'No history records found.']);
} else {
    echo json_encode([
        'no_records' => false, 
        'items' => $details['items'], 
        'shipping_fee' => $details['shipping_fee']
    ]);
}

$controller->close();
?>
