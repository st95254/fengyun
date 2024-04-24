document.addEventListener('DOMContentLoaded', function() {
    const accountInput = document.querySelector('input[name="account"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const loginButton = document.getElementById('loginButton');

    function updateButtonState() {
        loginButton.disabled = !accountInput.value || !passwordInput.value;
    }

    accountInput.oninput = updateButtonState;
    passwordInput.oninput = updateButtonState;
});