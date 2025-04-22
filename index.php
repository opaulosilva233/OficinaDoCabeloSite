<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficina do Cabelo</title>
    <link rel="stylesheet" href="./css/index.css">
    <script src="./js/slider.js"></script>
</head>
<body>
<?php include('./includes/header.php'); ?>
<main>
    <!-- Slider -->
    <div class="slider">
        <div class="slides">
            <!-- Slide 1 -->
            <div class="slide">
                <img src="./img/slide1.png" alt="Imagem 1">
                <div class="text">
                    <h2>Bem-vindo à Oficina do Cabelo</h2>
                    <div class="logo-slider">
                        <img src="./img/logotipo.png" alt="Logo" class="logo">
                    </div>
                    <p>Os melhores cortes que pode imaginar!</p>
                    <a href="./marcacoes.php" class="button">Agende a Sua Marcação</a>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="slide">
                <img src="./img/slide2.png" alt="Imagem 2">
                <div class="text">
                    <h2>Experiência Única</h2>
                    <div class="logo-slider">
                        <img src="./img/logotipo.png" alt="Logo" class="logo">
                    </div>
                    <p>Profissionais qualificados para transformar o seu visual.</p>
                    <a href="./marcacoes.php" class="button">Agende a Sua Marcação</a>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="slide">
                <img src="./img/slide3.png" alt="Imagem 3">
                <div class="text">
                    <h2>Estilo Personalizado</h2>
                    <div class="logo-slider">
                        <img src="./img/logotipo.png" alt="Logo" class="logo">
                    </div>
                    <p>Cada corte é feito à medida para si.</p>
                    <a href="./marcacoes.php" class="button">Agende a Sua Marcação</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Sobre Nós -->
    <section class="about-us">
        <h2>Sobre Nós</h2>
        <p>A <strong>Oficina do Cabelo</strong> é mais do que uma barbearia: é um espaço onde estilo, tradição e modernidade se encontram. Com anos de experiência e uma equipa de barbeiros apaixonados pelo que fazem, estamos prontos para transformar o seu visual e proporcionar uma experiência única.</p>
        <a href="./sobre.php" class="button">Conheça Mais</a>
    </section>
    <!-- Serviços -->
    <section class="services">
        <h2>Os Nossos Serviços</h2>
        <div class="service-list">
            <div class="service-item">
                <img src="./img/corte-cabelo.png" alt="Corte de Cabelo">
                <h3>Corte de Cabelo</h3>
                <p>Estilo personalizado para cada cliente.</p>
            </div>
            <div class="service-item">
                <img src="./img/barba.png" alt="Barba">
                <h3>Tratamento de Barba</h3>
                <p>Modelagem e cuidados especializados.</p>
            </div>
            <div class="service-item">
                <img src="./img/estilo.png" alt="Estilo">
                <h3>Estilo Personalizado</h3>
                <p>Criamos looks únicos para si.</p>
            </div>
        </div>
    </section>
    <!-- Contato -->
    <section class="contact">
        <h2>Fale Connosco</h2>
        <p>Entre em contacto para agendar a sua visita ou tirar dúvidas:</p>
        <ul>
            <li><strong>Morada:</strong> R. Eng. Armando de Magalhães 257, 4445-416 Ermesinde</li>
            <li><strong>Telefone:</strong> +351 912 345 678</li>
            <li><strong>Email:</strong> oficinadocabelo@gmail.com</li>
        </ul>
        <a href="./contacto.php" class="button">Mais Informações</a>
    </section>
</main>
<?php include('./includes/footer.php'); ?>
</body>
</html>