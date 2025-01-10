<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcações</title>
    <link rel="stylesheet" href="/css/marcacoes.css">
    <link rel="icon" href="img/logotipo2.png" type="image/x-icon">
    <script src="/js/marcacao.js" defer></script>
</head>
<body>
    <header>
        <?php include('includes/header.php'); ?>
    </header>
    <main>
        <section class="appointment-section">
            <h1>Agendar uma Marcação</h1>
            <p>Escolha o tipo de corte desejado e preencha suas informações para confirmar a sua marcação.</p>

            <!-- Etapa 1: Seleção do Tipo de Corte -->
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

            <div id="modal-backdrop" class="hidden"></div>

            <!-- Modal -->
            <div class="modal hidden" id="modal">
                <div class="modal-content">
                    <span class="close" id="modal-close">&times;</span>
                    <p id="selected-service" class="selected-service">Corte Selecionado: Nenhum</p>

                    <h3>Selecione o Barbeiro</h3>
                    <div class="barbers">
                        <div class="barber" data-barber="Bruno Martins">
                            <img src="img/BBarber.png" alt="Bruno Martins">
                            <p>Bruno Martins</p>
                        </div>
                        <div class="barber" data-barber="Hugo Alves">
                            <img src="img/HBarber.png" alt="Hugo Alves">
                            <p>Hugo Alves</p>
                        </div>
                    </div>

                    <h3>Selecione a Data</h3>
                    <input type="date" id="date" name="date" required>

                    <h3>Selecione o Horário</h3>
                    <select id="time" name="time" required></select>

                    <h3>Preencha suas Informações</h3>
                    <!-- Formulário de Marcação -->
                    <form id="appointment-form" action="includes/saveBooking.php" method="POST">
                        <!-- Campos ocultos que serão preenchidos via JavaScript -->
                        <input type="hidden" name="service" id="service-selected">
                        <input type="hidden" name="barber" id="barber-selected">
                        <input type="hidden" name="date" id="date-selected">
                        <input type="hidden" name="time" id="time-selected">

                        <label for="name">Nome</label>
                        <input type="text" id="name" name="name" placeholder="Seu nome" required>

                        <label for="phone">Telemóvel</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            placeholder="Seu número de telemóvel" 
                            required 
                            pattern="[0-9]{9}" 
                            maxlength="9"
                            title="Insira um número de telefone válido com 9 dígitos"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Seu email" required 
                            pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                            title="Insira um endereço de email válido">

                        <button type="submit" id="confirm-selection" class="btn-confirm">Confirmar Marcação</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <?php include('includes/footer.php'); ?>
    </footer>
</body>
</html>
