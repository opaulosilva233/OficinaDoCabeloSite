<?php
/**
 * Database connection configuration.
 * Uses PDO for secure database interactions.
 */

$host = "localhost";
$username = "paulo2";
$password = "paulo2";
$dbname = "db_oficina_cabelo";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log the error internally (in a real app) and show a generic message
    error_log("Database Connection Error: " . $e->getMessage());
    die("Desculpe, ocorreu um erro ao ligar à base de dados. Por favor, tente novamente mais tarde.");
}
?>