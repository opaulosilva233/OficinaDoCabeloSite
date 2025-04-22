php
<?php
/**
 * Este ficheiro é a página de erro para marcações da barbearia.
 * Ele exibe uma mensagem de erro recebida através do parâmetro 'error' na URL.
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro na Marcação</title>
    <link rel="stylesheet" href="./css/marcacoes.css"> <!-- Liga o CSS da página de marcações -->
    <link rel="icon" href="./img/logotipo2.png" type="image/x-icon"> <!-- Define o favicon da página -->
    <?php include('./includes/header.php'); ?> <!-- Inclui o cabeçalho da página -->
</head>
<body>
    <main>
        <section class="appointment-section">
            <div class="error-message-container">
                <h1>Ocorreu um Erro</h1> <!-- Título principal da página de erro -->
                <?php
                    // Verifica se o parâmetro 'error' está definido na URL.
                    if (isset($_GET['error'])) {
                        // Recupera a mensagem de erro e garante a sua segurança com htmlspecialchars().
                        $errorMessage = htmlspecialchars($_GET['error']);
                        echo "<p class='error-message'>$errorMessage</p>"; // Exibe a mensagem de erro
                    } else {
                        // Se não houver mensagem de erro, exibe uma mensagem padrão.
                        echo "<p class='error-message'>Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.</p>";
                    }
                ?>
                <p>Por favor, tente fazer a sua marcação novamente.</p>
                <a href="marcacoes.php" class="btn-confirm">Voltar para Marcações</a>
            </div>
        </section>
    </main>
    <footer>
        <?php include('./includes/footer.php'); ?> <!-- Inclui o footer da página -->
    </footer>
</body>
</html>