<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../CSRF.php';

class AuthController {
    public function login() {
        $auth = new Auth();
        
        // If already logged in, redirect to dashboard
        if ($auth->isLoggedIn()) {
            header("Location: " . BASE_URL . "dashboard");
            exit();
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF
            if (!isset($_POST['csrf_token']) || !CSRF::verifyToken($_POST['csrf_token'])) {
                $error = "Erro de segurança (CSRF). Tente novamente.";
            } else {
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';

                if ($auth->login($username, $password)) {
                    header("Location: " . BASE_URL . "dashboard");
                    exit();
                } else {
                    $error = "Credenciais Inválidas!";
                }
            }
        }

        // Load View
        require_once __DIR__ . '/../../views/login.php';
    }

    public function logout() {
        $auth = new Auth();
        $auth->logout();
        header("Location: " . BASE_URL . "login");
        exit();
    }
}
?>
