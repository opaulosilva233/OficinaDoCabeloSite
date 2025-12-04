<?php
require_once __DIR__ . '/../Database.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM login WHERE username = :username AND password = :password";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username, ':password' => $password]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            return true;
        }
        return false;
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: ../pages/login.php");
            exit();
        }
    }
    
    public function user() {
        return $_SESSION['username'] ?? null;
    }
}
?>
