<?php
// Utility script to create Admin/Barber users
// Usage: Open in browser. This should be renamed or deleted after use in a real public environment.

require_once 'includes/config.php';
require_once 'includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password']; // Do not trim password (some users use spaces)

    if (!empty($username) && !empty($password)) {
        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT id FROM login WHERE username = :username");
            $stmt->execute([':username' => $username]);
            
            if ($stmt->fetch()) {
                $message = '<div class="error">Erro: O utilizador já existe!</div>';
            } else {
                // Create user
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO login (username, password) VALUES (:username, :password)");
                if ($stmt->execute([':username' => $username, ':password' => $hash])) {
                    $message = '<div class="success">Sucesso! Utilizador <strong>' . htmlspecialchars($username) . '</strong> criado.</div>';
                } else {
                    $message = '<div class="error">Erro ao inserir na base de dados.</div>';
                }
            }
        } catch (PDOException $e) {
            $message = '<div class="error">Erro de BD: ' . $e->getMessage() . '</div>';
        }
    } else {
        $message = '<div class="error">Preencha todos os campos.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta de Barbeiro</title>
    <style>
        body { font-family: sans-serif; background: #222; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: #333; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); width: 300px; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; border: none; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #d4af37; color: #000; font-weight: bold; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #c5a028; }
        .success { color: #4cd964; margin-bottom: 10px; text-align: center; }
        .error { color: #ff3b30; margin-bottom: 10px; text-align: center; }
        h2 { text-align: center; margin-top: 0; color: #d4af37; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Novo Barbeiro</h2>
        <?= $message ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Nome de Utilizador" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Criar Conta</button>
        </form>
    </div>
</body>
</html>
