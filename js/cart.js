document.addEventListener('DOMContentLoaded', () => {
    let itemList = cartItems.map(item => ({
        id: item.cart_item_id, // cart_item 表中的 ID
        productId: item.product_id, // product 表中的商品 ID
        itemName: item.name,
        imgUrl: item.image,
        price: item.price,
        count: item.quantity
    }));

    const updateTotalAndFees = () => {
        let total = 0;
        let itemNames = [];
        itemList.forEach(item => {
            total += item.price * item.count;
            itemNames.push(`${item.itemName} x ${item.count}`);
        });

        // 計算運費，如果商品金額超過 $1000 則運費為 $0
        let shippingFeeValue = total > 1000 ? 0 : 60;
        let shippingFee = total > 1000 ? false : true;  // 當總金額超過1000時無運費（false），否則有運費（true）

        // 更新商品金額和總付款金額（包括運費）
        document.getElementById('product').textContent = `$${total}`;
        document.getElementById('delivery_fee').textContent = `$${shippingFeeValue}`;
        document.getElementById('total').textContent = `$${total + shippingFeeValue}`;
        document.getElementById('totalInput').value = total + shippingFeeValue;
        document.getElementById('shippingFeeInput').value = shippingFee;
        document.getElementById('orderStatusInput').value = "處理中";
    };

    const renderItems = () => {
        if (itemList.length === 0) {
            alert('您的購物車沒有商品');
            window.location.href = 'home.php';
            return; // 結束函數的執行
        }

        const itemContainer = document.querySelector('.item_container');
        itemContainer.innerHTML = itemList.map((item, index) => `
            <div class="item_header item_body">
                <div class="item"><img src="${item.imgUrl}" alt=""></div>
                <div class="name">${item.itemName}</div>
                <div class="price"><span>$</span>${item.price}</div>
                <div class="count">
                    <button class="sub" data-index="${index}">-</button>
                    <span>${item.count}</span>
                    <button class="plus" data-index="${index}">+</button>
                </div> 
                <div class="sum"><span>$</span>${item.price * item.count}</div>
                <div class="operate">
                    <button class="delete" data-index="${index}">刪除</button>
                </div>
            </div>
        `).join('');

        itemContainer.onclick = e => {
            const index = e.target.dataset.index;
            const item = itemList[index];
            if (!index || !item) return;

            let action = '';
            let quantity = item.count;

            if (e.target.classList.contains('sub')) {
                if (item.count > 1) {
                    action = 'update';
                    quantity--;
                } else {
                    action = 'delete';
                }
            } else if (e.target.classList.contains('plus')) {
                action = 'update';
                quantity++;
            } else if (e.target.classList.contains('delete')) {
                action = 'delete';
            }

            // 發送 AJAX 請求
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../controller/UpdateCart.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (this.status == 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.error) {
                        alert('Error: ' + response.error);
                    } else {
                        // 更新 itemList 和重新渲染界面
                        if (action === 'update') {
                            itemList[index].count = quantity;
                        } else if (action === 'delete') {
                            itemList.splice(index, 1);
                        }
                        renderItems();
                        updateTotalAndFees();
                    }
                }
            };
            xhr.send(`action=${action}&cart_item_id=${item.id}&quantity=${quantity}`);
        };
    };

    // 監聽欄位輸入
    const orderForm = document.getElementById('order_form');
    const requiredFields = document.querySelectorAll('#order_form input[required]');
    const phoneField = document.getElementById('phone');
    const accountField = document.getElementById('account');
    const submitButton = document.getElementById('submitBtn');
    if (submitButton) {
        submitButton.disabled = true; // 確保初始狀態為禁用
    }

    // Serialize the itemList into a JSON string and set it as a hidden input's value
    function prepareCartData() {
        const cartDataInput = document.getElementById('cart_data');
        if (!cartDataInput) {
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'cart_items');
            hiddenInput.setAttribute('id', 'cart_data');
            orderForm.appendChild(hiddenInput);
        }
        document.getElementById('cart_data').value = JSON.stringify(itemList);
    }

    function checkFormInput() {
        // 檢查每個必填欄位是否都已被填寫且符合要求
        let allValid = true;
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                allValid = false;
            }
        });

        // 檢查電話號碼格式
        const phoneRegex = /^\d{8,15}$/; // 匹配8到15位數字
        if (!phoneRegex.test(phoneField.value)) {
            allValid = false;
        }

        // 檢查帳號末5碼格式
        const accountRegex = /^\d{5}$/; // 匹配恰好5位數字
        if (!accountRegex.test(accountField.value)) {
            allValid = false;
        }

        // 如果所有欄位都填寫且符合格式，啟用按鈕；否則，禁用按鈕
        submitButton.disabled = !allValid;
    }

    if (submitButton) {
        submitButton.addEventListener('click', function(event) {
            // Prevent the default form submission
            event.preventDefault();
            
            // Prepare the cart data for submission
            prepareCartData();

            // If everything is valid, submit the form
            if (!submitButton.disabled) {
                orderForm.submit();
            }
        });
    }

    requiredFields.forEach(field => {
        field.addEventListener('input', checkFormInput);
    });

    document.getElementById("submitBtn").addEventListener("click", function() {
        alert("提醒您！！！ 下單後請將款項匯至 (000) 00000000000000，商品將於確認付款後出貨。");
    });

    renderItems();
    updateTotalAndFees();
    checkFormInput();
});


