<?php
session_start();
$account = filter_input(INPUT_POST, 'account', FILTER_SANITIZE_STRING);
$password = $_POST['password']; // 不要在這裡加密，後面會使用password_verify來檢查

// 引入配置文件
require_once "config.php";
// 使用配置文件中的變量來連接數據庫
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

$sql = "SELECT * FROM user WHERE account = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $account);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['id'] = $row['id'];
        header("Location: home.php");
        exit;
    }
}

header("Location: login.php?error=invalid");
exit;?>
