<?php
require_once './models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new UserModel($db);
    }

    public function registerCustomer() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->userModel->registerCustomer($_POST);
            if ($success) {
                header('Location: /login.html?status=registered'); //edit kung san ung directory ng login html
                exit();
            } else {
                echo "Registration failed.";
            }
        }
    }


    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->authenticate($username, $password);

            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role_name'];

                if ($user['role_name'] === 'Customer') {
                    header('Location: /index.html');//edit again kung san ung directory ng login html
                exit();
                } else {
                    header('Location: /dashboard.html');//edit again kung san ung directory ng dashboard html
                exit();
                }
                exit();
            } else {
                echo "Invalid credentials.";
            }
        }
    }
}
?>