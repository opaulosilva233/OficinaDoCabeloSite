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
        $sql = "SELECT * FROM login WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
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
            // Adjust path based on where this is called. 
            // In MVC, we might redirect to index.php?route=login
            // For now, keep relative path or absolute path logic
            header("Location: /pages/login.php"); 
            exit();
        }
    }
    
    public function user() {
        return $_SESSION['username'] ?? null;
    }
}
?>
