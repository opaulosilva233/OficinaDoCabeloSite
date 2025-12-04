<?php
$path_prefix = './';
?>
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h3>Links Úteis</h3>
            <ul>
                <li><a href="index.php?route=home">Início</a></li>
                <li><a href="index.php?route=sobre">Sobre</a></li>
                <li><a href="index.php?route=contacto">Contactos</a></li>
                <li><a href="index.php?route=marcacoes">Marcações</a></li>
                <li><a href="index.php?route=login">Login</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Legal</h3>
            <ul>
                <li><a href="index.php?route=rgpd">Política de Privacidade (RGPD)</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Redes Sociais</h3>
            <ul class="social-links">
                <li><a href="https://facebook.com" target="_blank" class="social-icon"><i class="fab fa-facebook"></i> Facebook</a></li>
                <li><a href="https://instagram.com" target="_blank" class="social-icon"><i class="fab fa-instagram"></i> Instagram</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Horário</h3>
            <ul class="opening-hours-list">
                <li>Seg - Sex: 09:00 - 19:00</li>
                <li>Sáb: 09:00 - 13:00</li>
                <li>Dom: Fechado</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>Oficina do Cabelo - A sua barbearia de confiança. &nbsp;|&nbsp; &copy; <?= date('Y') ?> Todos os direitos reservados.</p>
    </div>
</footer>
<link rel="stylesheet" href="assets/css/footer.css">