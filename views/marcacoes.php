<?php
$path_prefix = './';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcações - Oficina do Cabelo</title>
    <link rel="stylesheet" href="assets/css/marcacoes.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-header">
        <h1>Marcações Online</h1>
        <p>O seu estilo, o nosso compromisso. Agende já.</p>
    </div>

    <main class="booking-container">
        <div class="booking-wrapper">
            <div class="booking-header">
                <h2>Agende o seu Corte</h2>
                <p>Escolha o serviço, o barbeiro e o horário ideal para si.</p>
            </div>
            
            <!-- Stepper Navigation -->
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

            <form id="appointment-form" action="index.php?route=api/book" method="POST">
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
                        <!-- Corte de Cabelo Normal / Degradê -->
                        <div class="category expanded">
                            <div class="category-title">Corte de Cabelo Normal / Degradê <i class="fas fa-chevron-down"></i></div>
                            <div class="category-options">
                                <ul>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte de Cabelo - Corte Normal / Degradê">
                                            <span class="option-name">Corte de Cabelo - Corte Normal / Degradê</span>
                                            <span class="option-price">14€</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte de Cabelo + Barba">
                                            <span class="option-name">Corte de Cabelo + Barba</span>
                                            <span class="option-price">19€</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Corte com Gonçalo -->
                        <div class="category">
                            <div class="category-title">Corte com Gonçalo <i class="fas fa-chevron-down"></i></div>
                            <div class="category-options">
                                <ul>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte de Cabelo">
                                            <span class="option-name">Corte de Cabelo</span>
                                            <span class="option-price">13€</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte de Cabelo + Barba">
                                            <span class="option-name">Corte de Cabelo + Barba</span>
                                            <span class="option-price">19€</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte de Criança">
                                            <span class="option-name">Corte de Criança</span>
                                            <span class="option-price">13€</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                         <!-- Corte de Cabelo de Máquina / Pente Único (Rapado) -->
                         <div class="category">
                            <div class="category-title">Corte Máquina / Pente Único <i class="fas fa-chevron-down"></i></div>
                            <div class="category-options">
                                <ul>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte de Cabelo Pente Único (Rapado)">
                                            <span class="option-name">Corte de Cabelo Pente Único (Rapado)</span>
                                            <span class="option-price">6€</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte de Cabelo Pente Único (Rapado) + Barba">
                                            <span class="option-name">Corte de Cabelo Pente Único (Rapado) + Barba</span>
                                            <span class="option-price">12€</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Barba -->
                        <div class="category">
                            <div class="category-title">Barba <i class="fas fa-chevron-down"></i></div>
                            <div class="category-options">
                                <ul>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Barba - Tamanho + Contornos">
                                            <span class="option-name">Barba - Tamanho + Contornos</span>
                                            <span class="option-price">6€</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Corte de Criança -->
                        <div class="category">
                            <div class="category-title">Corte de Criança <i class="fas fa-chevron-down"></i></div>
                            <div class="category-options">
                                <ul>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte Criança - Corte Normal / Degradê (até 12 anos)">
                                            <span class="option-name">Corte Normal / Degradê (até 12 anos)</span>
                                            <span class="option-price">14€</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="option-btn" data-option="Corte Criança Pente Único (até 12 anos)">
                                            <span class="option-name">Corte Pente Único (até 12 anos)</span>
                                            <span class="option-price">6€</span>
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
                    
                    <div class="date-time-container">
                        <!-- Calendar Side -->
                        <div class="calendar-wrapper">
                            <h3>Selecione o Dia</h3>
                            <div id="inline-calendar"></div>
                            <input type="hidden" id="date" required>
                        </div>

                        <!-- Time Slots Side -->
                        <div class="time-slots-wrapper">
                            <h3>Horários Disponíveis</h3>
                            <div id="time-slots-grid" class="time-grid">
                                <p class="placeholder-text">Selecione um dia para ver os horários.</p>
                            </div>
                            <div id="loading-indicator" class="hidden">
                                <i class="fas fa-spinner fa-spin"></i> A atualizar...
                            </div>
                            <input type="hidden" id="time" required>
                        </div>
                    </div>

                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Step 4: Dados Pessoais -->
                <div class="step-content" data-step="4">
                    <h2>Seus Dados</h2>
                    <div class="form-grid">
                        <div class="input-group">
                            <label for="name"><i class="fas fa-user"></i> Nome Completo</label>
                            <input type="text" id="name" name="name" placeholder="Ex: João Silva" required>
                            <span id="name-error" class="error-text"></span>
                        </div>

                        <div class="input-group">
                            <label for="phone"><i class="fas fa-phone"></i> Telemóvel</label>
                            <input type="tel" id="phone" name="phone" placeholder="Ex: 912345678" maxlength="9" required>
                            <span id="phone-error" class="error-text"></span>
                        </div>

                        <div class="input-group full-width">
                            <label for="email"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" id="email" name="email" placeholder="Ex: joao@email.com" required>
                            <span id="email-error" class="error-text"></span>
                        </div>

                        <div class="input-group full-width">
                            <label for="observations"><i class="fas fa-comment-alt"></i> Observações (Opcional)</label>
                            <textarea id="observations" name="observations" placeholder="Ex: Tenho uma cicatriz, prefiro tesoura, etc." rows="3"></textarea>
                        </div>
                    </div>

                    <div class="step-navigation">
                        <button type="button" class="prev-btn">Voltar</button>
                        <button type="button" class="next-btn" disabled>Avançar</button>
                    </div>
                </div>

                <!-- Step 5: Confirmação -->
                <div class="step-content" data-step="5">
                    <div class="confirmation-container">
                        <h2>Confirme os Detalhes</h2>
                        <p class="subtitle">Quase lá! Verifique se está tudo correto.</p>
                        
                        <div class="summary-card">
                            <div class="summary-header">
                                <i class="fas fa-calendar-check"></i>
                                <h3>Resumo da Marcação</h3>
                            </div>
                            <div class="summary-body">
                                <div class="summary-row">
                                    <span class="label">Serviço</span>
                                    <span class="value highlight" id="confirm-service"></span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Barbeiro</span>
                                    <span class="value" id="confirm-barber"></span>
                                </div>
                                <div class="divider"></div>
                                <div class="summary-row">
                                    <span class="label">Data</span>
                                    <span class="value" id="confirm-date"></span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Horário</span>
                                    <span class="value" id="confirm-time"></span>
                                </div>
                                <div class="divider"></div>
                                <div class="summary-row">
                                    <span class="label">Cliente</span>
                                    <span class="value" id="confirm-name"></span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Contacto</span>
                                    <span class="value" id="confirm-phone"></span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Email</span>
                                    <span class="value" id="confirm-email"></span>
                                </div>
                                <div class="summary-full">
                                    <span class="label">Observações</span>
                                    <span class="value" id="confirm-observations"></span>
                                </div>
                            </div>
                        </div>

                        <div class="step-navigation">
                            <button type="button" class="prev-btn">Voltar</button>
                            <button type="submit" class="submit-btn">Agendar Agora <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </form>
            
            <div id="success-message" class="hidden confirmation-section">
                <div class="success-card">
                    <div class="success-icon-wrapper">
                        <i class="fas fa-check"></i>
                    </div>
                    <h2>Agendamento Confirmado!</h2>
                    <p>O seu lugar está guardado. Enviámos os detalhes para o seu email.</p>
                    <div class="success-actions">
                        <a href="index.php?route=home" class="cta-button">Voltar ao Início</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="assets/js/marcacao.js?v=<?= time() ?>"></script>
</body>
</html>