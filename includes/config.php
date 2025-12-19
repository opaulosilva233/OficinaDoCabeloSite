<?php
// Detect environment
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    // Local Environment
    define('DB_HOST', 'localhost');
    define('DB_USER', 'paulo2');
    define('DB_PASS', 'paulo2');
    define('DB_NAME', 'db_oficina_cabelo');
    define('BASE_URL', '/'); 
} else {
    // Remote Environment (Production)
    define('DB_HOST', 'sql207.infinityfree.com');
    define('DB_USER', 'if0_40722519');
    define('DB_PASS', 'Boaspessoa10000');
    define('DB_NAME', 'if0_40722519_oficina_do_cabelo');
    define('BASE_URL', '/'); // Usually / is correct for root domains
}

// App Configuration
define('APP_NAME', 'Oficina do Cabelo');
?>
