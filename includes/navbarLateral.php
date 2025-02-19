<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/navbarLateral.css"> <!-- Link para o CSS externo -->
    <link rel="icon" href="img/logotipo2.png" type="image/x-icon">
</head>
<body>

  <!-- Navbar Superior -->
  <header class="top-navbar">
      <div class="logo">
          <a href="dashboard.php" class="logo">
              <img src="/img/logotipo.png" alt="Logo Barbearia">
              <span>Dashboard</span>
          </a>
      </div>

      <!-- Menu Hamburger -->
      <div id="hamburger" class="hamburger">
          <div></div>
          <div></div>
          <div></div>
      </div>
  </header>

  <!-- Navbar Lateral -->
  <aside id="menu" class="sidebar">
      <ul>
          <li><a href="dashboard.php" class="sidebar-link">Geral</a></li>
          <li><a href="barbeiro1.php" class="sidebar-link">Bruno Martins</a></li>
          <li><a href="barbeiro2.php" class="sidebar-link">Hugo Alves</a></li>
          <li><a href="todas_marcacoes.php" class="sidebar-link">Todas Marcações</a></li>
          <li><a href="logout.php" class="sidebar-link">Logout</a></li>
      </ul>
  </aside>

<script src="js/navLatMenu.js"></script> <!-- Script para o menu hamburger -->
</body>
</html>
