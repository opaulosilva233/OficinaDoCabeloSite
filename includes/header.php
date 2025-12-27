<?php
$path_prefix = './';
?>
<!-- Page Transition Overlay -->
<div class="page-transition-overlay">
    <div class="transition-panel"></div>
    <div class="transition-panel"></div>
    <div class="transition-panel"></div>
</div>

<header>
    <div class="logo">
        <a href="<?= BASE_URL ?>home">
            <img src="assets/img/logotipo.png" alt="Oficina do Cabelo">
            <span>Oficina do Cabelo</span>
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="<?= BASE_URL ?>home">Início</a></li>
            <li><a href="<?= BASE_URL ?>sobre">Sobre Nós</a></li>
            <li><a href="<?= BASE_URL ?>contacto">Contactos</a></li>
            <li><a href="<?= BASE_URL ?>marcacoes" class="btn-marcacao">Marcações</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?= BASE_URL ?>dashboard" class="btn-login"><i class="fas fa-user"></i> Área Reservada</a></li>
                <li><a href="<?= BASE_URL ?>logout">Logout</a></li>
            <?php else: ?>
                <li><a href="<?= BASE_URL ?>login" class="btn-login"><i class="fas fa-user"></i> Área Reservada</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="dark-mode-toggle">
        <input type="checkbox" id="darkModeSwitch">
        <label for="darkModeSwitch">
            <i class="fas fa-moon"></i>
            <i class="fas fa-sun"></i>
        </label>
    </div>
</header>
<link rel="stylesheet" href="assets/css/header.css">
<link rel="stylesheet" href="assets/css/darkMode.css">
<link rel="stylesheet" href="assets/css/transitions.css">
<script src="assets/js/darkMode.js"></script>