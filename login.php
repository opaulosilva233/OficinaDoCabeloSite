<?php
include('./includes/db.php'); // Inclui o arquivo de ligação à base de dados
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $login = $_POST['username'];
    $senha = $_POST['password']; // Senha não criptografada (adapte conforme necessário)
    try {
        // Preparar a consulta para evitar SQL Injection
        $sql = "SELECT * FROM login WHERE username = :username AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $login, PDO::PARAM_STR);
        $stmt->bindParam(':password', $senha, PDO::PARAM_STR);
        $stmt->execute();
        // Verificar se há resultados
        if ($stmt->rowCount() > 0) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $login;
            header("Location: ./dashboard.php"); // Redireciona para a página da dashboard
            exit();
        } else {
            $error = "Credenciais Inválidas!";
        }
    } catch (PDOException $e) {
        // Exibir mensagem de erro (somente para depuração, remova em produção)
        $error = "Erro ao realizar o login: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
    <link rel="icon" href="./img/logotipo2.png" type="image/x-icon">
</head>
<body>
<main>
    <div class="login-container">
        <h2>Acesso Autorizado</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="./login.php" method="POST">
            <label for="username">Utilizador:</label>
            <input type="text" id="username" name="username" placeholder="Coloque o nome de utilizador" required>
            <label for="password">Palavra-Passe:</label>
            <input type="password" id="password" name="password" placeholder="Coloque a palavra-passe" required>
            <button type="submit" class="submit-btn">Entrar</button>
            <a href="./index.php" class="back-home-btn">Voltar à Página Inicial</a>
            <!-- Nota abaixo dos botões -->
            <p class="login-note">Este login só se destina a pessoas autorizadas.</p>
        </form>
    </div>
</main>
<!-- Rodapé -->
<footer class="footer">
    <p class="credit">Criado por Paulo Silva © 2025</p>
</footer>
</body>
</html>