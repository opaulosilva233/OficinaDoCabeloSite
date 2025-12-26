<?php
// All Bookings View
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas as Marcações - Oficina do Cabelo</title>
    <!-- External Libs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/dashboard-layout.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/dashboard-home.css?v=<?= time() ?>"> <!-- Reusing some dashboard styles -->
    <link rel="stylesheet" href="assets/css/todasMarcacoes.css?v=<?= time() ?>">
</head>
<body>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <?php include('includes/navbarLateral.php'); ?>
    
    <!-- Top Navbar -->
    <?php include('includes/navbarTop.php'); ?>
    
    <!-- Main Content -->
    <main class="main-content-area">
        
        <div class="page-header">
            <div>
                <h1 class="page-title">Todas as Marcações</h1>
                <p class="page-subtitle">Gerencie todas as marcações do sistema.</p>
            </div>
            <a href="index.php?route=marcacoes" class="btn-action primary">
                <i class="fas fa-plus"></i> Nova Marcação
            </a>
        </div>

        <!-- Filters & Search -->
        <div class="filters-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por nome ou telefone...">
            </div>
            
            <!-- Filter Actions Group -->
            <div class="filters-actions">
                <!-- Status Filter -->
                <div class="filter-group">
                    <select id="statusFilter" class="filter-select">
                        <option value="">Todos os Estados</option>
                        <option value="marcada">Marcada</option>
                        <option value="concluida">Concluída</option>
                        <option value="pendente">Pendente</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <!-- Date Filters -->
                <div class="filter-group date-filters">
                    <input type="date" id="dateStart" class="filter-input" placeholder="Data Início" title="Data Início">
                    <span class="date-separator">-</span>
                    <input type="date" id="dateEnd" class="filter-input" placeholder="Data Fim" title="Data Fim">
                </div>

                <button id="refreshBtn" class="btn-icon" title="Limpar Filtros e Atualizar"><i class="fas fa-sync-alt"></i></button>
            </div>
        </div>

        <!-- Appointments Table Card -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="premium-table" id="appointmentsTable">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Serviço</th>
                            <th>Barbeiro</th>
                            <th>Data e Hora</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loading State -->
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-spinner fa-spin fa-2x"></i>
                                <p class="mt-2">A carregar marcações...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                <span class="pagination-info">Mostrando <span id="showingStart">0</span> a <span id="showingEnd">0</span> de <span id="totalItems">0</span></span>
                <div class="pagination-controls">
                    <button id="prevPage" disabled><i class="fas fa-chevron-left"></i></button>
                    <span id="currentPageDisplay">Pag. 1</span>
                    <button id="nextPage" disabled><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>

    </main>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="custom-modal">
    <div class="modal-content-wrapper">
        <div class="modal-header">
            <h2>Detalhes da Marcação</h2>
            <button class="close-modal-btn" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Content filled by JS -->
            <div class="loading-modal">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" onclick="closeModal()"></div>

<script src="assets/js/todasMarcacoes.js?v=<?= time() ?>"></script>
</body>
</html>