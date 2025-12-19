<?php
$path_prefix = './';
require_once 'includes/CSRF.php';
CSRF::generateToken();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcações - Oficina do Cabelo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/marcacoes.css?v=<?= time() ?>">
</head>
<body>
    <!-- Fullscreen App Layout: No Footer/Header includes -->

    <main class="booking-container">
        <div class="booking-wrapper">
             <div class="booking-layout-container">
                <!-- Sidebar (Stepper) -->
                <aside class="booking-sidebar">
                    <!-- Sidebar Header: Back Link + Logo -->
                    <div class="sidebar-header">
                        <div class="sidebar-controls">
                            <a href="index.php?route=home" class="back-to-site">
                                <i class="fas fa-chevron-left"></i> Voltar
                            </a>
                            <button id="theme-toggle" class="theme-toggle" aria-label="Alternar Tema">
                                <i class="fas fa-moon"></i>
                            </button>
                        </div>
                        <img src="assets/img/logotipo.png" alt="Logo" class="sidebar-logo">
                        <h3>Oficina do Cabelo</h3>
                    </div>

                    <div class="stepper vertical">
                        <div class="step active" data-step="1">
                            <div class="step-icon">
                                <i class="fas fa-cut"></i>
                            </div>
                            <div class="step-info">
                                <span class="step-subtitle">Passo 1</span>
                                <span class="step-label">Serviço</span>
                            </div>
                        </div>
                        <div class="step" data-step="2">
                             <div class="step-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="step-info">
                                <span class="step-subtitle">Passo 2</span>
                                <span class="step-label">Barbeiro</span>
                            </div>
                        </div>
                        <div class="step" data-step="3">
                             <div class="step-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                           <div class="step-info">
                                <span class="step-subtitle">Passo 3</span>
                                <span class="step-label">Horário</span>
                            </div>
                        </div>
                        <div class="step" data-step="4">
                             <div class="step-icon">
                                <i class="fas fa-address-card"></i>
                            </div>
                            <div class="step-info">
                                <span class="step-subtitle">Passo 4</span>
                                <span class="step-label">Dados</span>
                            </div>
                        </div>
                        <div class="step" data-step="5">
                             <div class="step-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="step-info">
                                <span class="step-subtitle">Passo 5</span>
                                <span class="step-label">Resumo</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Navigation Buttons -->
                    <div class="sidebar-navigation">
                        <button type="button" id="sidebar-prev-btn" class="nav-btn prev-btn hidden">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </button>
                        <button type="button" id="sidebar-next-btn" class="nav-btn next-btn" disabled>
                            Avançar <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </aside>

                <!-- Main Content Area -->
                <div class="booking-main-content">
                    <div class="booking-header">
                        <h2>Agende o seu Corte</h2>
                        <p>Escolha o serviço, o barbeiro e o horário ideal para si.</p>
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
                        </div>

                        <!-- Step 3: Data e Hora -->
                        <div class="step-content" data-step="3">
                            <h2>Escolha a Data e Hora</h2>
                            
                            <div class="date-time-container">
                                <!-- Calendar Side -->
                                <div class="calendar-wrapper">
                                    <h3>Selecione o Dia</h3>
                                    <div id="custom-calendar-container"></div>
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
                                        <div class="summary-row clickable" data-go-to-step="1">
                                            <span class="label">Serviço <i class="fas fa-pen ml-2"></i></span>
                                            <span class="value highlight" id="confirm-service"></span>
                                        </div>
                                        <div class="summary-row clickable" data-go-to-step="2">
                                            <span class="label">Barbeiro <i class="fas fa-pen ml-2"></i></span>
                                            <span class="value" id="confirm-barber"></span>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="summary-row clickable" data-go-to-step="3">
                                            <span class="label">Data <i class="fas fa-pen ml-2"></i></span>
                                            <span class="value" id="confirm-date"></span>
                                        </div>
                                        <div class="summary-row clickable" data-go-to-step="3">
                                            <span class="label">Horário <i class="fas fa-pen ml-2"></i></span>
                                            <span class="value" id="confirm-time"></span>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="summary-row clickable" data-go-to-step="4">
                                            <span class="label">Cliente <i class="fas fa-pen ml-2"></i></span>
                                            <span class="value" id="confirm-name"></span>
                                        </div>
                                        <div class="summary-row clickable" data-go-to-step="4">
                                            <span class="label">Contacto <i class="fas fa-pen ml-2"></i></span>
                                            <span class="value" id="confirm-phone"></span>
                                        </div>
                                        <div class="summary-row clickable" data-go-to-step="4">
                                            <span class="label">Email <i class="fas fa-pen ml-2"></i></span>
                                            <span class="value" id="confirm-email"></span>
                                        </div>
                                        <div class="summary-full clickable" data-go-to-step="4">
                                            <span class="label">Observações <i class="fas fa-pen ml-2"></i></span>
                                            <span class="value" id="confirm-observations"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="success-message" class="hidden success-overlay">
                        <div class="success-card">
                            <div class="success-icon-container">
                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                </svg>
                            </div>
                            <h2>Agendamento Confirmado!</h2>
                            <p>O seu lugar na barbeira está guardado com sucesso.</p>
                            <div class="success-details">
                                <p>Enviámos um email com todos os detalhes.</p>
                            </div>
                            <div class="success-actions">
                                <a href="index.php?route=home" class="cta-button primary">
                                    <i class="fas fa-home"></i> Voltar ao Início
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="assets/js/marcacao.js?v=<?= time() ?>"></script>
</body>
</html>