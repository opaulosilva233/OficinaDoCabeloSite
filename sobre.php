<?php include('includes/header.php'); ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <link rel="stylesheet" href="/css/sobre.css">
</head>

<main>
    <!-- Seção de resumo da barbearia -->
    <section class="about">
        <div class="text">
            <div class="section-title">
                <h1>Sobre Nós</h1>
            </div>
            <div class="section-content">
                <p>Bem-vindo à nossa barbearia! Há anos, temos o orgulho de oferecer cortes de cabelo e cuidados com a barba de alta qualidade. Nossa missão é criar um ambiente acolhedor, onde você possa relaxar e sair se sentindo incrível.</p>
            </div>
        </div>
        <div class="image">
            <img src="/img/Space.png" alt="Imagem da Barbearia">
        </div>
    </section>

    <!-- Seção dos barbeiros -->
    <section class="barbers">
        <div class="section-title">
            <h2>Conheça Nossos Barbeiros</h2>
        </div>
        <div class="barbers-grid">
            <!-- Barbeiro 1 -->
            <div class="barber">
                <a href="#" class="barber-link" data-barber="barber1">
                    <img src="/img/BBarber.png" alt="Barbeiro 1">
                </a>
                <h3>Bruno Martins</h3>
            </div>

            <!-- Barbeiro 2 -->
            <div class="barber">
                <a href="#" class="barber-link" data-barber="barber2">
                    <img src="/img/HBarber.png" alt="Barbeiro 2">
                </a>
                <h3>Hugo Alves</h3>
            </div>
        </div>
    </section>

    <!-- Modal de sobreposição -->
    <div id="barber-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-text">
                <!-- Conteúdo do resumo será inserido aqui dinamicamente -->
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>

<script src="/js/sobre-nos.js"></script>
</html>
