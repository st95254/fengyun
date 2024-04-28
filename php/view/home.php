<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" href="../../css/home.css">
</head>
<body>
	<?php include 'header.php'; ?>

	<div class="banner">
		<div class="welcome">
			<span><i>Welcome</i></span>
			<span><i>to</i></span>
			<span><i>Feng</i></span>
			<span><i>Yun.</i></span>
		</div>
		<div class="slogan">
			<span><i>最高品質是不變的堅持</i></span>
		</div>
	</div>

	<div class="showcase">
  		<a href="gold.php">
    		<img src="../../element/home_gold.jpeg" alt="金箔">
    		<div class="description">金箔</div>
  		</a>
  		<a href="tea.php">
    		<img src="../../element/home_tea.jpg" alt="茶葉">
    		<div class="description">茶葉</div>
  		</a>
	</div>

	<hr align=center width=80% color=#e6e6e6 SIZE=1>
    <br>
    <?php include 'footer.php'; ?>
</body>
</html>