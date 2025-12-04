<?php
class CSRF {
    /**
     * Generate a CSRF token and store it in the session.
     *
     * @return string The generated token.
     */
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify the CSRF token from the request.
     *
     * @param string $token The token to verify.
     * @return bool True if valid, false otherwise.
     */
    public static function verifyToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            return true;
        }
        return false;
    }

    /**
     * Render a hidden input field with the CSRF token.
     *
     * @return string HTML input field.
     */
    public static function renderInput() {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
?>
