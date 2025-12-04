<?php
$path_prefix = '../';
session_start();
// Verifica se o utilizador está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
// Inclui o arquivo de ligação à base de dados
require_once '../includes/db.php';

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
    $result_daily = $pdo->query($query_daily);
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
    $result_weekly = $pdo->query($query_weekly);
    $weekly_summary = $result_weekly->fetch(PDO::FETCH_ASSOC);

    // Consulta para obter o resumo mensal
    $query_monthly = "
        SELECT 
            COUNT(*) AS total_bookings,
            SUM(CASE WHEN estado = 'concluida' THEN 1 ELSE 0 END) AS completed_bookings,
            SUM(CASE WHEN estado = 'marcada' THEN 1 ELSE 0 END) AS pending_bookings,
            SUM(CASE WHEN estado = 'cancelada' THEN 1 ELSE 0 END) AS canceled_bookings
        FROM marcacoes
        WHERE MONTH(data_marcacao) = MONTH(CURDATE()) AND YEAR(data_marcacao) = YEAR(CURDATE())
    ";
    $result_monthly = $pdo->query($query_monthly);
    $monthly_summary = $result_monthly->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erro ao consultar a base de dados: " . $e->getMessage());
}

// Dados para exportação
$export_data_daily = [
    ['Métrica', 'Valor'],
    ['Total de Marcações', $daily_summary['total_bookings'] ?? 0],
    ['Concluídas', $daily_summary['completed_bookings'] ?? 0],
    ['Marcadas', $daily_summary['pending_bookings'] ?? 0],
    ['Canceladas', $daily_summary['canceled_bookings'] ?? 0]
];

$export_data_weekly = [
    ['Métrica', 'Valor'],
    ['Total de Marcações', $weekly_summary['total_bookings'] ?? 0],
    ['Concluídas', $weekly_summary['completed_bookings'] ?? 0],
    ['Marcadas', $weekly_summary['pending_bookings'] ?? 0],
    ['Canceladas', $weekly_summary['canceled_bookings'] ?? 0]
];

$export_data_monthly = [
    ['Métrica', 'Valor'],
    ['Total de Marcações', $monthly_summary['total_bookings'] ?? 0],
    ['Concluídas', $monthly_summary['completed_bookings'] ?? 0],
    ['Marcadas', $monthly_summary['pending_bookings'] ?? 0],
    ['Canceladas', $monthly_summary['canceled_bookings'] ?? 0]
];

// Função para converter array em CSV
function arrayToCsv($data) {
    $output = '';
    foreach ($data as $row) {
        $output .= implode(',', array_map('strval', $row)) . "\n";
    }
    return $output;
}

// Exportar dados como CSV
$csv_data_daily = arrayToCsv($export_data_daily);
$csv_data_weekly = arrayToCsv($export_data_weekly);
$csv_data_monthly = arrayToCsv($export_data_monthly);

// Formatar a data atual
$current_date = date('d/m/Y');
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link para o CSS da Dashboard -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <!-- Incluindo o Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <?php include('../includes/navbarLateral.php'); ?>
</head>
<body>
<!-- Layout principal -->
<div class="dashboard-container">
    <!-- Filtro de Período -->
    <div class="filter-container">
        <label for="periodFilter">Período: </label>
        <select id="periodFilter" onchange="updateDashboard()">
            <option value="daily">Hoje</option>
            <option value="weekly" selected>Esta Semana</option>
            <option value="monthly">Este Mês</option>
        </select>
        <button id="refreshBtn" class="dashboard-btn"><i class="fas fa-sync-alt"></i> Atualizar</button>
    </div>

    <!-- Resumo Diário -->
    <section class="summary" id="dailySummary">
        <h2>Resumo Diário</h2>
        <p class="summary-note">Dados de hoje: <?php echo $current_date; ?></p>
        <div class="summary-item" onclick="window.location.href='concluidas.php?period=daily'" style="cursor: pointer;">
            <h3><i class="fas fa-calendar-alt"></i> Total de Marcações</h3>
            <p class="total"><?php echo $daily_summary['total_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item" onclick="window.location.href='concluidas.php?period=daily'" style="cursor: pointer;">
            <h3><i class="fas fa-check-circle"></i> Concluídas</h3>
            <p class="completed"><?php echo $daily_summary['completed_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item" onclick="window.location.href='marcadas.php?period=daily'" style="cursor: pointer;">
            <h3><i class="fas fa-clock"></i> Marcadas</h3>
            <p class="pending"><?php echo $daily_summary['pending_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item" onclick="window.location.href='canceladas.php?period=daily'" style="cursor: pointer;">
            <h3><i class="fas fa-times-circle"></i> Canceladas</h3>
            <p class="canceled"><?php echo $daily_summary['canceled_bookings'] ?? 0; ?></p>
        </div>
        <button class="export-btn" onclick="exportToCsv('dailySummary.csv', '<?php echo addslashes($csv_data_daily); ?>')">Exportar como CSV</button>
    </section>

    <!-- Resumo Semanal -->
    <section class="summary" id="weeklySummary">
        <h2>Resumo Semanal</h2>
        <p class="summary-note">Dados desta semana (segunda a sábado)</p>
        <div class="summary-item" onclick="window.location.href='concluidas.php?period=weekly'" style="cursor: pointer;">
            <h3><i class="fas fa-calendar-alt"></i> Total de Marcações</h3>
            <p class="total"><?php echo $weekly_summary['total_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item" onclick="window.location.href='concluidas.php?period=weekly'" style="cursor: pointer;">
            <h3><i class="fas fa-check-circle"></i> Concluídas</h3>
            <p class="completed"><?php echo $weekly_summary['completed_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item" onclick="window.location.href='marcadas.php?period=weekly'" style="cursor: pointer;">
            <h3><i class="fas fa-clock"></i> Marcadas</h3>
            <p class="pending"><?php echo $weekly_summary['pending_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item" onclick="window.location.href='canceladas.php?period=weekly'" style="cursor: pointer;">
            <h3><i class="fas fa-times-circle"></i> Canceladas</h3>
            <p class="canceled"><?php echo $weekly_summary['canceled_bookings'] ?? 0; ?></p>
        </div>
        <button class="export-btn" onclick="exportToCsv('weeklySummary.csv', '<?php echo addslashes($csv_data_weekly); ?>')">Exportar como CSV</button>
    </section>

    <!-- Resumo Mensal -->
    <section class="summary" id="monthlySummary" style="display: none;">
        <h2>Resumo Mensal</h2>
        <p class="summary-note">Dados deste mês</p>
        <div class="summary-item" onclick="window.location.href='concluidas.php?period=monthly'" style="cursor: pointer;">
            <h3><i class="fas fa-calendar-alt"></i> Total de Marcações</h3>
            <p class="total"><?php echo $monthly_summary['total_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item" onclick="window.location.href='concluidas.php?period=monthly'" style="cursor: pointer;">
            <h3><i class="fas fa-check-circle"></i> Concluídas</h3>
            <p class="completed"><?php echo $monthly_summary['completed_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item" onclick="window.location.href='marcadas.php?period=monthly'" style="cursor: pointer;">
            <h3><i class="fas fa-clock"></i> Marcadas</h3>
            <p class="pending"><?php echo $monthly_summary['pending_bookings'] ?? 0; ?></p>
        </div>
        <div class="summary-item" onclick="window.location.href='canceladas.php?period=monthly'" style="cursor: pointer;">
            <h3><i class="fas fa-times-circle"></i> Canceladas</h3>
            <p class="canceled"><?php echo $monthly_summary['canceled_bookings'] ?? 0; ?></p>
        </div>
        <button class="export-btn" onclick="exportToCsv('monthlySummary.csv', '<?php echo addslashes($csv_data_monthly); ?>')">Exportar como CSV</button>
    </section>

    <!-- Conteúdo principal -->
    <main class="main-content">
        <h1>Marcações Semanais</h1>
        <!-- Adicionar o gráfico de barras -->
        <div id="chartContainer">
            <div id="chartLoading" class="loading"><i class="fas fa-spinner fa-spin"></i> Carregando gráfico...</div>
            <div id="chartError" class="error" style="display: none;"><i class="fas fa-exclamation-triangle"></i> Falha ao carregar os dados do gráfico.</div>
            <canvas id="weeklyChart"></canvas>
        </div>
    </main>
</div>
<!-- Script para gerar o gráfico -->
<script src="../assets/js/chartScript.js"></script>
</body>
</html>