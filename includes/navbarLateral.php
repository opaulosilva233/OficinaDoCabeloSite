<!DOCTYPE html>
<html lang="pt-PT"> <!-- Alterado para 'pt-PT' -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controlo</title> <!-- Alterado "Dashboard" para "Painel de Controlo" -->
    <link rel="stylesheet" href="./css/navbarLateral.css"> <!-- Caminho atualizado com './' -->
    <link rel="icon" href="./img/logotipo2.png" type="image/x-icon"> <!-- Caminho atualizado com './' -->
</head>
<body>
  <!-- Barra de Navegação Superior -->
  <header class="top-navbar">
      <div class="logo">
          <a href="./dashboard.php" class="logo"> <!-- Caminho atualizado com './' -->
              <img src="./img/logotipo.png" alt="Logótipo da Barbearia"> <!-- Alterado "Logo" para "Logótipo" -->
              <span>Painel de Controlo</span> <!-- Alterado "Dashboard" para "Painel de Controlo" -->
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
          <li><a href="./dashboard.php" class="sidebar-link">Geral</a></li> <!-- Caminho atualizado com './' -->
          <li><a href="./barbeiro1.php" class="sidebar-link">Bruno Martins</a></li> <!-- Caminho atualizado com './' -->
          <li><a href="./barbeiro2.php" class="sidebar-link">Hugo Alves</a></li> <!-- Caminho atualizado com './' -->
          <li><a href="./todasMarcacoes.php" class="sidebar-link">Todas as Marcações</a></li> <!-- Caminho atualizado com './' -->
          <li><a href="./logout.php" class="sidebar-link">Terminar Sessão</a></li> <!-- Alterado "Logout" para "Terminar Sessão" -->
      </ul>
  </aside>
  <!-- Script para o menu hamburger -->
  <script src="./js/navLatMenu.js"></script> <!-- Caminho atualizado com './' -->
</body>
</html>