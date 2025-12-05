<?php $path_prefix = './'; ?>
<?php include('includes/header.php'); ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós | Oficina do Cabelo</title>
    <link rel="stylesheet" href="assets/css/sobre.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="page-header">
        <h1>Sobre Nós</h1>
        <p>Conheça a nossa história e a equipa que cuida do seu estilo.</p>
    </div>

    <main class="container">
        <!-- Section: A Nossa História -->
        <section class="about-section">
            <div class="about-container">
                <div class="about-text">
                    <h2><i class="fas fa-history"></i> A Nossa História</h2>
                    <p>A <strong>Oficina do Cabelo</strong> nasceu em 2019 em Ermesinde, fundada por dois amigos apaixonados pela arte de barbear: <strong>Bruno Martins</strong> e <strong>Hugo Alves</strong>. Inspirados pela tradição das barbearias clássicas e pela energia das tendências modernas, decidiram criar um espaço onde os clientes pudessem relaxar, cuidar do visual e sentir-se em casa.</p>
                    <p>Hoje, após 6 anos, já atendemos mais de <strong>5.000 clientes</strong>, sempre com o mesmo cuidado e dedicação.</p>
                    
                    <h3>A Nossa Missão</h3>
                    <p>A nossa missão é simples: proporcionar um momento de cuidado e estilo com atendimento personalizado, onde cada cliente se sinta único. Valorizamos a confiança que deposita em nós e trabalhamos para transformar cada visita numa experiência memorável.</p>
                    
                    <blockquote class="testimonial">
                        <p>"A Oficina do Cabelo é o meu lugar favorito para relaxar e sair com um corte impecável. O atendimento é sempre incrível!"</p>
                        <cite>– João Silva, cliente desde 2020</cite>
                    </blockquote>
                </div>
                <div class="about-image">
                    <img src="assets/img/Space.png" alt="Interior da Oficina do Cabelo em Ermesinde">
                    <div class="image-caption">
                        <i class="fas fa-map-marker-alt"></i> O nosso espaço em Ermesinde
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: A Nossa Equipa -->
        <section class="team-section">
            <div class="section-header">
                <h2><i class="fas fa-cut"></i> Conheça os Nossos Barbeiros</h2>
                <p>Profissionais dedicados à arte de bem servir.</p>
            </div>
            
            <div class="barbers-grid">
                <!-- Barbeiro 1 -->
                <div class="barber-card">
                    <div class="barber-image-wrapper">
                         <a href="#" class="barber-link" data-barber="barber1">
                            <img src="assets/img/BBarber.png" alt="Bruno Martins">
                             <div class="overlay">
                                <span>Ver Perfil</span>
                            </div>
                        </a>
                    </div>
                    <div class="barber-info">
                        <h3>Bruno Martins</h3>
                        <p class="role">Co-Founder & Master Barber</p>
                    </div>
                </div>

                <!-- Barbeiro 2 -->
                <div class="barber-card">
                    <div class="barber-image-wrapper">
                        <a href="#" class="barber-link" data-barber="barber2">
                            <img src="assets/img/HBarber.png" alt="Hugo Alves">
                            <div class="overlay">
                                <span>Ver Perfil</span>
                            </div>
                        </a>
                    </div>
                    <div class="barber-info">
                        <h3>Hugo Alves</h3>
                        <p class="role">Co-Founder & Master Barber</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal (Mantendo a estrutura para compatibilidade com JS) -->
        <div id="barber-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-body">
                    <h3 class="modal-title"></h3>
                    <div class="modal-image-container">
                        <img class="modal-image" src="" alt="Imagem do Barbeiro">
                        <button class="instagram-button"><i class="fab fa-instagram"></i> Instagram</button>
                    </div>
                    <p class="modal-description"></p>
                    <a href="index.php?route=marcacoes" class="modal-button"><i class="fas fa-calendar-alt"></i> Agendar Agora</a>
                </div>
            </div>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
    <script src="assets/js/sobre-nos.js"></script>
</body>
</html>