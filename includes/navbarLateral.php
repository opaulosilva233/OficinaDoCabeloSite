<?php
// This file is now included from index.php (root)
// So paths should be relative to root
$path_prefix = './'; 
?>
<div class="sidebar">
    <div class="logo-details">
        <img src="<?= $path_prefix ?>assets/img/logotipo2.png" alt="Logo" class="logo_img">
        <div class="logo_name">Oficina do Cabelo</div>
        <i class="fas fa-bars" id="btn"></i>
    </div>
    <ul class="nav-list">
        <li>
            <a href="<?= $path_prefix ?>index.php?route=dashboard">
                <i class="fas fa-th-large"></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
        <li>
            <a href="<?= $path_prefix ?>index.php?route=agenda&barber=Bruno%20Martins">
                <i class="fas fa-user"></i>
                <span class="links_name">Bruno Martins</span>
            </a>
            <span class="tooltip">Bruno Martins</span>
        </li>
        <li>
            <a href="<?= $path_prefix ?>index.php?route=agenda&barber=Hugo%20Alves">
                <i class="fas fa-user"></i>
                <span class="links_name">Hugo Alves</span>
            </a>
            <span class="tooltip">Hugo Alves</span>
        </li>
        <li>
            <a href="<?= $path_prefix ?>index.php?route=all">
                <i class="fas fa-calendar-alt"></i>
                <span class="links_name">Todas as Marcações</span>
            </a>
            <span class="tooltip">Todas as Marcações</span>
        </li>
        <li class="profile">
            <div class="profile-details">
                <img src="<?= $path_prefix ?>assets/img/logotipo.png" alt="profileImg">
                <div class="name_job">
                    <div class="name">Oficina do Cabelo</div>
                    <div class="job">Barbearia</div>
                </div>
            </div>
            <a href="<?= $path_prefix ?>index.php?route=logout" id="log_out">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</div>
<!-- Importar o CSS e JS da Navbar -->
<link rel="stylesheet" href="<?= $path_prefix ?>assets/css/navbarLateral.css">
<script src="<?= $path_prefix ?>assets/js/navLatMenu.js"></script>