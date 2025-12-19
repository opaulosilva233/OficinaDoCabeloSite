<?php
// Logic is now in AppointmentController::dashboard()
// Variables available: $daily_summary, $weekly_summary, $monthly_summary, $current_date
$current_date = date('d/m/Y');

// Helper for CSV export (can be moved to a separate JS file or kept here for simplicity)
$export_data_daily = [
    ['Métrica', 'Valor'],
    ['Total de Marcações', $daily_summary['total_bookings'] ?? 0],
    ['Concluídas', $daily_summary['completed_bookings'] ?? 0],
    ['Marcadas', $daily_summary['pending_bookings'] ?? 0],
    ['Canceladas', $daily_summary['canceled_bookings'] ?? 0]
];
// ... (CSV logic could be cleaner, but keeping it simple for now)
function arrayToCsv($data) {
    $output = '';
    foreach ($data as $row) {
        $output .= implode(',', array_map('strval', $row)) . "\n";
    }
    return $output;
}
$csv_data_daily = arrayToCsv($export_data_daily);
// ... (omitting other CSVs for brevity, can add back if needed)
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Oficina do Cabelo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- New Admin Layout -->
    <link rel="stylesheet" href="assets/css/admin_layout.css?v=2.8">
    <!-- Existing Dashboard specific styles (if any remain relevant) -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/transitions.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>

    <!-- 1. Sidebar -->
    <?php include('includes/admin_sidebar.php'); ?>

    <!-- 2. Topbar -->
    <?php $page_title = "Dashboard Geral"; ?>
    <?php include('includes/admin_topbar.php'); ?>

    <!-- 3. Main Content -->
    <div class="main-content">
        
        <!-- Filter Bar -->
        <div class="filter-container" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <label for="periodFilter" style="font-weight: 500;">Período: </label>
                <select id="periodFilter" onchange="updateDashboard()" style="padding: 5px; border-radius: 4px; border: 1px solid #ddd;">
                    <option value="daily">Hoje</option>
                    <option value="weekly" selected>Esta Semana</option>
                    <option value="monthly">Este Mês</option>
                </select>
            </div>
            <button id="refreshBtn" class="dashboard-btn" onclick="window.location.reload()" style="background: #d4af37; color: #fff; border:none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-sync-alt"></i> Atualizar
            </button>
        </div>

        <!-- Cards Container (Flexbox) -->
        <div class="cards-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
            
            <!-- We can simplify the PHP visual logic by outputting JS blocks or just raw HTML here. 
                 Using the "Daily" block as default visual, but logic will toggle visibility. -->
            
            <!-- Daily Summary -->
            <div id="dailySummary" class="summary-section">
                 <div style="margin-bottom: 10px; color: #666;">Resumo Diário (<?= $current_date ?>)</div>
                 <div class="stats-row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <!-- Card 1 -->
                    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #d4af37; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="color: #999; font-size: 12px; text-transform: uppercase;">Total</div>
                        <div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $daily_summary['total_bookings'] ?? 0; ?></div>
                    </div>
                    <!-- Card 2 -->
                    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #2ecc71; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="color: #999; font-size: 12px; text-transform: uppercase;">Concluídas</div>
                        <div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $daily_summary['completed_bookings'] ?? 0; ?></div>
                    </div>
                    <!-- Card 3 -->
                    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #f39c12; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="color: #999; font-size: 12px; text-transform: uppercase;">Marcadas</div>
                        <div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $daily_summary['pending_bookings'] ?? 0; ?></div>
                    </div>
                 </div>
            </div>

            <!-- Weekly Summary (Hidden by default or toggled via JS) -->
            <div id="weeklySummary" class="summary-section" style="display:none;">
                 <div style="margin-bottom: 10px; color: #666;">Resumo Semanal</div>
                 <div class="stats-row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #d4af37; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="color: #999; font-size: 12px; text-transform: uppercase;">Total</div>
                        <div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $weekly_summary['total_bookings'] ?? 0; ?></div>
                    </div>
                     <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #2ecc71; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="color: #999; font-size: 12px; text-transform: uppercase;">Concluídas</div>
                        <div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $weekly_summary['completed_bookings'] ?? 0; ?></div>
                    </div>
                    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #f39c12; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="color: #999; font-size: 12px; text-transform: uppercase;">Marcadas</div>
                        <div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $weekly_summary['pending_bookings'] ?? 0; ?></div>
                    </div>
                 </div>
            </div>

            <!-- Monthly Summary (Hidden) -->
             <div id="monthlySummary" class="summary-section" style="display:none;">
                 <div style="margin-bottom: 10px; color: #666;">Resumo Mensal</div>
                 <div class="stats-row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div class="stat-card" style="flex: 1; background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #d4af37; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="color: #999; font-size: 12px; text-transform: uppercase;">Total</div>
                        <div style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $monthly_summary['total_bookings'] ?? 0; ?></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Chart Section -->
        <div class="chart-container" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h2 style="margin-top: 0; font-size: 18px; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">Gráfico de Marcações</h2>
            <div id="chartContainer" style="position: relative; height: 300px;">
                <div id="chartLoading" class="loading"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>
                <div id="chartError" class="error" style="display: none;">Erro ao carregar.</div>
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

    </div>

<script src="assets/js/chartScript.js"></script>
<script src="assets/js/transitions.js"></script>
<script>
    // Simple script to handle the summary visibility logic (ported from original dashboard logic)
    function updateDashboard() {
        const period = document.getElementById('periodFilter').value;
        document.querySelectorAll('.summary-section').forEach(el => el.style.display = 'none');
        
        if (period === 'daily') {
            document.getElementById('dailySummary').style.display = 'block';
        } else if (period === 'weekly') {
            document.getElementById('weeklySummary').style.display = 'block';
        } else if (period === 'monthly') {
            document.getElementById('monthlySummary').style.display = 'block';
        }
    }
    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
         // Default to weekly as per original select default
         document.getElementById('periodFilter').value = 'weekly';
         updateDashboard();
    });
</script>
</body>
</html>