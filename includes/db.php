<?php
// Configurações de ligação à base de dados
$host = "localhost";
$username = "paulo2";
$password = "paulo2";
$dbname = "db_oficina_cabelo";

// Tente criar a ligação PDO
try {
    // Ligando à base de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurando o modo de erro do PDO para excepções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Caso a ligação seja bem-sucedida, pode realizar operações na base de dados
} catch (PDOException $e) {
    // Caso ocorra algum erro na ligação, exibe uma mensagem
    die("Erro ao ligar à base de dados: " . $e->getMessage());
}
?>