<?php
session_start();
require_once "config.php";
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    echo "<script>alert('使用者尚未登入'); window.location.href = 'login.php';</script>";
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="../css/history.css">
    <script src="../js/history.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="item_header">
            <div class="trade_no">訂單編號</div>
            <div class="date">購買日期</div>
            <div class="amount">合計</div>
            <div class="state">訂單狀態</div>
            <div class="btn">　　</div>
        </div>
        <div class="item_body">
            <!-- JavaScript將動態填充這裡的內容 -->
        </div>
    </div>

    <hr align=center width=80% color=#e6e6e6 SIZE=1>
    <br>
    <?php include 'footer.php'; ?>
</body>
</html>
