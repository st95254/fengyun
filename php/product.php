<?php
	session_start();
	// 檢查是否存在URL參數'id'
	if(isset($_GET['id'])) {
	    // 獲取參數
	    $id = $_GET['id'];
	
		require_once "config.php";
		$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	
	    // 檢查連接
	    if ($conn->connect_error) {
	        die("連接失敗: " . $conn->connect_error);
	    }
	
	    // 防止SQL注入
	    $id = $conn->real_escape_string($id);
	
	    // 編寫SQL查詢
	    $sql = "SELECT * FROM `product` WHERE id = $id";
	    $result = $conn->query($sql);
	
	    if ($result->num_rows > 0) {
	        // 獲取相關商品資訊
	        $row = $result->fetch_assoc();
	        $productName = $row["name"];
	        $productPrice = $row["price"];
	        $productImage = $row["image"];
	        $productDescription = $row["description"];
	    } else {
	        echo "商品找不到";
	    }
	
	    $conn->close();
	}
	?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include 'head.php'; ?>
		<link rel="stylesheet" href="../css/product.css">
		<script src="../js/product.js"></script>
	</head>
	<body>
		<?php include 'header.php'; ?>
		<main class="container">
			<article class="showcase">
				<?php if(isset($productImage)): ?>
				<img src="<?php echo $productImage; ?>" class="product-image">
				<?php else: ?>
				<!-- 顯示預設的圖片或不顯示圖片 -->
				<img src="../element/home_tea.jpg" class="product-image">
				<?php endif; ?>
			</article>
			<aside class="imformation">
				<div id="frame">
					<?php if(isset($productName)): ?>
					<div class="name">
						<p><?php echo $productName; ?></p>
					</div>
					<?php endif; ?>
					<?php if(isset($productDescription)): ?>
					<div class="description">
						<p><?php echo $productDescription; ?></p>
					</div>
					<?php endif; ?>
					<?php if(isset($productPrice)): ?>
					<div class="detail">
						<div class="money">NT$<?php echo $productPrice; ?></div>
						<div class="counter">
							<button id="decrease">-</button>
							<span id="number">1</span>
							<button id="increase">+</button>
						</div>
					</div>
					<?php endif; ?>
					<div class="cart">
						<div>
							<form action="add-to-cart.php" method="get">
            					<input type="hidden" name="product_id" value="<?php echo $id; ?>">
            					<input type="hidden" id="quantity" name="quantity" value="1">
            					<button type="submit" class="add_btn">加入購物車</button>
        					</form>
						</div>
					</div>
				</div>
			</aside>
		</main>
		<hr align=center width=80% color=#e6e6e6 SIZE=1>
		<br>
		<?php include 'footer.php'; ?>
	</body>
</html>