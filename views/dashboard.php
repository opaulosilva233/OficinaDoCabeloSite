<?php
// Main Dashboard View
// Data available: $daily_summary, $weekly_summary, $monthly_summary, $next_appointments, $current_date
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Oficina do Cabelo</title>
    <!-- External Libs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/dashboard-layout.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/dashboard-home.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/transitions.css">
</head>
<body>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <?php include('includes/navbarLateral.php'); ?>
    
    <!-- Top Navbar -->
    <?php include('includes/navbarTop.php'); ?>
    
    <!-- Main Content -->
    <main class="main-content-area">
        
        <!-- Welcome Header -->
        <div class="welcome-section" style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h1 class="welcome-title">Bom dia, Equipa!</h1>
                <p class="welcome-subtitle">Aqui está o resumo da atividade para hoje, <?= date('d/m/Y') ?>.</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions" style="display: flex; gap: 10px;">
                <a href="index.php?route=marcacoes" class="btn-action primary">
                    <i class="fas fa-plus"></i> Nova Marcação
                </a>
                <a href="index.php?route=agenda" class="btn-action secondary">
                    <i class="fas fa-calendar-alt"></i> Ver Agenda
                </a>
            </div>
        </div>
        
        <!-- Filter Bar (Moved for better layout) -->
        <div class="filter-bar" style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
             <div class="filter-controls" style="display: flex; gap: 10px; align-items: center;">
                <select id="periodFilter" onchange="updateDashboard()" style="padding: 8px 15px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); background: var(--color-bg-card); color: var(--color-text-main);">
                    <option value="daily">Hoje</option>
                    <option value="weekly" selected>Esta Semana</option>
                    <option value="monthly">Este Mês</option>
                </select>
                <button onclick="window.location.reload()" style="background: none; border: none; cursor: pointer; color: var(--color-text-muted); font-size: 1.1rem;"><i class="fas fa-sync-alt"></i></button>
            </div>
        </div>

        <!-- Stats Grid (Using Weekly Data by Default for now, JS updates numbers) -->
        <div class="stats-grid" id="statsGrid">
            <!-- Total -->
            <div class="stat-card total">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-info">
                    <span class="stat-value" id="val-total"><?php echo $weekly_summary['total_bookings'] ?? 0; ?></span>
                    <span class="stat-label">Total de Marcações</span>
                </div>
            </div>

            <!-- Completed -->
            <div class="stat-card completed">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value" id="val-completed"><?php echo $weekly_summary['completed_bookings'] ?? 0; ?></span>
                    <span class="stat-label">Concluídas</span>
                </div>
            </div>

            <!-- Pending -->
            <div class="stat-card pending">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <span class="stat-value" id="val-pending"><?php echo $weekly_summary['pending_bookings'] ?? 0; ?></span>
                    <span class="stat-label">Por Realizar</span>
                </div>
            </div>

            <!-- Canceled -->
            <div class="stat-card canceled">
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value" id="val-canceled"><?php echo $weekly_summary['canceled_bookings'] ?? 0; ?></span>
                    <span class="stat-label">Canceladas</span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid">
            
            <!-- Chart Section -->
            <div class="dashboard-card chart-card">
                <div class="card-header">
                    <h2 class="card-title">Performance Semanal</h2>
                </div>
                <div id="chartContainer" style="position: relative; height: 300px; width: 100%;">
                    <canvas id="weeklyChart"></canvas>
                    
                    <!-- Loading State -->
                    <div id="chartLoading" class="chart-overlay" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>A carregar dados...</span>
                    </div>
                    
                    <!-- Error State -->
                    <div id="chartError" class="chart-overlay" style="display: none; color: #dc3545;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Erro ao carregar gráfico.</span>
                    </div>
                    
                </div>
                <!-- Legend (Populated by JS) - Moved outside strictly for layout spacing -->
                <div id="chartLegend"></div>
            </div>

            <!-- Upcoming Appointments List -->
            <div class="dashboard-card activity-card">
                <div class="card-header">
                    <h2 class="card-title">Próximas Marcações</h2>
                </div>
                
                <?php if (empty($next_appointments)): ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>Sem marcações próximas.</p>
                    </div>
                <?php else: ?>
                    <ul class="activity-list">
                        <?php foreach ($next_appointments as $appt): ?>
                            <li class="activity-item">
                                <div class="activity-time">
                                    <?= date('H:i', strtotime($appt['horario_marcacao'])) ?>
                                </div>
                                <div class="activity-details">
                                    <span class="activity-title"><?= htmlspecialchars($appt['cliente']) ?></span>
                                    <div class="activity-meta">
                                        <span><i class="fas fa-cut"></i> <?= htmlspecialchars($appt['servico']) ?></span>
                                        <span><i class="fas fa-user-tie"></i> <?= explode(' ', $appt['barbeiro'])[0] ?></span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

        </div>

    </main>
</div>

<!-- JS -->
<script src="assets/js/chartScript.js?v=<?= time() ?>"></script>
<script>
    // Pass PHP data to JS
    const dailyData = <?= json_encode($daily_summary) ?>;
    const weeklyData = <?= json_encode($weekly_summary) ?>;
    const monthlyData = <?= json_encode($monthly_summary) ?>;
</script>
</body>
</html>