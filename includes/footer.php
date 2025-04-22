<?php
/**
 * Este ficheiro contém o código HTML para o rodapé (footer) da página web.
 * O rodapé inclui seções de links rápidos, redes sociais, login e informações de direitos autorais.
 */
?>
<!DOCTYPE html>
<html lang="pt-PT"> <!-- Indica que a página está em português de Portugal -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/footer.css"> <!-- Liga o ficheiro CSS do rodapé -->
    </head>
<footer>
        <div class="darkmode-container footer">
            <input type="checkbox" id="darkmode-toggle">
            <label for="darkmode-toggle">
                <svg id="sun" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Free fontawesome.com--><path d="M361.5 1.2c5.6 2.1 10.4 5.9 13 10.7L375 27c4.2 7.2 1.9 16.5-5.3 21.7s-16.8 2.3-21.7-5.3l-16-27.6c-4.6-8-13.8-11.5-21.7-8.5s-12.7 9.5-11.6 17.7l.3 14.1c.9 8.3 7.7 14.6 16 14.6H349c8.3 0 15.1-6.3 16-14.6l.3-14.1c1.1-8.1-2.4-16.2-8.5-21.7l-27.6-16c-8-4.6-11.5-13.8-8.5-21.7s9.5-12.7 17.7-11.6l14.1.3c8.3.9 14.6 7.7 14.6 16v.4c0 8.3-6.3 15.1-14.6 16l-14.1.3c-8.1 1.1-16.2-2.4-21.7-8.5l-16-27.6c-4.6-8-13.8-11.5-21.7-8.5s-12.7 9.5-11.6 17.7l.3 14.1c.9 8.3 7.7 14.6 16 14.6H250c8.3 0 15.1-6.3 16-14.6l.3-14.1c1.1-8.1-2.4-16.2-8.5-21.7l-27.6-16c-8-4.6-11.5-13.8-8.5-21.7s9.5-12.7 17.7-11.6l14.1.3c8.3.9 14.6 7.7 14.6 16v.4c0 8.3-6.3 15.1-14.6 16l-14.1.3c-8.1 1.1-16.2-2.4-21.7-8.5l-16-27.6c-4.6-8-13.8-11.5-21.7-8.5s-12.7 9.5-11.6 17.7l.3 14.1c.9 8.3 7.7 14.6 16 14.6H131c8.3 0 15.1-6.3 16-14.6l.3-14.1c1.1-8.1-2.4-16.2-8.5-21.7l-27.6-16c-8-4.6-11.5-13.8-8.5-21.7s9.5-12.7 17.7-11.6l14.1.3c8.3.9 14.6 7.7 14.6 16v.4c0 8.3-6.3 15.1-14.6 16l-14.1.3c-8.1 1.1-16.2-2.4-21.7-8.5l-16-27.6c-4.6-8-13.8-11.5-21.7-8.5s-12.7 9.5-11.6 17.7l.3 14.1c.9 8.3 7.7 14.6 16 14.6H19c8.3 0 15.1-6.3 16-14.6l.3-14.1c1.1-8.1-2.4-16.2-8.5-21.7L6.7 45.3c-8-4.6-11.5-13.8-8.5-21.7s9.5-12.7 17.7-11.6l14.1.3c8.3.9 14.6 7.7 14.6 16v.4c0 8.3-6.3 15.1-14.6 16l-14.1.3c-8.1 1.1-16.2-2.4-21.7-8.5L6.7 11.3c-4.6-8-13.8-11.5-21.7-8.5s-12.7 9.5-11.6 17.7l.3 14.1c.9 8.3 7.7 14.6 16 14.6h14.6c8.3 0 15.1-6.3 16-14.6l.3-14.1c1.1-8.1-2.4-16.2-8.5-21.7l-27.6-16c-8-4.6-11.5-13.8-8.5-21.7s9.5-12.7 17.7-11.6l14.1.3c8.3.9 14.6 7.7 14.6 16z"/></svg>
                <svg id="moon" xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Free fontawesome.com--><path d="M223.5 32C100 32 0 132.3 0 256s100 224 223.5 224c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-63.4-5-126.2-2.8-178.2 6.3c-13.1 2.2-24.5 11.7-30.7 24.7s-5.1 26.4-.1 39.9c3.2 8.3 6.1 16.8 8.7 25.4c12.4 42.1 51.3 92.7 105.1 101.6c6.6 1.1 13.8-3.3 14.9-9.9s-3.3-13.8-9.9-14.9c-47.5-8.1-85-59.8-85-111.6c0-27.5 7.1-53.1 17.1-76.6c12.3-28.4 33.8-50 64.1-63.2c20.5-8.9 43.1-13.1 66.9-13.1z"/></svg>
            </label>
        </div>
    <div class="footer-container">
        <!-- Início da Secção de Links Rápidos -->
        <div class="footer-section links">
            <h4>Links Rápidos</h4>
            <ul>
                <li><a href="./index.php">Início</a></li> <!-- Caminho atualizado -->
                <li><a href="./sobre.php">Sobre Nós</a></li> <!-- Caminho atualizado -->
                <li><a href="./contacto.php">Contactos</a></li> <!-- Caminho atualizado -->
                <li><a href="./marcacoes.php">Marcações</a></li> <!-- Caminho atualizado -->
                <li><a href="./rgpd.php">Política de Privacidade</a></li> <!-- Caminho atualizado e correção de 'rgdp' para 'rgpd' -->
            </ul>
        </div>
        <!-- Fim da Secção de Links Rápidos -->
        <!-- Secção de Redes Sociais -->
        <div class="footer-section social-media">
            <h4>Siga-nos</h4>
            <ul>
                <li><a href="https://www.instagram.com/boficinadocabelo/" target="_blank">Instagram</a></li>
                <li><a href="https://www.facebook.com/BOficinadoCabelo/" target="_blank">Facebook</a></li>
            </ul>
        </div>
        <!-- Fim da Secção de Redes Sociais -->
        <!-- Secção de Login -->
        <div class="footer-section login">
            <h4>Login</h4>
            <ul>
                <li><a href="./login.php">Área dos Barbeiros</a></li> <!-- Caminho atualizado -->
            </ul>
        </div><!-- Fim da Secção de Login -->

        <!-- Rodapé Inferior com Informação de Direitos Reservados -->
        <div class="footer-bottom">
            <p>&copy; 2024 Oficina do Cabelo. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>
