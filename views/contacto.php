<?php $path_prefix = './'; ?>
<?php include('includes/header.php'); ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos | Oficina do Cabelo</title>
    <link rel="stylesheet" href="assets/css/contacto.css?v=<?= time() + 2 ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

    <div class="page-header">
        <h1>Fale Connosco</h1>
        <p>Estamos prontos para o receber. Visite-nos ou entre em contacto.</p>
    </div>

    <main class="container">
        
        <section class="contact-grid">
            <!-- Info Cards Section -->
            <div class="info-section">
                <h2><i class="fas fa-building"></i> Informações de Contacto</h2>
                <div class="cards-wrapper">
                    
                    <!-- Location Card -->
                    <div class="contact-card">
                        <div class="icon-box">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="card-content">
                            <h3>Localização</h3>
                            <p>R. Eng. Armando de Magalhães 257,<br>4445-416 Ermesinde</p>
                            <a href="https://maps.google.com/?q=Oficina+do+Cabelo+Ermesinde" target="_blank" class="card-link">Ver no Mapa</a>
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div class="contact-card">
                        <div class="icon-box">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="card-content">
                            <h3>Telefone</h3>
                            <p>Estamos disponíveis para agendamentos.</p>
                            <a href="tel:+351912345678" class="card-link highlight">+351 912 345 678</a>
                        </div>
                    </div>

                    <!-- Email Card -->
                    <div class="contact-card">
                        <div class="icon-box">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="card-content">
                            <h3>Email</h3>
                            <p>Tem alguma dúvida? Envie-nos um email.</p>
                            <a href="mailto:oficinadocabelo@gmail.com" class="card-link">oficinadocabelo@gmail.com</a>
                        </div>
                    </div>

                    <!-- Hours Card -->
                    <div class="contact-card">
                        <div class="icon-box">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-content">
                            <h3>Horário</h3>
                            <p><strong>Seg - Sab:</strong> 09:00 - 19:00</p>
                            <p><strong>Dom:</strong> Fechado</p>
                        </div>
                    </div>

                </div>

                <!-- Social Media -->
                <div class="social-section">
                    <h3>Siga-nos nas Redes Sociais</h3>
                    <div class="social-buttons">
                        <a href="https://www.instagram.com/boficinadocabelo/" target="_blank" class="social-btn instagram">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                        <a href="https://www.facebook.com/BOficinadoCabelo/" target="_blank" class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="map-section">
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d757.8518812840372!2d-8.556412538242734!3d41.21716437047501!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd246104921b972d%3A0x9d97a6f1d60812eb!2sOficina%20do%20Cabelo!5e0!3m2!1spt-PT!2spt!4v1731927510153!5m2!1spt-PT!2spt" 
                        allowfullscreen="" 
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Localização Oficina do Cabelo">
                    </iframe>
                </div>
            </div>
        </section>

    </main>

    <?php include('includes/footer.php'); ?>
</body>
</html>