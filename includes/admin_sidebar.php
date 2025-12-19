<?php
// Get current route for active state
$current_route = $_GET['route'] ?? 'dashboard';
?>
<div class="sidebar" id="sidebar">
    <div class="logo-details">
        <!-- Using logotipo.png which usually has the full bg/contrast -->
        <img src="assets/img/logotipo.png" alt="Logo" style="height: 45px; width: auto; object-fit: contain;">
        <span class="logo_name">Oficina do Cabelo</span>
    </div>
    
    <ul class="nav-links">
        <!-- Section: Geral -->
        <li class="section-header">Geral</li>
        <li>
            <a href="index.php?route=dashboard" class="<?= $current_route == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i>
                <span class="link_name">Dashboard</span>
            </a>
        </li>

        <!-- Section: Agendas -->
        <li class="section-header">Agendas</li>
        <li>
            <a href="index.php?route=agenda&barber=Bruno%20Martins" class="<?= isset($_GET['barber']) && $_GET['barber'] == 'Bruno Martins' ? 'active' : '' ?>">
                <i class="fas fa-cut"></i>
                <span class="link_name">Bruno Martins</span>
            </a>
        </li>
        <li>
            <a href="index.php?route=agenda&barber=Hugo%20Alves" class="<?= isset($_GET['barber']) && $_GET['barber'] == 'Hugo Alves' ? 'active' : '' ?>">
                <i class="fas fa-cut"></i>
                <span class="link_name">Hugo Alves</span>
            </a>
        </li>
        <li>
            <a href="index.php?route=all" class="<?= $current_route == 'all' ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i>
                <span class="link_name">Todas as Marcações</span>
            </a>
        </li>

         <!-- Section: Sistema -->
         <li class="section-header">Sistema</li>
         <li>
            <a href="index.php?route=home">
                <i class="fas fa-external-link-alt"></i>
                <span class="link_name">Ir para o Site</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="index.php?route=logout">
            <i class="fas fa-sign-out-alt"></i>
            <span class="link_name">Sair</span>
        </a>
    </div>
</div>
