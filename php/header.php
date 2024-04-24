<link rel="stylesheet" type="text/css" href="../css/header.css">

<header>
	<div class="header-container">
		<div class="header-left">
			<div class="logo">
				<!-- <img id="icon" src="../elements/bulb.png"> -->
				<a href="home.php">灃耘</a>
			</div>
			<ul id="left-menu">
				<li><a href="gold.php">金箔</a></li>
				<li><a href="tea.php">茶葉</a></li>
			</ul>
		</div>
		<div class="<?php 
				if(isset($_SESSION['id'])){
					echo 'header-right-after' ;
				}else{
					echo 'header-right-before';
				}	
			?>" >
			<!--登入前-->
			<div id="before-login">
				<ul id="right-menu">
					<li><a href="login.php" style="margin-right:28px;">登入</a></li>
					<li><a href="signup.php">註冊</a></li>
				</ul>
			</div>
			<!--登入後-->
			<div id="after-login">
				<ul id="right-menu">
					<li>
        				<a href="cart.php">
            				<img id="cart" src="../element/header_cart.png">
            				<span class="tooltip">前往購物車</span>
            			</a>
        			</li>
        			<li>
        				<img id="user" src="../element/header_user.png">
        				<ul>
							<li><a href="history.php">購買紀錄</a></li>
							<li><a href="logout.php">　登出　</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>