<?php
session_start();

// Verifica se o utilizador está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); // Redireciona para o login caso o utilizador não esteja logado
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <!-- Link para o CSS da Dashboard -->
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Substitua pelo caminho correto -->
    
    <!-- Incluindo o Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include('includes/navbarDashboard.php'); ?>

<!-- Layout principal -->
<div class="dashboard-container">
    <!-- Navbar lateral incluída -->
    <?php include('includes/navbarLateral.php'); ?>

    <!-- Conteúdo principal -->
    <main class="main-content">
        <h1>Marcações Semanais</h1>
        
        <!-- Adicionar o gráfico de barras -->
        <div class="chart-container">
            <canvas id="weeklyChart"></canvas>
        </div>
    </main>
</div>

<div id="chartContainer">
    <canvas id="weeklyChart"></canvas>
</div>

<!-- Cartões de marcações -->
<div class="info-cards">
    <div class="info-card" id="dailyCard">
        <h3>Marcações do Dia</h3>
        <p id="dailyCount">0/30</p> <!-- Preenchido com os dados -->
    </div>
    <div class="info-card" id="weeklyCard">
        <h3>Marcações da Semana</h3>
        <p id="weeklyCount">0/180</p> <!-- Preenchido com os dados -->
    </div>
    <div class="info-card" id="monthlyCard">
        <h3>Marcações do Mês</h3>
        <p id="monthlyCount">0/120</p> <!-- Preenchido com os dados -->
    </div>
</div>

<!-- Script para gerar o gráfico -->
<script src="/js/chartScript.js"></script> <!-- Arquivo JS separado -->
</body>
</html>
