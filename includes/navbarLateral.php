<?php
if (!isset($path_prefix)) {
    $path_prefix = './';
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controlo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/navbarLateral.css">
    <link rel="icon" href="<?php echo $path_prefix; ?>assets/img/logotipo2.png" type="image/x-icon">
</head>
<body>
  <!-- Barra de Navegação Superior -->
  <header class="top-navbar">
      <div class="logo">
          <a href="<?php echo $path_prefix; ?>pages/dashboard.php" class="logo">
              <img src="<?php echo $path_prefix; ?>assets/img/logotipo.png" alt="Logótipo da Barbearia">
              <span>Painel de Controlo</span>
          </a>
      </div>
      <!-- Menu Hamburger -->
      <div id="hamburger" class="hamburger">
          <div></div>
          <div></div>
          <div></div>
      </div>
  </header>
  <!-- Barra de Navegação Lateral -->
  <aside id="menu" class="sidebar">
      <ul>
          <li class="sidebar-section">
              <span class="section-title">Visão Geral</span>
              <ul>
                  <li>
                      <a href="<?php echo $path_prefix; ?>pages/dashboard.php" class="sidebar-link" title="Visão geral da dashboard">
                          <i class="fas fa-tachometer-alt"></i> Geral
                          <span class="loading-icon"><i class="fas fa-spinner fa-spin"></i></span>
                      </a>
                  </li>
              </ul>
          </li>
          <li class="sidebar-section">
              <span class="section-title">Barbeiros</span>
              <ul>
                  <li>
                      <a href="<?php echo $path_prefix; ?>pages/barbeiro1.php" class="sidebar-link" title="Ver agenda de Bruno Martins">
                          <i class="fas fa-user"></i> Bruno Martins
                          <span class="loading-icon"><i class="fas fa-spinner fa-spin"></i></span>
                      </a>
                  </li>
                  <li>
                      <a href="<?php echo $path_prefix; ?>pages/barbeiro2.php" class="sidebar-link" title="Ver agenda de Hugo Alves">
                          <i class="fas fa-user"></i> Hugo Alves
                          <span class="loading-icon"><i class="fas fa-spinner fa-spin"></i></span>
                      </a>
                  </li>
              </ul>
          </li>
          <li class="sidebar-section">
              <span class="section-title">Outros</span>
              <ul>
                  <li>
                      <a href="<?php echo $path_prefix; ?>pages/todasMarcacoes.php" class="sidebar-link" title="Ver todas as marcações">
                          <i class="fas fa-calendar-alt"></i> Todas as Marcações
                          <span class="loading-icon"><i class="fas fa-spinner fa-spin"></i></span>
                      </a>
                  </li>
                  <li>
                      <a href="<?php echo $path_prefix; ?>pages/logout.php" class="sidebar-link logout-link" title="Terminar a sessão atual">
                          <i class="fas fa-sign-out-alt"></i> Terminar Sessão
                          <span class="loading-icon"><i class="fas fa-spinner fa-spin"></i></span>
                      </a>
                  </li>
              </ul>
          </li>
      </ul>
  </aside>
  <!-- Modal de Confirmação para Terminar Sessão -->
  <div id="logoutModal" class="modal">
      <div class="modal-content">
          <h2><i class="fas fa-sign-out-alt modal-icon"></i> Terminar Sessão</h2>
          <p>Tem certeza que deseja terminar a sessão?</p>
          <div class="modal-buttons">
              <button id="confirmLogout" class="modal-btn confirm-btn">Confirmar</button>
              <button id="cancelLogout" class="modal-btn cancel-btn">Cancelar</button>
          </div>
      </div>
  </div>
  <!-- Script para o menu hamburger -->
  <script src="<?php echo $path_prefix; ?>assets/js/navLatMenu.js"></script>
</body>
</html>