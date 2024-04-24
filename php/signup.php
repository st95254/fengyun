<?php 
session_start();
if(isset($_SESSION['id'])){
    header("Location: home.php");
    exit; // 確保重定向後停止執行腳本
}

require_once "config.php";
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(isset($_GET['act']) && $_GET['act'] == 'signup'){ // 這裡檢查 'act' 是否設定
    $account = $_POST['account'];
    
    // 檢查帳號是否已經存在
    $sql = "SELECT * FROM user WHERE account = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $account);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0){
        echo "<script>alert('此帳號已是會員！');</script>";
    } else {
        if($_POST['password_check'] != $_POST['password']){
            echo "<script>alert('輸入的密碼不一致');</script>";
        } else {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $sql2 = "INSERT INTO user (account, password) VALUES (?, ?)";
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "ss", $account, $password);
            $result2 = mysqli_stmt_execute($stmt2);
            if($result2){
                echo "<script>alert('註冊成功，請重新登入！');parent.location.href='login.php';</script>";
            } else {
                echo "<script>alert('註冊失敗');</script>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" href="../css/signup.css">
	<script src="../js/signup.js"></script>
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