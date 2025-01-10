<?php
// Configurações de conexão ao banco de dados
$host = "localhost";
$username = "paulo2";
$password = "paulo2";
$dbname = "db_oficina_cabelo";


// Tente criar a conexão PDO
try {
    // Conectando ao banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurando o modo de erro do PDO para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Caso a conexão seja bem-sucedida, você pode realizar operações no banco de dados
} catch (PDOException $e) {
    // Caso haja algum erro na conexão, exibe uma mensagem
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
