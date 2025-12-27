<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($barberName) ?> - Marcações</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/dashboard-layout.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/dashboard-home.css?v=<?= time() ?>"> 
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
        <input type="hidden" id="barberName" value="<?= htmlspecialchars($barberName) ?>">
        
        <div class="page-header">
            <div>
                <h1 class="page-title">Agenda: <?= htmlspecialchars($barberName) ?></h1>
                <p class="page-subtitle">Gerencie as suas marcações e disponibilidade.</p>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="filters-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Pesquisar por cliente ou telefone...">
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

                <button id="btnPendingAction" class="btn-icon" title="Ver Por Validar (Passadas)" style="color: #f59e0b; border-color: rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-exclamation-triangle"></i>
                </button>

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
                            <th>Data e Hora</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loading State -->
                        <tr>
                            <td colspan="5" class="text-center py-5">
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
        <!-- Modal Footer for Actions -->
        <div class="modal-footer" id="modalFooter" style="display: none;">
             <button id="btnComplete" class="btn-action primary">✅ Concluir</button>
             <button id="btnCancel" class="btn-action danger" style="background: #dc3545; color: white;">❌ Cancelar</button>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="custom-modal small-modal">
    <div class="modal-content-wrapper">
        <div class="modal-header">
            <h2 id="confirmTitle">Confirmação</h2>
            <button class="close-modal-btn" onclick="closeConfirmModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirmMessage" style="font-size: 1.1rem; color: #e0e0e0; text-align: center; margin: 20px 0;">Tem a certeza?</p>
        </div>
        <div class="modal-footer" style="justify-content: center; gap: 20px;">
            <button id="btnConfirmNo" class="btn-action secondary" onclick="closeConfirmModal()" style="background: rgba(255,255,255,0.1); color: #ccc;">Voltar</button>
            <button id="btnConfirmYes" class="btn-action primary">Confirmar</button>
        </div>
    </div>
</div>

<div class="modal-overlay" onclick="closeModal()"></div>

<script src="assets/js/barbeiroAgenda.js?v=<?= time() ?>"></script>
</body>
</html>
