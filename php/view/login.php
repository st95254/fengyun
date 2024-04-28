<?php 
session_start();

// 檢查是否已經登入
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;  // 確保重定向後停止執行腳本
}

require_once "../controller/UserController.php";
$controller = new UserController();

// 處理登入請求
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['account'], $_POST['password'])) {
    $controller->handleLoginRequest();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="../../css/login.css">
    <script src="../../js/login.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <?php
    if (isset($_GET['error']) && $_GET['error'] == 'invalid') {
        echo "<script>alert('帳號或密碼錯誤！');</script>";
    }
    ?>
    
    <!-- 表單提交到自身 -->
    <form action="login.php" method="POST">
        <div class="container">
            <div class="title"><span>登入</span></div>
            <div class="login_sec">
                <div class="input">
                    <input placeholder="帳號" name="account" type="text" required>
                    <input placeholder="密碼" name="password" type="password" required>
                </div>
                <div class="submit">
                    <button type="submit" class="login_btn" id="loginButton" disabled>登入</button>
                </div>
                <hr align="center" width="320px" color="#e6e6e6" SIZE="1">
                <div class="signup">
                    <p>還沒有帳號？</p>
                    <button class="signup_btn" onclick="window.location.href='signup.php'">註冊</button>
                </div>
            </div>
        </div>
    </form>
    <br><br><br>
    
    <hr align="center" width="80%" color="#e6e6e6" SIZE="1">
    <br>
    <?php include 'footer.php'; ?>
</body>
</html>
