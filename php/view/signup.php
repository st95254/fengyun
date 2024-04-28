<?php 
session_start();

// 檢查是否已經登入
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;  // 確保重定向後停止執行腳本
}

require_once "../controller/UserController.php";
$controller = new UserController();

// 處理註冊請求
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['act']) && $_GET['act'] == 'signup') {
    $account = $_POST['account'];
    $password = $_POST['password'];
    $passwordCheck = $_POST['password_check'];
    $controller->signup($account, $password, $passwordCheck);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" href="../../css/signup.css">
	<script src="../../js/signup.js"></script>
</head>
<body>
	<?php include 'header.php'; ?>

	<div class="container">
		<div class="title"><span>註冊</span></div>
		<div class="signup_sec">
			<div class="lable">
				<p>▌填寫以下資料</p>
			</div>
			<form action="signup.php?act=signup" method="POST" class="signup_form">
				<div id="input">
					<label> 帳號</label>
					<input placeholder="4 ~ 24 位英文或數字" name="account" type="text" required pattern="^([a-zA-Z0-9]){4,24}$">
				</div>
				<div id="input">
					<label> 密碼</label>
					<input placeholder="至少 6 位包含英文及數字" name="password" id="password" type="password" required pattern="^(?=.*[A-Za-z])(?=.*\d).{6,}$">
				</div>
				<div id="input">
					<label> 確認密碼</label>
					<input placeholder="再次輸入密碼" name="password_check" id="password_check" type="password" required>
				</div>
				<div class="confirm">
					<button class="signup_btn" onClick="validateForm(this.form)" disabled>註冊</button>
				</div>
			</form>
		</div>
	</div>
	<br><br><br>
	
	<hr align=center width=80% color=#e6e6e6 SIZE=1>
    <br>
    <?php include 'footer.php'; ?>
</body>
</html>