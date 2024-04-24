<?php 
    session_start(); 
    require_once "config.php";
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // 檢查連接
    if ($conn->connect_error) {
      die("連接失敗: " . $conn->connect_error);
    }
    
    // 編寫SQL查詢
    $sql = "SELECT * FROM `product` WHERE `product_type` = 'tea'";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'head.php'; ?>
        <link rel="stylesheet" href="../css/tea.css">
    </head>
    <body>
        <?php include 'header.php'; ?>
        
        <main class="container">
            <div class="product_lis">
                <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="card">';
                            echo '<div class="img">';
                            echo '<img src="' . $row["image"] . '">';
                            echo '</div>';
                            echo '<div class="info">';
                            echo '<div class="tt">';
                            // 將商品名稱的連結更新為帶有正確查詢參數的連結
                            echo '<a href="product.php?id=' . $row["id"] . '">' . $row["name"] . '</a>';
                            echo '</div>';
                            echo '<div class="price">';
                            echo '$' . $row["price"] . '元';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "0 個結果";
                    }
                    ?>
            </div>
        </main>
        <br><br><br>
        <hr align=center width=80% color=#e6e6e6 SIZE=1>
        <br>
        <?php include 'footer.php'; ?>
        <?php
            $conn->close(); // 關閉資料庫連接
        ?>
    </body>
</html>