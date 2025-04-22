<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }
        .error-container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .error-message {
            color: #d9534f;
            margin-bottom: 20px;
        }
        .back-button {
            background-color: #5bc0de;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-message" id="errorMessage"></h1>
        <a href="marcacoes.php" class="back-button">Voltar</a>
    </div>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const errorMessage = urlParams.get('error');
        document.getElementById('errorMessage').textContent = errorMessage || 'Ocorreu um erro desconhecido.';
    </script>
</body>
</html>