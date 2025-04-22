<?php
// Include config file
include('config.php');
// Start the session
session_start();
?><!-- Início do cabeçalho HTML -->
<!DOCTYPE html>
<html lang="pt-PT"> <!-- Especifica que o idioma da página é português de Portugal -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css"> <!-- Liga o ficheiro CSS do cabeçalho -->
    <link rel="stylesheet" href="./css/darkMode.css">
    <link rel="icon" href="./img/logotipo.png" type="image/x-icon"> <!-- Define o ícone da página (favicon) -->
    <title>Oficina do Cabelo</title>
    <script src="./js/darkMode.js"></script>

</head>
<body>
    <!-- Início do cabeçalho -->
    <header>
        <!-- Secção da logo -->
        <div class="logo">
            <a href="./index.php" class="logo"> <!-- Link para a página inicial -->
                <img src="./img/logotipo.png" alt="Logótipo da Barbearia"> <!-- Exibe a logo da barbearia -->
                <span>Oficina do Cabelo</span>
            </a>
        </div>
        <!-- Secção da navegação -->
        <nav>
            <!-- Ícone Hamburger (para dispositivos móveis) -->
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <!-- Menu principal -->
            <ul id="menu" class="menu">
                <li><a href="./index.php">Início</a></li> <!-- Caminho atualizado com './' -->
                <li><a href="./sobre.php">Sobre Nós</a></li> <!-- Caminho atualizado com './' -->
                <li><a href="./contacto.php">Contactos</a></li> <!-- Caminho atualizado com './' -->
                <li><a href="./marcacoes.php">Marcações</a></li> <!-- Caminho atualizado com './' -->
                <li>
                    <div class="dark-mode-toggle"><button id="dark-mode-toggle"></button></div>
                </li>
            </ul>
        </nav>
    </header>
    <!-- Inclusão do script JavaScript para o menu de navegação -->
    <script src="./js/navMenu.js"></script> 
</body>