<?php 
session_start();
require_once "../controller/ProductController.php";

$controller = new ProductController();
$teaProducts = $controller->getTeaProducts();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'head.php'; ?>
        <link rel="stylesheet" href="../../css/tea.css">
    </head>
    <body>
        <?php include 'header.php'; ?>
        
        <main class="container">
            <div class="product_lis">
                <?php
                    if ($teaProducts->num_rows > 0) {
                        while($row = $teaProducts->fetch_assoc()) {
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
    </body>
</html>