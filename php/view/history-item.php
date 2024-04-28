<?php
session_start();
require_once "../controller/HistoryController.php";

$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    echo "<script>alert('使用者尚未登入'); window.location.href = 'login.php';</script>";
    exit;
}

$history_id = $_GET['history_id'] ?? null;
if (!$history_id) {
    echo "<script>alert('缺少訂單編號'); window.location.href = 'history.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="../../css/history-item.css">
    <script src="../../js/history-item.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="item_header">
            <div class="item">商品</div>
            <div class="name"></div>
            <div class="price">單價</div>
            <div class="count">數量</div> 
            <div class="sum">合計</div>
        </div>

        <div class="item_container">
            <!-- JavaScript將動態填充這裡的內容 -->
        </div>

        <div class="money">
            <div class="line-item">
                <span class="moneyTitle">商品金額：</span>
                <span class="amount" id="product">$0</span>
            </div>
            <div class="line-item">
                <span class="moneyTitle">運費金額：</span>
                <span class="amount" id="delivery_fee">$60</span>
            </div>
            <div class="line-item total">
                <span class="moneyTitle">總付款金額：</span>
                <span class="amount" id="total">$0</span>
            </div>
        </div>
    </div>

    <hr align=center width=80% color=#e6e6e6 SIZE=1>
    <br>
    <?php include 'footer.php'; ?>
</body>
</html>
