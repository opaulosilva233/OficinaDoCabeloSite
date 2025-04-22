<?php
/**
 * Este ficheiro contém a configuração para a ligação à base de dados.
 */

// Definição das variáveis para a ligação à base de dados
$host = "localhost";
$username = "paulo2";
$password = "paulo2";
$dbname = "db_oficina_cabelo";

// Tenta criar a ligação à base de dados usando PDO
try {
    // Criação de uma nova instância PDO para ligar à base de dados MySQL
    // A string de conexão inclui o tipo de base de dados, o host, o nome da base de dados e o conjunto de caracteres
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Define o modo de erro do PDO para lançar exceções em caso de erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Se a ligação for bem-sucedida, o script continua e as operações na base de dados podem ser realizadas
} catch (PDOException $e) {
    // Em caso de erro na ligação, uma exceção PDOException é lançada
    // O script para e exibe uma mensagem de erro
    die("Erro ao ligar à base de dados: " . $e->getMessage());
}
?>