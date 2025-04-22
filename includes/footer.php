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
        <button id="dark-mode-toggle"></button>
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
    <script src="./js/darkMode.js"></script>
</footer>
