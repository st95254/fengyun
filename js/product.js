document.addEventListener('DOMContentLoaded', function () {
    const decreaseButton = document.getElementById('decrease');
    const increaseButton = document.getElementById('increase');
    const numberSpan = document.getElementById('number');
    const quantityInput = document.getElementById('quantity');
    let number = 1;

    function updateQuantityDisplay() {
        numberSpan.textContent = number;
        quantityInput.value = number; // 更新隱藏輸入字段的值
    }

    updateQuantityDisplay(); // 初始化數量顯示

    function updateButtonState() {
        decreaseButton.disabled = number <= 1;
    }

    updateButtonState(); // 初始化按鈕狀態

    decreaseButton.addEventListener('click', function () {
        if (number > 1) {
            number--;
            updateQuantityDisplay();
            updateButtonState();
        }
    });

    increaseButton.addEventListener('click', function () {
        number++;
        updateQuantityDisplay();
        updateButtonState();
    });
});
