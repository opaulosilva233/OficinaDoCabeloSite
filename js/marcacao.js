document.addEventListener('DOMContentLoaded', () => {
    // Elementos do DOM
    const form = document.getElementById('appointment-form');
    const steps = document.querySelectorAll('.step');
    const stepContents = document.querySelectorAll('.step-content');
    const optionButtons = document.querySelectorAll('.option-btn');
    const barbers = document.querySelectorAll('.barber');
    const dateField = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const loadingIndicator = document.getElementById('loading-indicator');
    const serviceInput = document.getElementById('service-selected');
    const barberInput = document.getElementById('barber-selected');
    const dateInput = document.getElementById('date-selected');
    const timeInput = document.getElementById('time-selected');
    const userName = document.getElementById('name');
    const userPhone = document.getElementById('phone');
    const userEmail = document.getElementById('email');
    const categoryTitles = document.querySelectorAll('.category-title');
    const selectedServiceDisplay = document.getElementById('selected-service');
    let currentStep = 1;
    let selectedService = null;
    let selectedBarber = null;
    let lockId = null;

    // Função para navegação entre etapas
    const updateStep = (step) => {
        // Remover classe active de todas as etapas
        steps.forEach(s => s.classList.remove('active'));
        stepContents.forEach(s => s.classList.remove('active'));

        // Adicionar classe active apenas à etapa atual
        steps[step - 1].classList.add('active');
        stepContents[step - 1].classList.add('active');

        // Marcar etapas anteriores como concluídas e remover a classe completed das etapas futuras e da etapa atual
        steps.forEach((s, index) => {
            if (index < step - 1) {
                s.classList.add('completed');
            } else {
                s.classList.remove('completed');
            }
        });

        // Atualizar o texto do tipo de corte selecionado na Etapa 2
        if (step === 2 && selectedService) {
            selectedServiceDisplay.textContent = `Tipo de Corte Selecionado: ${selectedService}`;
        } else {
            selectedServiceDisplay.textContent = '';
        }

        currentStep = step;

        // Atualizar estado dos botões "Avançar"
        if (step === 1) updateNextButtonState(step, selectedService);
        if (step === 2) updateNextButtonState(step, selectedBarber);
        if (step === 3) updateNextButtonState(step, dateField.value && timeSelect.value);
    };

    // Estado do botão "Avançar"
    const updateNextButtonState = (step, condition) => {
        const nextBtn = stepContents[step - 1].querySelector('.next-btn');
        if (nextBtn) {
            nextBtn.disabled = !condition;
        }
    };

    // Funções auxiliares
    const formatDate = (date) => {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    };

    const fetchAvailableTimes = async (barber, date, forValidation = false) => {
        loadingIndicator.classList.remove('hidden');
        timeSelect.disabled = true;
        timeSelect.innerHTML = '';

        try {
            const response = await fetch(`includes/getAvailableTimes.php?barber=${barber}&date=${date}`);
            const data = await response.json();
            if (data.success) {
                updateTimeSelect(data.slots);
                if (forValidation) return data.slots;
            } else {
                updateTimeSelect([], data.message);
            }
        } catch {
            updateTimeSelect([], 'Erro ao buscar horários');
        } finally {
            loadingIndicator.classList.add('hidden');
        }
        return [];
    };

    const updateTimeSelect = (slots, errorMessage = '') => {
        timeSelect.innerHTML = '';
        if (slots.length > 0) {
            slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;
                timeSelect.appendChild(option);
            });
            timeSelect.disabled = false;
        } else {
            const noSlots = document.createElement('option');
            noSlots.value = '';
            noSlots.textContent = errorMessage || 'Nenhum horário disponível';
            timeSelect.appendChild(noSlots);
        }
    };

    const lockTimeSlot = async (barber, date, time) => {
        try {
            const response = await fetch('includes/lockTimeSlot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `barber=${barber}&date=${date}&time=${time}`
            });
            const data = await response.json();
            if (data.success) {
                lockId = data.lock_id;
            } else {
                alert('Não foi possível reservar este horário. Por favor, escolha outro.');
                fetchAvailableTimes(barber, date);
            }
        } catch {
            alert('Erro ao reservar o horário. Tente novamente.');
            fetchAvailableTimes(barber, date);
        }
    };

    const releaseTimeSlot = async (lockId) => {
        try {
            await fetch('includes/releaseTimeSlot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `lock_id=${lockId}`
            });
            lockId = null;
        } catch {
            console.error('Erro ao liberar o horário');
        }
    };

    // Funções de validação
    const validateName = () => {
        const nameValue = userName.value.trim();
        const nameRegex = /^[a-zA-Z\s]+$/;
        if (nameValue === '') {
            document.getElementById('name-error').textContent = 'O nome é obrigatório';
            return false;
        } else if (nameValue.length < 3 || !nameRegex.test(nameValue)) {
            document.getElementById('name-error').textContent = 'O nome deve ter no mínimo 3 caracteres e conter apenas letras e espaços.';
            return false;
        } else {
            document.getElementById('name-error').textContent = '';
            return true;
        }
    };

    const validatePhone = () => {
        const phoneValue = userPhone.value.trim();
        if (phoneValue.length !== 9 || isNaN(phoneValue)) {
            document.getElementById('phone-error').textContent = 'O telemóvel deve ter 9 dígitos.';
            return false;
        } else {
            document.getElementById('phone-error').textContent = '';
            return true;
        }
    };

    const validateEmail = () => {
        const emailValue = userEmail.value.trim();
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(emailValue)) {
            document.getElementById('email-error').textContent = 'O e-mail é inválido.';
            return false;
        } else {
            document.getElementById('email-error').textContent = '';
            return true;
        }
    };

    // Inicializar: apenas a primeira etapa visível e categorias fechadas
    updateStep(1);
    document.querySelectorAll('.category').forEach(category => {
        category.classList.remove('expanded');
    });

    // Acordeão para categorias
    categoryTitles.forEach(title => {
        title.addEventListener('click', () => {
            const category = title.parentElement;
            const isExpanded = category.classList.contains('expanded');

            // Fechar todas as outras categorias
            document.querySelectorAll('.category').forEach(cat => {
                cat.classList.remove('expanded');
            });

            // Abrir ou fechar a categoria clicada
            if (!isExpanded) {
                category.classList.add('expanded');
            }
        });
    });

    // Configurar data mínima (hoje) e desabilitar domingos
    const today = new Date().toISOString().split('T')[0];
    dateField.setAttribute('min', today);
    dateField.addEventListener('change', () => {
        const selectedDate = new Date(dateField.value);
        if (selectedDate.getDay() === 0) {
            alert('A barbearia está fechada aos domingos. Por favor, escolha outro dia.');
            dateField.value = '';
        }
    });

    // Etapa 1: Seleção do Tipo de Corte
    optionButtons.forEach(button => {
        button.addEventListener('click', () => {
            optionButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
            selectedService = button.getAttribute('data-option');
            serviceInput.value = selectedService;
            updateNextButtonState(1, true);
        });
    });

    // Etapa 2: Seleção do Barbeiro
    barbers.forEach(barber => {
        barber.addEventListener('click', () => {
            barbers.forEach(b => b.classList.remove('selected'));
            barber.classList.add('selected');
            selectedBarber = barber.getAttribute('data-barber');
            barberInput.value = selectedBarber;
            updateNextButtonState(2, true);
        });
    });

    // Etapa 3: Seleção de Data e Hora
    dateField.addEventListener('change', () => {
        const selectedDate = formatDate(new Date(dateField.value));
        if (selectedBarber && selectedDate) {
            fetchAvailableTimes(selectedBarber, selectedDate);
        }
    });

    timeSelect.addEventListener('change', () => {
        updateNextButtonState(3, dateField.value && timeSelect.value);
        if (timeSelect.value) {
            lockTimeSlot(selectedBarber, formatDate(new Date(dateField.value)), timeSelect.value);
        }
    });

    // Etapa 4: Validação de Dados Pessoais
    userName.addEventListener('input', validateName);
    userPhone.addEventListener('input', validatePhone);
    userEmail.addEventListener('input', validateEmail);

    // Navegação
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep < 4) updateStep(currentStep + 1);
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) {
                if (currentStep === 3 && lockId) {
                    releaseTimeSlot(lockId);
                }
                updateStep(currentStep - 1);
            }
        });
    });

    // Formulário
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!validateName() || !validatePhone() || !validateEmail()) return;

        // Verificar se o horário ainda está disponível
        const selectedDate = formatDate(new Date(dateField.value));
        const availableTimes = await fetchAvailableTimes(selectedBarber, selectedDate, true);
        if (!availableTimes.includes(timeSelect.value)) {
            alert('Desculpe, este horário já foi reservado. Por favor, escolha outro.');
            updateStep(3);
            return;
        }

        dateInput.value = selectedDate;
        timeInput.value = timeSelect.value;
        form.submit();
    });
});