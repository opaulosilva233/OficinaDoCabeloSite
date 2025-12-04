<?php
$path_prefix = '../';
require_once '../includes/models/Auth.php';
require_once '../includes/models/Appointment.php';
require_once '../includes/functions.php'; // For e() helper if needed, though dashboard mostly uses numbers

$auth = new Auth();
$auth->requireLogin();

$appointment = new Appointment();

try {
    $daily_summary = $appointment->getSummary('daily');
    $weekly_summary = $appointment->getSummary('weekly');
    $monthly_summary = $appointment->getSummary('monthly');
} catch (Exception $e) {
    error_log("Dashboard Error: " . $e->getMessage());
    die("Erro ao carregar os dados da dashboard.");
}

// Data for CSV export
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

// Function to convert array to CSV string
function arrayToCsv($data) {
    $output = '';
    foreach ($data as $row) {
        $output .= implode(',', array_map('strval', $row)) . "\n";
    }
    return $output;
}

$csv_data_daily = arrayToCsv($export_data_daily);
$csv_data_weekly = arrayToCsv($export_data_weekly);
$csv_data_monthly = arrayToCsv($export_data_monthly);

$current_date = date('d/m/Y');
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Oficina do Cabelo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <?php include('../includes/navbarLateral.php'); ?>
</head>
<body>
<div class="dashboard-container">
    <!-- Filter -->
    <div class="filter-container">
        <label for="periodFilter">Período: </label>
        <select id="periodFilter" onchange="updateDashboard()">
            <option value="daily">Hoje</option>
            <option value="weekly" selected>Esta Semana</option>
            <option value="monthly">Este Mês</option>
        </select>
        <button id="refreshBtn" class="dashboard-btn" onclick="window.location.reload()"><i class="fas fa-sync-alt"></i> Atualizar</button>
    </div>

    <!-- Daily Summary -->
    <section class="summary" id="dailySummary">
        <h2>Resumo Diário</h2>
        <p class="summary-note">Dados de hoje: <?= $current_date ?></p>
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

    <!-- Weekly Summary -->
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

    <!-- Monthly Summary -->
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

    <!-- Main Content -->
    <main class="main-content">
        <h1>Marcações Semanais</h1>
        <div id="chartContainer">
            <div id="chartLoading" class="loading"><i class="fas fa-spinner fa-spin"></i> Carregando gráfico...</div>
            <div id="chartError" class="error" style="display: none;"><i class="fas fa-exclamation-triangle"></i> Falha ao carregar os dados do gráfico.</div>
            <canvas id="weeklyChart"></canvas>
        </div>
    </main>
</div>
<script src="../assets/js/chartScript.js"></script>
</body>
</html>