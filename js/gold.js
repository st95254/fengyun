function GetGoldPrice() {
    var amount = $("#LeafAmount").val();
    if (amount < 100 || amount > 10000) {
        alert('請輸入數量在 100 到 10000 之間');
        return;
    }
    // 將RequestURL設置為proxy.php路徑(目前位置為php/view/)，並附上任何必要的查詢參數
    var RequestURL = "../api/proxy.php?Curr=NT&OrderQty=" + amount;
    
    console.log($('meta[name="csrf-token"]').attr('content'));
    $.ajax({
        url: RequestURL,
        type: "get", // 因為proxy.php使用$_GET來獲取參數，這裡應該是get請求
        dataType: "json", // 明確指定期待返回的數據類型是JSON
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (result) {
            console.log(result);
            for(var i = 0; i < result.length; i++) {
                var totalPrice = result[i].price.replace("NT$",""); // 使用result[i].price來獲取價格
                totalPrice = totalPrice.replace(",","");
                var totalPriceNumber = Number(totalPrice) * 1.1;
                $("#total" + i).text("NT$" + totalPriceNumber.toFixed(0));

                if (amount == 100) { // 僅當 amount 等於 100 時更新價格
                    // 使用從PHP獲取的產品ID
                    var productId = productIds[i];
                    var newPrice = totalPriceNumber.toFixed(0);
                    updateProductPrice(productId, newPrice);
                }
            }
            
        },
        error: function(xhr, status, error) {
            console.error("Error: " + status + " " + error);
        }
    });
}


// 發送AJAX請求更新價格
function updateProductPrice(productId, newPrice) {
    $.ajax({
        url: "../controller/UpdatePrice.php", // PHP檔案路徑
        type: "post",
        data: {
            id: productId,
            price: newPrice
        },
        success: function(data) {
            console.log("Price updated for product " + productId);
        },
        error: function(xhr, status, error) {
            console.error("Error updating price: " + status + " " + error);
        }
    });
}

$(document).ready(function() {
    // 在頁面加載完成後自動調用以取得時價
    GetGoldPrice();

    // 設定作品集過濾功能
    $('.portfolio-filter li').click(function(){
        var category = $(this).attr('data-filter');
        $('.portfolio-filter li').removeClass('active');
        $(this).addClass('active');

        if(category == '*'){
            $('.single-portfolio').show();
        } else {
            $('.single-portfolio').hide();
            $(category).show(); 
        }
    });
});
