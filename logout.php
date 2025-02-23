<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminar Sessão - Barbershop</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="./css/logout.css">
</head>
<body>
    <main>
        <div class="logout-container">
            <h1>A terminar sessão...</h1>
            <p>A sua sessão será encerrada em breve. Obrigado por utilizar o nosso sistema!</p>
            <div class="spinner"></div>
            <p class="redirect-message">Será redirecionado em <span id="countdown">5</span> segundos.</p>
            <a href="/" class="button">Voltar à Página Inicial</a>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p class="credit">Criado por Paulo Silva &copy; 2025</p>
    </footer>

    <!-- Script JavaScript -->
    <script>
        // Função para redirecionar após o logout
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');

        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = '/'; // Redireciona para a página inicial
            }
        }, 1000);
    </script>
</body>
</html>