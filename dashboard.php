<?php
session_start();
// Verifica se o utilizador está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); // Redireciona para a página de login caso o utilizador não esteja autenticado
    exit();
}
// Inclui o arquivo de ligação à base de dados
require_once './includes/db.php';
try {
    // Consulta para obter o resumo diário
    $query_daily = "
        SELECT 
            COUNT(*) AS total_bookings,
            SUM(CASE WHEN estado = 'concluida' THEN 1 ELSE 0 END) AS completed_bookings,
            SUM(CASE WHEN estado = 'marcada' THEN 1 ELSE 0 END) AS pending_bookings,
            SUM(CASE WHEN estado = 'cancelada' THEN 1 ELSE 0 END) AS canceled_bookings
        FROM marcacoes
        WHERE data_marcacao = CURDATE()
    ";
    $result_daily = $pdo->query($query_daily); // Usando $pdo aqui
    $daily_summary = $result_daily->fetch(PDO::FETCH_ASSOC);

    // Consulta para obter o resumo semanal
    $query_weekly = "
        SELECT 
            COUNT(*) AS total_bookings,
            SUM(CASE WHEN estado = 'concluida' THEN 1 ELSE 0 END) AS completed_bookings,
            SUM(CASE WHEN estado = 'marcada' THEN 1 ELSE 0 END) AS pending_bookings,
            SUM(CASE WHEN estado = 'cancelada' THEN 1 ELSE 0 END) AS canceled_bookings
        FROM marcacoes
        WHERE YEARWEEK(data_marcacao, 1) = YEARWEEK(CURDATE(), 1)
    ";
    $result_weekly = $pdo->query($query_weekly); // Usando $pdo aqui
    $weekly_summary = $result_weekly->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erro ao consultar a base de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link para o CSS da Dashboard -->
    <link rel="stylesheet" href="./css/dashboard.css"> <!-- Substitua pelo caminho correto -->
    <!-- Incluindo o Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include('./includes/navbarLateral.php'); ?>
</head>
<body>
<!-- Layout principal -->
<div class="dashboard-container">
    <!-- Resumo Diário -->
    <section class="summary">
        <h2>Resumo Diário</h2>
        <div class="summary-item">
            <h3>Total de Marcações</h3>
            <p><?php echo $daily_summary['total_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item">
            <h3>Concluídas</h3>
            <p><?php echo $daily_summary['completed_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item">
            <h3>Marcadas</h3>
            <p><?php echo $daily_summary['pending_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item">
            <h3>Canceladas</h3>
            <p><?php echo $daily_summary['canceled_bookings'] ?? 0; ?></p>
        </div>
    </section>
    <!-- Resumo Semanal -->
    <section class="summary">
        <h2>Resumo Semanal</h2>
        <div class="summary-item">
            <h3>Total de Marcações</h3>
            <p><?php echo $weekly_summary['total_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item">
            <h3>Concluídas</h3>
            <p><?php echo $weekly_summary['completed_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item">
            <h3>Marcadas</h3>
            <p><?php echo $weekly_summary['pending_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item">
            <h3>Canceladas</h3>
            <p><?php echo $weekly_summary['canceled_bookings'] ?? 0; ?></p>
        </div>
    </section>
    <!-- Conteúdo principal -->
    <main class="main-content">
        <h1>Marcações Semanais</h1>
        <!-- Adicionar o gráfico de barras -->
        <div id="chartContainer">
            <canvas id="weeklyChart"></canvas>
        </div>
    </main>
</div>
<!-- Script para gerar o gráfico -->
<script src="./js/chartScript.js"></script> <!-- Arquivo JS separado -->
</body>
</html>