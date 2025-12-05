<?php
$path_prefix = './';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficina do Cabelo - Barbearia Clássica</title>
    <link rel="stylesheet" href="assets/css/index.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Slider -->
    <div class="slider">
        <!-- Slide 1 -->
        <div class="slide active">
            <img src="assets/img/slide1.png" alt="Corte de Cabelo Clássico">
            <div class="text active">
                <div class="logo-slider">
                    <img src="assets/img/logotipo.png" alt="Logo Oficina do Cabelo" class="logo">
                </div>
                <h2>Estilo & Tradição</h2>
                <p>Cuidamos do seu visual com a excelência que você merece.</p>
                <a href="index.php?route=marcacoes" class="button"><i class="fas fa-calendar-check"></i> Agendar Agora</a>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="slide">
            <img src="assets/img/slide2.png" alt="Barba e Bigode">
            <div class="text">
                <div class="logo-slider">
                    <img src="assets/img/logotipo.png" alt="Logo Oficina do Cabelo" class="logo">
                </div>
                <h2>Barba Impecável</h2>
                <p>Tratamentos completos para uma barba saudável e alinhada.</p>
                <a href="index.php?route=marcacoes" class="button"><i class="fas fa-calendar-check"></i> Agendar Agora</a>
            </div>
        </div>
        <!-- Slide 3 -->
        <div class="slide">
            <img src="assets/img/slide3.png" alt="Ambiente Confortável">
            <div class="text">
                <div class="logo-slider">
                    <img src="assets/img/logotipo.png" alt="Logo Oficina do Cabelo" class="logo">
                </div>
                <h2>Ambiente Premium</h2>
                <p>Relaxe enquanto cuidamos de si no nosso espaço exclusivo.</p>
                <a href="index.php?route=marcacoes" class="button"><i class="fas fa-calendar-check"></i> Agendar Agora</a>
            </div>
        </div>
    </div>

    <main>
        <!-- Sobre Nós -->
        <section class="about-us">
            <div class="about-content" style="width: 100%; text-align: center;">
                <h2><i class="fas fa-info-circle"></i> Sobre Nós</h2>
                <p>A <strong>Oficina do Cabelo</strong> não é apenas uma barbearia, é um espaço de encontro onde o estilo clássico se funde com as tendências modernas.</p>
                <p>Fundada por profissionais apaixonados, a nossa missão é proporcionar uma experiência única, garantindo que cada cliente saia não só com um visual renovado, mas também com a confiança elevada.</p>
                <a href="index.php?route=sobre" class="button"><i class="fas fa-arrow-right"></i> Saiba Mais</a>
            </div>
        </section>

        <!-- Nossos Serviços -->
        <section class="services">
            <h2><i class="fas fa-cut"></i> Nossos Serviços</h2>
            <p>Oferecemos uma gama completa de serviços para o homem moderno.</p>
            <div class="service-list">
                <div class="service-item">
                    <img src="assets/img/corte-cabelo.png" alt="Corte de Cabelo">
                    <h3>Corte de Cabelo</h3>
                    <p>Tesoura ou máquina, ao seu estilo.</p>
                </div>
                <div class="service-item">
                    <img src="assets/img/barba.png" alt="Barba">
                    <h3>Barba</h3>
                    <p>Modelagem e hidratação com toalha quente.</p>
                </div>
                <div class="service-item">
                    <img src="assets/img/estilo.png" alt="Acabamentos">
                    <h3>Acabamentos</h3>
                    <p>Sobrancelha e contornos perfeitos.</p>
                </div>
            </div>
            <br>
            <a href="index.php?route=marcacoes" class="button"><i class="fas fa-list"></i> Ver Preçário Completo</a>
        </section>

        <!-- Fale Connosco -->
        <section class="contact">
            <h2><i class="fas fa-comments"></i> Fale Connosco</h2>
            <p>Tem dúvidas ou quer agendar por telefone? Estamos à disposição.</p>
            <div class="contact-list">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Localização</h3>
                    <p>Rua Exemplo, 123, Ermesinde</p>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <h3>Telefone</h3>
                    <p>+351 912 345 678</p>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <h3>Email</h3>
                    <p>geral@oficinadocabelo.pt</p>
                </div>
            </div>
            <a href="index.php?route=contacto" class="button"><i class="fas fa-envelope-open-text"></i> Enviar Mensagem</a>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/slider.js"></script>
</body>
</html>
