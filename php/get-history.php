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
if (!$user_id) {
    echo json_encode(['error' => 'Unauthorized: User not logged in.']);
    exit;
}

// 更新了SQL查询以包括id字段
$sql = "SELECT h.id, h.trade_no, h.date, h.total, h.status
        FROM history h
        WHERE h.user_id = ?
        ORDER BY h.date DESC";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $history_items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $history_items[] = $row;
    }

    if (empty($history_items)) {
        echo json_encode(['no_records' => true, 'message' => 'No history records found.']);
    } else {
        echo json_encode(['no_records' => false, 'items' => $history_items]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Failed to prepare SQL statement.']);
}

mysqli_close($conn);
?>
