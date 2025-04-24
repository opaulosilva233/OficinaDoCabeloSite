<?php
/**
 * Página de marcações da barbearia com um stepper para guiar o usuário.
 * Etapas: 1. Tipo de Corte, 2. Barbeiro, 3. Data e Hora, 4. Dados Pessoais, 5. Confirmação.
 * Após a submissão bem-sucedida, exibe uma mensagem de sucesso.
 */
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Marcação - Oficina do Cabelo Barbearia Ermesinde</title>
    <meta name="description" content="Agende sua marcação na Oficina do Cabelo, a melhor barbearia em Ermesinde. Escolha seu barbeiro, data e horário de forma rápida e fácil!">
    <link rel="stylesheet" href="./css/marcacoes.css">
    <link rel="icon" href="./img/logotipo2.png" type="image/x-icon">
    <!-- Adicionando Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="./js/marcacao.js" defer></script>
    <?php include('./includes/header.php'); ?>
</head>
<body>
    <main>
    <?php
    // Função para formatar a data de YYYY-MM-DD para DD-MM-YYYY
    function formatDisplayDate($date) {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $dateObj = DateTime::createFromFormat('Y-m-d', $date);
            return $dateObj ? $dateObj->format('d-m-Y') : $date;
        }
        return $date;
    }
    ?>
        <section class="appointment-section">
            <h1>Agendar uma Marcação</h1>
            <p>Preencha as informações abaixo para agendar seu corte na Oficina do Cabelo, barbearia em Ermesinde.</p>

            <!-- Stepper -->
            <div class="stepper">
                <div class="step active" data-step="1">
                    <span class="step-number">1</span>
                    <span class="step-label">Tipo de Corte</span>
                </div>
                <div class="step" data-step="2">
                    <span class="step-number">2</span>
                    <span class="step-label">Barbeiro</span>
                </div>
                <div class="step" data-step="3">
                    <span class="step-number">3</span>
                    <span class="step-label">Data e Hora</span>
                </div>
                <div class="step" data-step="4">
                    <span class="step-number">4</span>
                    <span class="step-label">Dados Pessoais</span>
                </div>
                <div class="step" data-step="5">
                    <span class="step-number">5</span>
                    <span class="step-label">Confirmação</span>
                </div>
            </div>

            <!-- Mensagem de Sucesso (inicialmente oculta) -->
            <div id="success-message" class="success-message hidden">
                <h2>Marcação Concluída</h2>
                <p>A sua marcação foi concluída com sucesso!</p>
                <p>Receberá uma confirmação no seu e-mail em breve.</p>
                <div class="step-navigation">
                    <a href="index.php" class="cta-button home-btn">Voltar à Página Inicial</a>
                    <a href="marcacoes.php" class="cta-button">Fazer Nova Marcação</a>
                </div>
            </div>

            <!-- Formulário com Etapas -->
            <form id="appointment-form" action="./includes/saveBooking.php" method="POST">
                <!-- Etapa 1: Tipo de Corte -->
                <div class="step-content active" data-step="1">
                    <h2>Selecione o Tipo de Corte</h2>
                    <div class="categories">
                        <div class="category">
                            <h3 class="category-title">Corte de Cabelo Normal / Degradê <i class="fas fa-chevron-down"></i></h3>
                            <ul class="category-options">
                                <li>
                                    <button type="button" class="option-btn" data-option="Corte de Cabelo Normal / Degradê - 13€">
                                        <span class="option-name">Corte de Cabelo Normal / Degradê</span>
                                        <span class="option-price">13€</span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="option-btn" data-option="Corte de Cabelo + Barba - 18€">
                                        <span class="option-name">Corte de Cabelo + Barba</span>
                                        <span class="option-price">18€</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="category">
                            <h3 class="category-title">Corte de Cabelo de Máquina / Pente Único (Rapado) <i class="fas fa-chevron-down"></i></h3>
                            <ul class="category-options">
                                <li>
                                    <button type="button" class="option-btn" data-option="Corte de Cabelo Pente Único (Rapado) - 6€">
                                        <span class="option-name">Corte de Cabelo Pente Único (Rapado)</span>
                                        <span class="option-price">6€</span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="option-btn" data-option="Corte de Cabelo Pente Único (Rapado) + Barba - 11€">
                                        <span class="option-name">Corte de Cabelo Pente Único (Rapado) + Barba</span>
                                        <span class="option-price">11€</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="category">
                            <h3 class="category-title">Barba <i class="fas fa-chevron-down"></i></h3>
                            <ul class="category-options">
                                <li>
                                    <button type="button" class="option-btn" data-option="Barba - Tamanho + Contornos - 6€">
                                        <span class="option-name">Barba - Tamanho + Contornos</span>
                                        <span class="option-price">6€</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="category">
                            <h3 class="category-title">Corte de Criança <i class="fas fa-chevron-down"></i></h3>
                            <ul class="category-options">
                                <li>
                                    <button type="button" class="option-btn" data-option="Corte Criança - Corte Normal / Degradê (até 12 anos) - 13€">
                                        <span class="option-name">Corte Criança - Corte Normal / Degradê (até 12 anos)</span>
                                        <span class="option-price">13€</span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="option-btn" data-option="Corte Criança Pente Único (até 12 anos) - 6€">
                                        <span class="option-name">Corte Criança Pente Único (até 12 anos)</span>
                                        <span class="option-price">6€</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="service" id="service-selected">
                    <div class="step-navigation">
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Etapa 2: Barbeiro -->
                <div class="step-content" data-step="2">
                    <h2>Selecione o Barbeiro</h2>
                    <p id="selected-service" class="selected-service"></p>
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
                    <input type="hidden" name="barber" id="barber-selected">
                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Etapa 3: Data e Hora -->
                <div class="step-content" data-step="3">
                    <h2>Selecione a Data e Hora</h2>
                    <label for="date">Data</label>
                    <input type="date" id="date" name="date" required>
                    <label for="time">Horário</label>
                    <select id="time" name="time" required disabled></select>
                    <div id="loading-indicator" class="hidden">A carregar...</div>
                    <input type="hidden" name="date" id="date-selected">
                    <input type="hidden" name="time" id="time-selected">
                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Etapa 4: Dados Pessoais -->
                <div class="step-content" data-step="4">
                    <h2>Preencha os Seus Dados</h2>
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" placeholder="O seu nome" required>
                    <span id="name-error" class="error-text"></span>
                    <label for="phone">Telemóvel</label>
                    <input type="tel" id="phone" name="phone" placeholder="O seu número de telemóvel" required pattern="[0-9]{9}" maxlength="9" title="Insira um número de telefone válido com 9 dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <span id="phone-error" class="error-text"></span>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="O seu email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Insira um endereço de email válido">
                    <span id="email-error" class="error-text"></span>
                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Etapa 5: Confirmação -->
                <div class="step-content" data-step="5">
                    <h2>Confirmação</h2>
                    <div class="confirmation-summary">
                        <p><strong>Serviço:</strong> <span id="confirm-service"></span></p>
                        <p><strong>Barbeiro:</strong> <span id="confirm-barber"></span></p>
                        <p><strong>Data:</strong> <span id="confirm-date"></span></p>
                        <p><strong>Horário:</strong> <span id="confirm-time"></span></p>
                        <p><strong>Nome:</strong> <span id="confirm-name"></span></p>
                        <p><strong>Telemóvel:</strong> <span id="confirm-phone"></span></p>
                        <p><strong>E-mail:</strong> <span id="confirm-email"></span></p>
                    </div>
                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="submit" class="submit-btn">Confirmar Marcação</button>
                    </div>
                </div>
            </form>
        </section>
    </main>
    <?php include('./includes/footer.php'); ?>
</body>
</html>