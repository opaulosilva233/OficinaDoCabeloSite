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
        <a href="index.php?route=home">
            <img src="assets/img/logotipo.png" alt="Oficina do Cabelo">
            <span>Oficina do Cabelo</span>
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="index.php?route=home">Início</a></li>
            <li><a href="index.php?route=sobre">Sobre</a></li>
            <li><a href="index.php?route=contacto">Contactos</a></li>
            <li><a href="index.php?route=marcacoes" class="btn-marcacao">Marcações</a></li>
            <li><a href="index.php?route=login" class="btn-login"><i class="fas fa-user"></i> Área Reservada</a></li>
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