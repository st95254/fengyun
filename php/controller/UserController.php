<?php
require_once "../model/UserModel.php";

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login($account, $password) {
        $user = $this->userModel->findUserByAccount($account);
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['id'] = $user['id'];
            header("Location: home.php");
            exit;
        }
        header("Location: login.php?error=invalid");
        exit;
    }

    public function logout() {
        session_start();
        unset($_SESSION['id']);
        session_destroy();
        header("Location: ../view/home.php");
        exit;
    }

    public function handleLoginRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['account'], $_POST['password'])) {
            $account = filter_input(INPUT_POST, 'account', FILTER_SANITIZE_STRING);
            $password = $_POST['password'];
            $this->login($account, $password);
        }
    }

    public function signup($account, $password, $passwordCheck) {
        $user = $this->userModel->findUserByAccount($account);
        if ($passwordCheck != $password) {
            echo "<script>alert('輸入的密碼不一致');</script>";
            return;
        }
        
        if ($user) {
            echo "<script>alert('此帳號已是會員！');</script>";
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $isRegistered = $this->userModel->addUser($account, $hashedPassword);

        if ($isRegistered) {
            echo "<script>alert('註冊成功，請重新登入！');parent.location.href='login.php';</script>";
        } else {
            echo "<script>alert('註冊失敗');</script>";
        }
    }
}
