<?php
session_start();
require_once "../controller/ProductController.php";
$controller = new ProductController();

$product = null;
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = $controller->getProductDetails($id);
}

if (!$product) {
    echo "商品找不到";
    exit; // 如果商品不存在，提前结束脚本
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'head.php'; ?>
        <link rel="stylesheet" href="../../css/product.css">
        <script src="../../js/product.js"></script>
    </head>
    <body>
        <?php include 'header.php'; ?>
        <main class="container">
            <article class="showcase">
                <?php if(isset($product['image'])): ?>
                <img src="<?php echo $product['image']; ?>" class="product-image">
                <?php else: ?>
                <!-- 顯示預設的圖片或不顯示圖片 -->
                <img src="../../element/home_tea.jpg" class="product-image">
                <?php endif; ?>
            </article>
            <aside class="information">
                <div id="frame">
                    <?php if(isset($product['name'])): ?>
                    <div class="name">
                        <p><?php echo $product['name']; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($product['description'])): ?>
                    <div class="description">
                        <p><?php echo $product['description']; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if(isset($product['price'])): ?>
                    <div class="detail">
                        <div class="money">NT$<?php echo $product['price']; ?></div>
                        <div class="counter">
                            <button id="decrease">-</button>
                            <span id="number">1</span>
                            <button id="increase">+</button>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="cart">
                        <div>
                            <form action="../controller/AddToCart.php" method="get">
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