<?php
session_start();
require_once "../controller/ProductController.php";
$controller = new ProductController();

$productIds = $controller->getGoldProductIds();
$goldProducts = $controller->getGoldProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" href="../../css/gold.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../../js/gold.js"></script>
</head>
<body>
    <script>
        var productIds = <?php echo json_encode($productIds); ?>;
        console.log(productIds);
    </script>

	<?php include 'header.php'; ?>

	<!-- Start Price Section -->
    <section id="price_sec">
        <div class="title">
            <h1>金箔即時價格</h1>
        </div>
        <div class="input">
            <h3 style="display: inline-block;">請輸入張數 (最少100，最多10000)</h3>
            <input type="number" value="100" id="LeafAmount">
            <button class="btn-info btn" onclick="GetGoldPrice()">取得時價</button>
        </div>
        <div class="price_table ">
            <table class="table">
                <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>金箔種類</th>
                        <th>金箔大小</th>
                        <th>金箔尺寸</th>
                        <th>價　　格</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>一號金箔</td>
                        <td>9 分</td>
                        <td>2.65公分 x 2.65公分</td>
                        <td id="total0"></td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>一號金箔</td>
                            <td>1 吋 2</td>
                        <td>3.55公分 x 3.55公分</td>
                        <td id="total1"></td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>一號金箔</td>
                        <td>1 吋 8</td>
                        <td>5.40公分 x 5.40公分</td>
                        <td id="total2"></td>
                    </tr>
                    <tr>
                        <th scope="row">4</th>
                        <td>一號金箔</td>
                        <td>3 吋 6</td>
                        <td>10.9公分 x 10.9公分</td>
                        <td id="total3"></td>
                    </tr>
                    <tr>
                        <th scope="row">5</th>
                        <td>四號金箔</td>
                        <td>9 分</td>
                        <td>2.65公分 x 2.65公分</td>
                        <td id="total4"></td>
                    </tr>
                    <tr>
                        <th scope="row">6</th>
                        <td>四號金箔</td>
                        <td>1 吋 2</td>
                        <td>3.55公分 x 3.55公分</td>
                        <td id="total5"></td>
                    </tr>
                    <tr>
                        <th scope="row">7</th>
                        <td>四號金箔</td>
                        <td>1 吋 8</td>
                        <td>5.40公分 x 5.40公分</td>
                        <td id="total6"></td>
                    </tr>
                    <tr>
                        <th scope="row">8</th>
                        <td>四號金箔</td>
                        <td>3 吋 6</td>
                        <td>10.9公分 x 10.9公分</td>
                        <td id="total7"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <!-- End Price Section -->

    <!-- Start Product Section -->
	<section id="product_sec">
        <div class="title"><h1>商品選購</h1></div>
        <div class="product_lis">
            <?php
            if ($goldProducts->num_rows > 0) {
                while($row = $goldProducts->fetch_assoc()) {
                    echo '<div class="card"><div class="img"><img src="' . $row["image"] . '"></div>';
                    echo '<div class="info"><div class="tt"><a href="product.php?id=' . $row["id"] . '">' . $row["name"] . '</a></div>';
                    echo '<div class="price">$' . $row["price"] . '元</div></div></div>';
                }
            } else {
                echo "0 個結果";
            }
            ?>
        </div>
    </section>
	<!-- End Product Section -->

	<!-- Ｓtart Portfolio Section -->
    <section id="protfolio_sec">
        <div class="title">
            <h1>技術與服務</h1>
        </div>
        <div class="info">
            <div class="portfolio-filter">
                <ul class="filter">
                    <li class="active" data-filter="*">全部</li>
                    <li data-filter=".melting">熔化</li>
                    <li data-filter=".extending">延展</li>
                    <li data-filter=".beating">錘製</li>
                    <li data-filter=".interleaving">交錯</li>
                    <li data-filter=".process">加工</li>
                </ul>
            </div>
            <div class="all-portfolios">
                <!-- Melting -->
                <div class="single-portfolio melting">
                    <img class="img-responsive fixsize" src="../../element/gold/metling1.jpg" alt="">
                </div>
            	<!-- Extending -->
                <div class="single-portfolio extending">
                    <img class="img-responsive fixsize" src="../../element/gold/rolling1a.jpg" alt="">
                </div>
                <div class="single-portfolio extending">
                    <img class="img-responsive fixsize" src="../../element/gold/rolling1b.jpg" alt="">
                </div>
                <div class="single-portfolio extending">
                    <img class="img-responsive fixsize" src="../../element/gold/rolling1c.jpg" alt="">
                </div>
            	<!-- beating -->
                <div class="single-portfolio beating">
                    <img class="img-responsive fixsize" src="../../element/gold/beating1.jpg" alt="">
                </div>
                <div class="single-portfolio beating">
                    <img class="img-responsive fixsize" src="../../element/gold/beating2.jpg" alt="">
                </div>
                <div class="single-portfolio beating">
                    <img class="img-responsive fixsize" src="../../element/gold/beating3.jpg" alt="">
                </div>
                <div class="single-portfolio beating">
                    <img class="img-responsive fixsize" src="../../element/gold/beating4.jpg" alt="">
                </div>
                <div class="single-portfolio beating">
                    <img class="img-responsive fixsize" src="../../element/gold/beating5.jpg" alt="">
                </div>
            	<!-- interleaving -->
                <div class="single-portfolio interleaving">
                    <img class="img-responsive fixsize" src="../../element/gold/interleaving1.jpg" alt="">
                </div>
                <div class="single-portfolio interleaving">
                    <img class="img-responsive fixsize" src="../../element/gold/interleaving2.jpg" alt="">
                </div>
                <!-- process -->
                <div class="single-portfolio process">
                    <img class="img-responsive fixsize" src="../../element/gold/examining1.jpg" alt="">
                </div>
                <div class="single-portfolio process">
                    <img class="img-responsive fixsize" src="../../element/gold/packaging1.jpg" alt="">
                </div>
                <div class="single-portfolio process">
                    <img class="img-responsive fixsize" src="../../element/gold/cutting1.jpg" alt="">
                </div>
                <div class="single-portfolio process">
                    <img class="img-responsive fixsize" src="../../element/gold/cutting2.jpg" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- End Portfolio Section -->

	<br><br><br>
	
	<?php include 'footer.php'; ?>
</body>
</html>