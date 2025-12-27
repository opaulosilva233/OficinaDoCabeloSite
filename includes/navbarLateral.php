<?php
// This file is now included from index.php (root)
// So paths should be relative to root
$path_prefix = './'; 
?>
<div class="sidebar-area">
    <div class="brand-box">
        <img src="<?= $path_prefix ?>assets/img/logotipo.png" alt="Logo">
        <span class="logo-text">OFICINA</span>
    </div>
    
    <div class="sidebar-menu">
        <div class="menu-category">Menu Principal</div>
        <a href="<?= BASE_URL ?>dashboard" class="menu-item <?= (isset($_GET['route']) && $_GET['route'] == 'dashboard') ? 'active' : '' ?>" title="Dashboard">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= BASE_URL ?>todas_marcacoes" class="menu-item <?= (isset($_GET['route']) && $_GET['route'] == 'todas_marcacoes') ? 'active' : '' ?>" title="Todas as Marcações">
            <i class="fas fa-calendar-alt"></i>
            <span>Todas as Marcações</span>
        </a>

        <div class="menu-category">Barbeiros</div>
        <a href="<?= BASE_URL ?>agenda?barber=Bruno%20Martins" class="menu-item <?= (isset($_GET['barber']) && $_GET['barber'] == 'Bruno Martins') ? 'active' : '' ?>" title="Bruno Martins">
            <i class="fas fa-user-tie"></i>
            <span>Bruno Martins</span>
        </a>
        <a href="<?= BASE_URL ?>agenda?barber=Hugo%20Alves" class="menu-item <?= (isset($_GET['barber']) && $_GET['barber'] == 'Hugo Alves') ? 'active' : '' ?>" title="Hugo Alves">
            <i class="fas fa-user-tie"></i>
            <span>Hugo Alves</span>
        </a>

        <div class="menu-category">Sistema</div>
        <a href="<?= BASE_URL ?>settings" class="menu-item" title="Definições">
            <i class="fas fa-cog"></i>
            <span>Definições</span>
        </a>
        <a href="<?= BASE_URL ?>logout" class="menu-item" style="color: #ff6b6b;" title="Sair">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sair</span>
        </a>
    </div>
</div>
<!-- Styles and Scripts for Navigation are recommended to be in the main layout head/footer, 
     but keeping here for compatibility if included standalone -->
<link rel="stylesheet" href="<?= $path_prefix ?>assets/css/dashboard-layout.css?v=<?= time() ?>">
<!-- We will create a new JS file for the layout interaction -->
<script src="<?= $path_prefix ?>assets/js/dashboard-layout.js?v=<?= time() ?>"></script>
<link rel="stylesheet" href="<?= $path_prefix ?>assets/css/dashboard-tooltip.css?v=<?= time() ?>">