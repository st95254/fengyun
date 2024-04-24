// 監聽輸入欄位的變化
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.signup_form input');
    inputs.forEach(input => {
        input.addEventListener('keyup', updateButtonState);
        input.addEventListener('change', updateButtonState);
    });
});

function updateButtonState() {
    const form = document.querySelector('.signup_form');
    const accountValid = checkAccount(form.account, false);
    const passwordValid = checkPassword(form.password, false);
    const passwordCheckValid = checkPassword_check(form.password_check, false);
    const signupButton = document.querySelector('.signup_btn');
    if (accountValid && passwordValid && passwordCheckValid) {
        signupButton.removeAttribute('disabled');
    } else {
        signupButton.setAttribute('disabled', 'disabled');
    }
    console.log('account:', form.account);
    console.log('password:', form.password);
    console.log('password_check:', form.password_check);
}

function checkAccount(control, showAlert = true) {
    rule = /^([a-zA-Z0-9]){4,24}$/
    if (rule.test(control.value) == false) {
        if (showAlert) validatePrompt(control, "請輸入有效的帳號");
        return false;
    }
    return true;
}

function checkPassword(control, showAlert = true) {
    rule = /^(?=.*[A-Za-z])(?=.*\d).{6,}$/;
    if (rule.test(control.value) == false) {
        if (showAlert) validatePrompt(control, "請輸入至少 6 位包含英文及數字的密碼");
        return false;
    }
    return true;
}

function checkPassword_check(control, showAlert = true) {
    const password = document.getElementById("password").value;
	const password_check = document.getElementById("password_check").value;
    if (rule.test(control.value) == false) {
        if (showAlert) validatePrompt(control, "您輸入的密碼不一致");
        return false;
    }
    return true;
}

// 修改validateForm函數以僅在按鈕點擊時觸發彈出提示
function validateForm(form) {
    if (!checkAccount(form.account, true) || !checkPassword(form.password, true) || !checkPassword_check(form.password_check, true)) {
        event.preventDefault();
        return false;
    }
    form.submit();
}