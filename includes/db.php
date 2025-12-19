<?php
/**
 * Database connection configuration.
 * Uses PDO for secure database interactions.
 */

// Ensure config is loaded
require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log the error internally (in a real app) and show a generic message
    error_log("Database Connection Error: " . $e->getMessage());
    die("Desculpe, ocorreu um erro ao ligar à base de dados. Por favor, tente novamente mais tarde.");
}
?>