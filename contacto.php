<?php include('./includes/header.php'); ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos</title>
    <link rel="stylesheet" href="/css/contacto.css">
</head>
<body>
<main>
    <!-- Seção de Contactos -->
    <section class="contacts">
        <div class="contact-info">
            <h1>Contactos</h1>
            <p class="contact-invite">Entre em contato conosco e agende sua visita à Oficina do Cabelo!</p>
            <p><strong>Horário:</strong> Segunda a Sábado, das 9h às 19h</p>
            <p><strong>Email:</strong> <a href="mailto:oficinadocabelo@gmail.com" class="contact-link">oficinadocabelo@gmail.com</a></p>
            <p><strong>Telefone:</strong> <a href="tel:+351912345678" class="contact-link">+351 912 345 678</a></p>
            <p><strong>Morada:</strong> R. Eng. Armando de Magalhães 257, 4445-416 Ermesinde</p>
            <p><strong>Redes Sociais:</strong></p>
            <ul class="social-links">
                <li>
                    <a href="https://www.instagram.com/boficinadocabelo/" target="_blank" class="social-icon">
                        <img src="/img/instagram.png" alt="Instagram">
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/BOficinadoCabelo/" target="_blank" class="social-icon">
                        <img src="/img/facebook.png" alt="Facebook">
                    </a>
                </li>
            </ul>
        </div>
        <div class="map-container">
            <!-- Mapa integrado -->
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d757.8518812840372!2d-8.556412538242734!3d41.21716437047501!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd246104921b972d%3A0x9d97a6f1d60812eb!2sOficina%20do%20Cabelo!5e0!3m2!1spt-PT!2spt!4v1731927510153!5m2!1spt-PT!2spt" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </section>
</main>
<?php include('./includes/footer.php'); ?>
</body>
</html>