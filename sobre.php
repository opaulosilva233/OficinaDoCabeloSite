<?php include('./includes/header.php'); ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <link rel="stylesheet" href="./css/sobre.css">
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
                <p>A Oficina do Cabelo, localizada em Ermesinde, é mais do que uma barbearia: é um espaço onde estilo e tradição se encontram. Desde 2019, oferecemos cortes personalizados, cuidados com a barba e um ambiente acolhedor para os nossos clientes. Venha visitar-nos e experimente o verdadeiro espírito da barbearia moderna!</p>
            </div>
        </div>
        <div class="image">
            <img src="./img/Space.png" alt="Imagem da Barbearia">
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
                    <img src="./img/BBarber.png" alt="Barbeiro Bruno Martins em ação na Oficina do Cabelo">
                </a>
                <div class="barber-name">
                    <h3>Bruno Martins</h3>
                </div>
            </div>
            <div class="barber">
                <a href="#" class="barber-link" data-barber="barber2">
                    <img src="./img/HBarber.png" alt="Barbeiro Hugo Alves em ação na Oficina do Cabelo">
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
                <a href="./marcacoes.php" class="modal-button"><i class="fas fa-calendar-alt"></i> Agendar com Este Barbeiro</a>
            </div>
        </div>
    </div>
</main>
<?php include('./includes/footer.php'); ?>
<script src="./js/sobre-nos.js"></script>
</body>
</html>