<?php $path_prefix = './'; ?>
<?php include('includes/header.php'); ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <link rel="stylesheet" href="assets/css/sobre.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<main>
    <section class="about">
        <div class="text">
            <div class="section-title">
                <h1><i class="fas fa-info-circle"></i> Sobre Nós</h1>
            </div>
            <div class="section-content">
                <p>A Oficina do Cabelo nasceu em 2019 em Ermesinde, fundada por dois amigos apaixonados pela arte de barbear: Bruno Martins e Hugo Alves. Inspirados pela tradição das barbearias clássicas e pela energia das tendências modernas, eles decidiram criar um espaço onde os clientes pudessem relaxar, cuidar do visual e se sentir em casa. Hoje, após 6 anos, já atendemos mais de 5.000 clientes, sempre com o mesmo cuidado e dedicação.</p>
                <p>Nossa missão é simples: proporcionar um momento de cuidado e estilo com atendimento personalizado, onde cada cliente se sinta único. Valorizamos a confiança que você deposita em nós e trabalhamos para transformar cada visita em uma experiência memorável.</p>
                <p><em>"A Oficina do Cabelo é o meu lugar favorito para relaxar e sair com um corte impecável. O atendimento é sempre incrível!"</em> – João Silva, cliente desde 2020.</p>
            </div>
        </div>
        <div class="image">
            <img src="assets/img/Space.png" alt="Imagem da Barbearia">
            <p class="image-caption">Nosso espaço acolhedor em Ermesinde</p>
        </div>
    </section>
    <section class="barbers">
        <div class="section-title">
            <h2><i class="fas fa-cut"></i> Conheça os Nossos Barbeiros</h2>
        </div>
        <div class="barbers-grid">
            <div class="barber">
                <a href="#" class="barber-link" data-barber="barber1">
                    <img src="assets/img/BBarber.png" alt="Barbeiro Bruno Martins em ação na Oficina do Cabelo">
                </a>
                <div class="barber-name">
                    <h3>Bruno Martins</h3>
                </div>
            </div>
            <div class="barber">
                <a href="#" class="barber-link" data-barber="barber2">
                    <img src="assets/img/HBarber.png" alt="Barbeiro Hugo Alves em ação na Oficina do Cabelo">
                </a>
                <div class="barber-name">
                    <h3>Hugo Alves</h3>
                </div>
            </div>
        </div>
    </section>
    <div id="barber-modal" class="modal">
        <div class="modal-content">
            <span class="close">×</span>
            <div class="modal-text">
                <h3 class="modal-title"></h3>
                <div class="modal-image-container">
                    <img class="modal-image" src="" alt="Imagem do Barbeiro">
                    <button class="instagram-button"><i class="fab fa-instagram"></i> Instagram</button>
                </div>
                <p class="modal-description"></p>
                <a href="index.php?route=marcacoes" class="modal-button"><i class="fas fa-calendar-alt"></i> Agendar com Este Barbeiro</a>
            </div>
        </div>
    </div>
</main>
<?php include('includes/footer.php'); ?>
<script src="assets/js/sobre-nos.js"></script>
</body>
</html>