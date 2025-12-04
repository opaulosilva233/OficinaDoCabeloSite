<?php
/**
 * Helper functions for the application.
 */

/**
 * Escapes a string for safe output in HTML.
 * Prevents XSS attacks.
 *
 * @param string $string The string to escape.
 * @return string The escaped string.
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirects to a specified URL.
 *
 * @param string $url The URL to redirect to.
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Checks if the user is logged in.
 *
 * @return bool True if logged in, false otherwise.
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Requires the user to be logged in.
 * Redirects to login page if not.
 *
 * @param string $loginPagePath Path to the login page (relative).
 */
function requireLogin($loginPagePath = 'login.php') {
    if (!isLoggedIn()) {
        redirect($loginPagePath);
    }
}
?>
