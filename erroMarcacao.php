<?php
// Verificar se existe uma mensagem de erro na URL
$errorMessage = isset($_GET['error']) ? $_GET['error'] : 'Ocorreu um erro desconhecido.';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro na Marcaçâo</title>
    <link rel="stylesheet" href="/css/erroMarcacao.css"> <!-- Referência ao arquivo CSS externo -->
</head>
<body>
    <main>
        <div class="login-container">
            <h2>Erro ao Realizar a Marcaçâo</h2>
            <p class="error"><?php echo $errorMessage; ?></p>
            <a href="marcacoes.php" class="back-home-btn">Voltar às Marcações</a>
        </div>
    </main>
</body>
</html>
