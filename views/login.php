<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Reservada - Oficina do Cabelo</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/transitions.css">
    <link rel="icon" href="assets/img/logotipo2.png" type="image/x-icon">
</head>
<body>
<!-- Page Transition Overlay -->
<div class="page-transition-overlay">
    <div class="transition-panel"></div>
    <div class="transition-panel"></div>
    <div class="transition-panel"></div>
</div>

<main class="login-wrapper">
    <!-- Left Side: Brand Image -->
    <div class="brand-section">
        <div class="brand-overlay"></div>
        <div class="brand-content">
            <img src="assets/img/logotipo.png" alt="Oficina do Cabelo" class="brand-logo">
            <h1>Oficina do Cabelo</h1>
            <p>A excelência na arte de barbear.</p>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="form-section">
        <div class="form-container">
            <div class="form-header">
                <h2>Bem-vindo de volta</h2>
                <p>Aceda à sua área reservada</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-notification">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form action="index.php?route=login" method="POST">
                <?= CSRF::renderInput() ?>
                
                <div class="input-group">
                    <label for="username">Utilizador</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="username" name="username" placeholder="Digite o seu utilizador" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Palavra-passe</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" placeholder="Digite a sua palavra-passe" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <span>Entrar</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="form-footer">
                <a href="index.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Voltar à Página Inicial
                </a>
            </div>
        </div>
        
        <footer class="mini-footer">
            <p>&copy; <?= date('Y') ?> Oficina do Cabelo</p>
        </footer>
    </div>
</main>

<script src="assets/js/transitions.js"></script>
</body>
</html>
