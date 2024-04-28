<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="../../css/order.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="content">
            <p>感謝您的訂購！<p>
            <p>📣 下單後請將款項匯至 (000) 00000000000000，商品將於確認付款後出貨。</p>
            <p>📣 訂單成立後無法變更配送地址，敬請見諒。 若收件人資訊不完整或配送不成功，商品退回後訂單將進行退款。</p>
        </div>
        <button onclick="window.location.href='home.php'" id="btn">回首頁</button>
    </div>

    <hr align=center width=80% color=#e6e6e6 SIZE=1>
    <br>
    <?php include 'footer.php'; ?>
</body>
</html>
