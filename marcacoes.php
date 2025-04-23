<?php
/**
 * Este ficheiro é a página principal de marcações da barbearia.
 * Ele permite aos utilizadores selecionar um tipo de serviço, escolher um barbeiro,
 * uma data e hora para a marcação, e preencher as suas informações pessoais.
 * Se a marcação for confirmada com sucesso, mostra uma mensagem de confirmação.
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcações</title>
    <link rel="stylesheet" href="./css/marcacoes.css"> <!-- Liga o CSS da página de marcações -->
    <link rel="icon" href="./img/logotipo2.png" type="image/x-icon"> <!-- Define o favicon da página -->
    <script src="./js/marcacao.js" defer></script> <!-- Liga o JavaScript para a lógica de marcações -->
    <?php include('./includes/header.php'); ?> <!-- Inclui o cabeçalho da página -->
</head>
<body>
    <?php
        // Verifica se há um erro nos parâmetros da URL. Se houver, exibe uma mensagem de erro.
        if (isset($_GET['error'])) {
            $error = htmlspecialchars($_GET['error']);
            echo "<div class='error-message'>Erro: $error</div>";
        }
    ?>
    <?php
    // Verifica se os parâmetros da URL (date, time, barber, name) estão presentes.
    // Se estiverem, significa que a marcação foi confirmada e deve ser exibida uma mensagem de confirmação.
    if (isset($_GET['date']) && isset($_GET['time']) && isset($_GET['barber']) && isset($_GET['name']) && isset($_GET['service'])) {
        $date = htmlspecialchars($_GET['date']);
        $time = htmlspecialchars($_GET['time']);
        $barber = htmlspecialchars($_GET['barber']);
        $name = htmlspecialchars($_GET['name']);
        $service = htmlspecialchars($_GET['service']);
        ?>

        <main>
        <section class="appointment-section">
            <div class="confirmation-message">
                <h2>Marcação Confirmada</h2> <!-- Título da mensagem de confirmação -->
                <p>Olá, <?php echo $name; ?>!</p> <!-- Mensagem de boas-vindas com o nome do utilizador -->
                <p>A sua marcação foi confirmada com sucesso.</p> <!-- Mensagem de confirmação -->
                <p><strong>Data:</strong> <?php echo $date; ?></p> <!-- Exibe a data da marcação -->
                <p><strong>Hora:</strong> <?php echo $time; ?></p> <!-- Exibe a hora da marcação -->
                <p><strong>Barbeiro:</strong> <?php echo $barber; ?></p> <!-- Exibe o nome do barbeiro selecionado -->
                <p><strong>Serviço:</strong> <?php echo $service; ?></p> <!-- Exibe o serviço selecionado -->
                <p>Iremos enviar um email com os detalhes da marcação.</p> <!-- Informa que um email será enviado -->
            </div>
        </section>
        <!-- Inclui o conteúdo do main caso a reserva esteja completa. -->
        </main>
            <footer>
                <?php include('./includes/footer.php'); ?>
            </footer>
        </body>
        </html><?php exit; // Stop execution to avoid showing the booking form
    } ?>
    </header>
    <!-- Caso não exista um reserva, irá aparecer esta página para fazer a reserva. -->
    <main>
        <section class="appointment-section">
            <h1>Agendar uma Marcação</h1> <!-- Título principal da página de marcações -->
            <p>Escolha o tipo de corte desejado e preencha as suas informações para confirmar a sua marcação.</p> <!-- Instruções para o utilizador -->
            <!-- Início da Etapa 1: Seleção do Tipo de Corte -->
            <div class="step step-1">
                <h2>Selecione o Tipo de Corte</h2>
                <div class="categories">
                    <div class="category">
                        <h3 class="category-title">Corte de Cabelo Normal / Degradê</h3>
                        <ul>
                            <li>
                                <button class="option-btn" data-option="Corte de Cabelo Normal / Degradê - 13€">
                                    Corte de Cabelo Normal / Degradê - 13€
                                </button>
                            </li>
                            <li>
                                <button class="option-btn" data-option="Corte de Cabelo + Barba - 18€">
                                    Corte de Cabelo + Barba - 18€
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="category">
                        <h3 class="category-title">Corte de Cabelo de Máquina / Pente Único (Rapado)</h3>
                        <ul>
                            <li>
                                <button class="option-btn" data-option="Corte de Cabelo Pente Único (Rapado) - 6€">
                                    Corte de Cabelo Pente Único (Rapado) - 6€
                                </button>
                            </li>
                            <li>
                                <button class="option-btn" data-option="Corte de Cabelo Pente Único (Rapado) + Barba - 11€">
                                    Corte de Cabelo Pente Único (Rapado) + Barba - 11€
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="category">
                        <h3 class="category-title">Barba</h3>
                        <ul>
                            <li>
                                <button class="option-btn" data-option="Barba - Tamanho + Contornos - 6€">
                                    Barba - Tamanho + Contornos - 6€
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="category">
                        <h3 class="category-title">Corte de Criança</h3>
                        <ul>
                            <li>
                                <button class="option-btn" data-option="Corte Criança - Corte Normal / Degradê (até 12 anos) - 13€">
                                    Corte Criança - Corte Normal / Degradê (até 12 anos) - 13€
                                </button>
                            </li>
                            <li>
                                <button class="option-btn" data-option="Corte Criança Pente Único (até 12 anos) - 6€">
                                    Corte Criança Pente Único (até 12 anos) - 6€
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Fim da Etapa 1: Seleção do Tipo de Corte -->
            <div id="modal-backdrop" class="hidden"></div> <!-- Fundo semitransparente para o modal -->
            <!-- Início do Modal -->
            <div class="modal hidden" id="modal">
                <div class="modal-content">
                    <span class="close" id="modal-close">&times;</span> <!-- Botão para fechar o modal -->
                    <p id="selected-service" class="selected-service">Corte Selecionado: Nenhum</p>
                    <h3>Selecione o Barbeiro</h3>
                    <div class="barbers">
                        <div class="barber" data-barber="Bruno Martins">
                            <img src="./img/BBarber.png" alt="Bruno Martins">
                            <p>Bruno Martins</p>
                        </div>
                        <div class="barber" data-barber="Hugo Alves">
                            <img src="./img/HBarber.png" alt="Hugo Alves">
                            <p>Hugo Alves</p>
                        </div>
                    </div>
                    <h3>Selecione a Data</h3>
                    <input type="date" id="date" name="date" required>
                    <h3>Selecione o Horário</h3>
                    <select id="time" name="time" required></select> <!-- Select para escolher o horário -->
                    <h3>Preencha as Suas Informações</h3>
                    <!-- Início do Formulário de Marcação -->
                    <form id="appointment-form" action="./includes/saveBooking.php" method="POST"> <!-- Formulário para enviar os dados da marcação -->
                        <!-- Campos ocultos que serão preenchidos via JavaScript e enviados ao servidor -->
                        <input type="hidden" name="service" id="service-selected"> <!-- Campo oculto para o serviço -->
                        <input type="hidden" name="barber" id="barber-selected">
                        <input type="hidden" name="date" id="date-selected">
                        <input type="hidden" name="time" id="time-selected">
                        <label for="name">Nome</label>
                        <input type="text" id="name" name="name" placeholder="O seu nome" required>
                        <label for="phone">Telemóvel</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            placeholder="O seu número de telemóvel" 
                            required 
                            pattern="[0-9]{9}" 
                            maxlength="9"
                            title="Insira um número de telefone válido com 9 dígitos"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="O seu email" required 
                            pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                            title="Insira um endereço de email válido">
                        <button type="submit" id="confirm-selection" class="btn-confirm">Confirmar Marcação</button>
                    </form> <!-- Fim do Formulário de Marcação -->
                </div>
            </div> <!-- Fim do Modal -->
        </section>
    </main> <!-- Fim do conteúdo principal -->
    <!-- Inclui o footer na página -->
    <footer>
        <?php include('./includes/footer.php'); ?>
    </footer>
</body>
</html>