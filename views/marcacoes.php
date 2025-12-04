<?php
$path_prefix = './';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcações - Oficina do Cabelo</title>
    <link rel="stylesheet" href="assets/css/marcacoes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <div class="appointment-section">
            <h1>Agende o seu Corte</h1>
            <p>Escolha o serviço, o barbeiro e o horário ideal para si.</p>

            <!-- Stepper -->
            <div class="stepper">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Serviço</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Barbeiro</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Data & Hora</div>
                </div>
                <div class="step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-label">Dados</div>
                </div>
                <div class="step" data-step="5">
                    <div class="step-number">5</div>
                    <div class="step-label">Confirmação</div>
                </div>
            </div>

            <form id="appointment-form" action="includes/saveBooking.php" method="POST">
                <?php require_once 'includes/CSRF.php'; ?>
                <?= CSRF::renderInput() ?>
                
                <!-- Hidden inputs for form submission -->
                <input type="hidden" id="service-selected" name="service" required>
                <input type="hidden" id="barber-selected" name="barber" required>
                <input type="hidden" id="date-selected" name="date" required>
                <input type="hidden" id="time-selected" name="time" required>

                <!-- Step 1: Serviço -->
                <div class="step-content active" data-step="1">
                    <h2>Escolha o Serviço</h2>
                    <div class="categories">
                        <div class="category expanded">
                            <div class="category-title">Cabelo <i class="fas fa-chevron-down"></i></div>
                            <div class="category-options">
                                <ul>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte de Cabelo">
                                            <span class="option-name">Corte de Cabelo</span>
                                            <span class="option-price">15€</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte Máquina">
                                            <span class="option-name">Corte Máquina</span>
                                            <span class="option-price">10€</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="category">
                            <div class="category-title">Barba <i class="fas fa-chevron-down"></i></div>
                            <div class="category-options">
                                <ul>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Barba Completa">
                                            <span class="option-name">Barba Completa</span>
                                            <span class="option-price">10€</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Aparo de Barba">
                                            <span class="option-name">Aparo de Barba</span>
                                            <span class="option-price">8€</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="category">
                            <div class="category-title">Combos <i class="fas fa-chevron-down"></i></div>
                            <div class="category-options">
                                <ul>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Cabelo + Barba">
                                            <span class="option-name">Cabelo + Barba</span>
                                            <span class="option-price">22€</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="step-navigation">
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Step 2: Barbeiro -->
                <div class="step-content" data-step="2">
                    <h2>Escolha o Barbeiro</h2>
                    <div class="barbers">
                        <div class="barber" data-barber="Bruno Martins">
                            <img src="assets/img/BBarber.png" alt="Bruno Martins">
                            <p>Bruno Martins</p>
                            <div class="specialty"><i class="fas fa-cut"></i> Especialista em Cortes Clássicos</div>
                        </div>
                        <div class="barber" data-barber="Hugo Alves">
                            <img src="assets/img/HBarber.png" alt="Hugo Alves">
                            <p>Hugo Alves</p>
                            <div class="specialty"><i class="fas fa-cut"></i> Especialista em Degrades</div>
                        </div>
                    </div>
                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Step 3: Data e Hora -->
                <div class="step-content" data-step="3">
                    <h2>Escolha a Data e Hora</h2>
                    <label for="date">Data:</label>
                    <input type="text" id="date" placeholder="Selecione uma data">
                    
                    <label for="time">Horário:</label>
                    <select id="time" disabled>
                        <option value="">Selecione uma data primeiro</option>
                    </select>
                    <div id="loading-indicator" class="hidden"><i class="fas fa-spinner fa-spin"></i> A carregar horários...</div>

                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Step 4: Dados Pessoais -->
                <div class="step-content" data-step="4">
                    <h2>Seus Dados</h2>
                    <label for="name">Nome Completo:</label>
                    <input type="text" id="name" name="name" placeholder="Ex: João Silva" required>
                    <span id="name-error" class="error-text"></span>

                    <label for="phone">Telemóvel:</label>
                    <input type="tel" id="phone" name="phone" placeholder="Ex: 912345678" maxlength="9" required>
                    <span id="phone-error" class="error-text"></span>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Ex: joao@email.com" required>
                    <span id="email-error" class="error-text"></span>

                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Step 5: Confirmação -->
                <div class="step-content" data-step="5">
                    <h2>Confirme a sua Marcação</h2>
                    <div class="confirmation-summary">
                        <h3>Resumo</h3>
                        <p><strong>Serviço:</strong> <span id="confirm-service"></span></p>
                        <p><strong>Barbeiro:</strong> <span id="confirm-barber"></span></p>
                        <p><strong>Data:</strong> <span id="confirm-date"></span></p>
                        <p><strong>Hora:</strong> <span id="confirm-time"></span></p>
                        <p><strong>Nome:</strong> <span id="confirm-name"></span></p>
                        <p><strong>Telemóvel:</strong> <span id="confirm-phone"></span></p>
                        <p><strong>Email:</strong> <span id="confirm-email"></span></p>
                    </div>
                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="submit" class="submit-btn">Confirmar Marcação</button>
                    </div>
                </div>
            </form>
            
            <div id="success-message" class="hidden confirmation-section">
                <div class="confirmation-message">
                    <i class="fas fa-check-circle" style="font-size: 3rem; color: #4caf50; margin-bottom: 20px;"></i>
                    <h2>Marcação Confirmada!</h2>
                    <p>Obrigado pela sua preferência. Enviámos um email com os detalhes.</p>
                    <a href="index.php?route=home" class="cta-button">Voltar ao Início</a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="assets/js/marcacao.js"></script>
</body>
</html>