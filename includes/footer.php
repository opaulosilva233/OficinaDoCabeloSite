<?php
/**
 * Este ficheiro contém o código HTML para o rodapé (footer) da página web.
 * O rodapé inclui seções de links rápidos, redes sociais, login, horário de funcionamento e direitos autorais.
 */
?>
<!DOCTYPE html>
<html lang="pt-PT"> <!-- Indica que a página está em português de Portugal -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/footer.css"> <!-- Liga o ficheiro CSS do rodapé -->
    <!-- Incluindo Font Awesome para os ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<footer id="site-footer">
    <div class="footer-container">
        <!-- Início da Secção de Links Rápidos -->
        <div class="footer-section links">
            <h4>Links Rápidos</h4>
            <ul>
                <li><a href="./index.php">Início</a></li> <!-- Caminho atualizado -->
                <li><a href="./sobre.php">Sobre Nós</a></li> <!-- Caminho atualizado -->
                <li><a href="./contacto.php">Contactos</a></li> <!-- Caminho atualizado -->
                <li><a href="./marcacoes.php">Marcações</a></li> <!-- Caminho atualizado -->
                <li><a href="./rgpd.php">Política de Privacidade</a></li> <!-- Caminho atualizado -->
            </ul>
        </div>

        <!-- Horário de Funcionamento -->
        <div class="footer-section opening-hours">
            <h4>Horário de Funcionamento</h4>
            <ul class="opening-hours-list">
                <li>Segunda a Sexta: 9h às 19h</li>
                <li>Sábado: 9h às 13h</li>
                <li>Domingo: Encerrado</li>
            </ul>
        </div>

        <!-- Secção de Redes Sociais -->
        <div class="footer-section social-media">
            <h4>Siga-nos</h4>
            <ul class="social-links">
                <li>
                    <a href="https://www.instagram.com/boficinadocabelo/" target="_blank" class="social-icon">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/BOficinadoCabelo/" target="_blank" class="social-icon">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                </li>
            </ul>
        </div>

        <!-- Secção de Login -->
        <div class="footer-section login">
            <h4>Login</h4>
            <ul>
                <li><a href="./login.php">Área dos Barbeiros</a></li> <!-- Caminho atualizado -->
            </ul>
        </div>

        <!-- Rodapé Inferior com Informação de Direitos Reservados -->
        <div class="footer-bottom">
            <p>© <?php echo date("Y"); ?> Oficina do Cabelo. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>