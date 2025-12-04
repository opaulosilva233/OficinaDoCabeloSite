<?php
$path_prefix = '../';
require_once '../includes/models/Auth.php';
require_once '../includes/functions.php';

$auth = new Auth();

// If already logged in, redirect to dashboard
if ($auth->isLoggedIn()) {
    redirect('./dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['username'] ?? '';
    $senha = $_POST['password'] ?? '';

    if (empty($login) || empty($senha)) {
        $error = "Por favor, preencha todos os campos.";
    } else {
        if ($auth->login($login, $senha)) {
            redirect('./dashboard.php');
        } else {
            $error = "Credenciais Inválidas!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Oficina do Cabelo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="icon" href="../assets/img/logotipo2.png" type="image/x-icon">
</head>
<body>
<main>
    <div class="login-container">
        <h2><i class="fas fa-lock title-icon"></i> Acesso Autorizado</h2>
        
        <?php if ($error): ?>
            <p class="error"><i class="fas fa-exclamation-circle error-icon"></i> <?= e($error) ?></p>
        <?php endif; ?>

        <form action="./login.php" method="POST">
            <div class="input-group">
                <input type="text" id="username" name="username" required value="<?= e($_POST['username'] ?? '') ?>">
                <label for="username">Utilizador</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" required>
                <label for="password">Palavra-Passe</label>
            </div>
            <button type="submit" class="submit-btn">Entrar</button>
            <a href="../index.php" class="back-home-btn"><i class="fas fa-home"></i> Voltar à Página Inicial</a>
            
            <p class="login-note"><i class="fas fa-lock note-icon"></i> Este login só se destina a pessoas autorizadas.</p>
        </form>
    </div>
</main>
<footer class="footer">
    <p class="credit">Criado por Paulo Silva © <?= date('Y') ?></p>
</footer>
</body>
</html>