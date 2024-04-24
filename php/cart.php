<?php 
session_start();

require_once "config.php";
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['id'] ?? null; // 使用 null 合併運算符來防止未定義索引錯誤
if (!$user_id) {
    echo "<script>alert('使用者尚未登入'); window.location.href = 'login.php';</script>";
    exit;
}
$cart_items = array();

if ($user_id) {
    $sql = "SELECT ci.id AS cart_item_id, ci.product_id, ci.quantity, p.price, p.name, p.image 
            FROM cart_item ci 
            JOIN product p ON ci.product_id = p.id 
            WHERE ci.cart_id = (SELECT id FROM cart WHERE user_id = '$user_id')";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
    }

    // After fetching the cart items, get the cart ID as well
    $cart_id_query = "SELECT id FROM cart WHERE user_id = '$user_id'";
    $cart_id_result = mysqli_query($conn, $cart_id_query);
    $cart_id_row = mysqli_fetch_assoc($cart_id_result);
    $cart_id = $cart_id_row['id'];
}

// 如果購物車是空的，則彈出警告並重定向到首頁
if (empty($cart_items)) {
    echo "<script>alert('您的購物車沒有商品'); window.location.href='home.php';</script>";
    exit; // 結束腳本執行
}

mysqli_close($conn);

// 將購物車項目轉換為 JSON
$cart_items_json = json_encode($cart_items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="../css/cart.css">
    <script src="../js/cart.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="item_header">
            <div class="item">商品</div>
            <div class="name">　　</div>
            <div class="price">單價</div>
            <div class="count">數量</div> 
            <div class="sum">合計</div>
            <div class="operate">　　</div>
        </div>
        <div class="item_container" v-for="(item, index) in itemList" :key="item.id" >
            <div class="item_body">
                <div class="item">
                    <img v-bind:src="item.imgUrl" alt="">
                </div>
                <div class="name">{{item.itemName}}</div>
                <div class="price"><span>$</span>{{item.price}}</div>
                <div class="count">
                        <button @click="handleSub(item)">-</button>
                            {{item.count}}
                        <button @click="handlePlus(item)">+</button>
                </div> 
                <div class="sum"><span>$</span>{{item.price * item.count}}</div>
                <div class="operate">
                        <button @click="handledelete(index)">刪除</button>
                </div>
            </div>
        </div>

        <div class="title">訂單資料</div>
        <div class="lable">
            <p>▌請確實填寫以下資料</p>
        </div>

        <div class="content-wrapper">
            <div class="order_sec">
                <form id="order_form" action="checkout.php" method="post">
                    <div class="input-group">
                        <label for="name">收件人 </label>
                        <input id="name" name="name" type="text" maxlength="100" autocomplete="name" required>
                    </div>
                    <div class="input-group">
                        <label for="phone">聯絡電話 </label>
                        <input id="phone" name="phone" type="text" autocomplete="tel" pattern="^\d{8,15}$" required>
                    </div>
                    <div class="input-group">
                        <label for="address">收貨地址 </label>
                        <input id="address" name="address" type="text" maxlength="100" autocomplete="street-address" require>
                    </div>
                    <div class="input-group">
                        <label for="account">匯款帳號末 5 碼 </label>
                        <input id="account" name="account" type="text" autocomplete="off" pattern="^\d{5}$" required>
                    </div>
                    <div class="input-group">
                        <div>備註</div>
                        <input id="remark" name="remark" type="text" maxlength="100" autocomplete="off">
                    </div>
                    <input type="hidden" id="totalInput" name="totalInput">
                    <input type="hidden" id="shippingFeeInput" name="shippingFeeInput">
                    <input type="hidden" id="orderStatusInput" name="orderStatusInput">
                </form>
            </div>

            <div class="money">
                <div class="line-item">
                    <span class="moneyTitle">商品金額：</span>
                    <span class="amount" id="product">$0</span>
                </div>
                <div class="line-item">
                    <span class="moneyTitle">運費金額：</span>
                    <span class="amount" id="delivery_fee">$60</span>
                </div>
                <div class="line-item total">
                    <span class="moneyTitle">總付款金額：</span>
                    <span class="amount" id="total">$0</span>
                </div>
                <button type="submit" id="submitBtn" form="order_form">提交訂單</button>
            </div>
        </div>

        <div class="note">
            <p>📣 訂單滿 $1000 免運費。</p>
            <p>📣 下單後請將款項匯至 (000) 00000000000000，商品將於確認付款後出貨。</p>
            <p>📣 訂單成立後無法變更配送地址，敬請見諒。 若收件人資訊不完整或配送不成功，商品退回後訂單將進行退款。</p>
        </div>
    </div>

    
    <hr align=center width=80% color=#e6e6e6 SIZE=1>
    <br>
    <?php include 'footer.php'; ?>

    <script>
        var cartItems = <?php echo $cart_items_json ?: '[]' ?>;
    </script>
</body>
</html>