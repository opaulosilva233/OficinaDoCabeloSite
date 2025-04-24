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
    const confirmService = document.getElementById('confirm-service');
    const confirmBarber = document.getElementById('confirm-barber');
    const confirmDate = document.getElementById('confirm-date');
    const confirmTime = document.getElementById('confirm-time');
    const confirmName = document.getElementById('confirm-name');
    const confirmPhone = document.getElementById('confirm-phone');
    const confirmEmail = document.getElementById('confirm-email');
    const successMessage = document.getElementById('success-message');

    // Simulação de dias cheios (sem horários disponíveis)
    // Em um ambiente real, isso seria obtido via API
    const fullDays = [
        "2025-04-25", // Exemplo: 25 de abril de 2025 está cheio
        "2025-04-26", // Exemplo: 26 de abril de 2025 está cheio
    ];

    // Variáveis de controle
    let currentStep = 1;
    let selectedService = null;
    let selectedBarber = null;
    let selectedDate = null;
    let selectedTime = null;

    // Função para mostrar a mensagem de confirmação intermediária
    function showConfirmationToast(message) {
        let toast = document.querySelector('.confirmation-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.classList.add('confirmation-toast');
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.classList.add('show');

        // Remove a mensagem após 3 segundos
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Função para atualizar o resumo na Etapa 5
    const updateConfirmationSummary = () => {
        confirmService.textContent = selectedService || 'N/A';
        confirmBarber.textContent = selectedBarber || 'N/A';
        confirmDate.textContent = dateField.value ? formatDate(new Date(dateField.value)) : 'N/A';
        confirmTime.textContent = timeSelect.value || 'N/A';
        confirmName.textContent = userName.value || 'N/A';
        confirmPhone.textContent = userPhone.value || 'N/A';
        confirmEmail.textContent = userEmail.value || 'N/A';
    };

    // Função para navegação entre etapas
    const updateStep = (step) => {
        steps.forEach(s => s.classList.remove('active'));
        stepContents.forEach(s => s.classList.remove('active'));
        steps[step - 1].classList.add('active');
        stepContents[step - 1].classList.add('active');
        steps.forEach((s, index) => {
            if (index < step - 1) {
                s.classList.add('completed');
            } else {
                s.classList.remove('completed');
            }
        });

        selectedServiceDisplay.textContent = '';
        if (step === 5) {
            updateConfirmationSummary();
        }

        currentStep = step;

        if (step === 1) updateNextButtonState(step, !!selectedService);
        if (step === 2) updateNextButtonState(step, !!selectedBarber);
        if (step === 3) {
            if (!selectedBarber || !dateField.value) {
                updateTimeSelect([], 'Selecione uma data e um barbeiro');
            } else {
                const formattedDate = formatDateForBackend(new Date(dateField.value));
                fetchAvailableTimes(selectedBarber, formattedDate);
            }
            updateNextButtonState(step, !!dateField.value && !!timeSelect.value);
        }
        if (step === 4) {
            updateNextButtonState(step, validateName() && validatePhone() && validateEmail());
        }
        if (step === 5) {
            const nextBtn = stepContents[step - 1].querySelector('.submit-btn');
            if (nextBtn) nextBtn.disabled = false;
        }
    };

    const updateNextButtonState = (step, condition) => {
        const nextBtn = stepContents[step - 1].querySelector('.next-btn') || stepContents[step - 1].querySelector('.submit-btn');
        if (nextBtn) {
            nextBtn.disabled = !condition;
            console.log(`Step ${step} - Next Button State: ${!condition ? 'Disabled' : 'Enabled'}, Condition: ${condition}`);
        }
    };

    // Função para formatar a data para exibição (DD-MM-YYYY)
    const formatDate = (date) => {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    };

    // Função para formatar a data para o backend (YYYY-MM-DD)
    const formatDateForBackend = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const fetchAvailableTimes = async (barber, date, forValidation = false) => {
        if (!barber || !date) {
            updateTimeSelect([], 'Selecione uma data e um barbeiro');
            return [];
        }

        // Verifica se o dia está na lista de dias cheios
        if (fullDays.includes(date)) {
            updateTimeSelect([], 'Nenhum horário disponível para este dia');
            return [];
        }

        loadingIndicator.classList.remove('hidden');
        timeSelect.disabled = true;
        timeSelect.innerHTML = '';

        try {
            const response = await fetch(`includes/getAvailableTimes.php?barber=${barber}&date=${date}`, {
                headers: {
                    'Cache-Control': 'no-cache'
                }
            });
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Resposta não é JSON: ${text}`);
            }

            const data = await response.json();
            if (data.success) {
                console.log(`Horários disponíveis para barbeiro=${barber}, data=${date}:`, data.slots);
                updateTimeSelect(data.slots);
                if (forValidation) return data.slots;
            } else {
                console.log(`Nenhum horário disponível para barbeiro=${barber}, data=${date}:`, data.message);
                updateTimeSelect([], data.message || 'Nenhum horário disponível');
            }
        } catch (error) {
            console.error('Erro ao buscar horários:', error);
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
            noSlots.textContent = errorMessage || 'Selecione uma data e um barbeiro';
            timeSelect.appendChild(noSlots);
            timeSelect.disabled = true;
        }
        updateNextButtonState(3, !!dateField.value && !!timeSelect.value && timeSelect.value !== '');
    };

    const validateName = () => {
        const nameValue = userName.value.trim();
        const nameRegex = /^[a-zA-ZÀ-ÿ\s]+$/;
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

    updateStep(1);
    document.querySelectorAll('.category').forEach(category => {
        category.classList.remove('expanded');
    });

    updateTimeSelect([], 'Selecione uma data e um barbeiro');

    categoryTitles.forEach(title => {
        title.addEventListener('click', () => {
            const category = title.parentElement;
            const isExpanded = category.classList.contains('expanded');
            document.querySelectorAll('.category').forEach(cat => {
                cat.classList.remove('expanded');
            });
            if (!isExpanded) {
                category.classList.add('expanded');
            }
        });
    });

    flatpickr(dateField, {
        minDate: "today",
        dateFormat: "Y-m-d",
        disable: [
            function(date) {
                return date.getDay() === 0; // Desabilita domingos
            },
            ...fullDays // Desabilita dias cheios
        ],
        onDayCreate: (dObj, dStr, fp, dayElem) => {
            // Adiciona tooltips aos dias desabilitados
            const date = new Date(dayElem.dateObj);
            const formattedDate = fp.formatDate(date, 'Y-m-d');
            if (dayElem.classList.contains('flatpickr-disabled')) {
                if (fullDays.includes(formattedDate)) {
                    dayElem.setAttribute('data-tooltip', 'Dia cheio');
                } else if (date.getDay() === 0) {
                    dayElem.setAttribute('data-tooltip', 'Fechado');
                }
            }
        },
        onMonthChange: () => {
            // Garante que os tooltips sejam reaplicados ao mudar de mês
            const days = document.querySelectorAll('.flatpickr-day');
            days.forEach(day => {
                if (day.classList.contains('flatpickr-disabled')) {
                    const date = new Date(day.dateObj);
                    const formattedDate = flatpickr.formatDate(date, 'Y-m-d');
                    if (fullDays.includes(formattedDate)) {
                        day.setAttribute('data-tooltip', 'Dia cheio');
                    } else if (date.getDay() === 0) {
                        day.setAttribute('data-tooltip', 'Fechado');
                    }
                }
            });
        },
        onChange: (selectedDates, dateStr) => {
            console.log('Data selecionada no Flatpickr:', dateStr);
            selectedDate = formatDate(new Date(dateStr)); // Armazena a data formatada para exibição
            if (selectedBarber && dateStr) {
                const formattedDate = formatDateForBackend(new Date(dateStr));
                console.log('Data formatada para o backend:', formattedDate);
                fetchAvailableTimes(selectedBarber, formattedDate);
            } else {
                updateTimeSelect([], 'Selecione uma data e um barbeiro');
                updateNextButtonState(3, false);
            }
        }
    });

    optionButtons.forEach(button => {
        button.addEventListener('click', () => {
            optionButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
            selectedService = button.getAttribute('data-option');
            serviceInput.value = selectedService;
            console.log('Selected Service:', selectedService);
            updateNextButtonState(1, true);
        });
    });

    barbers.forEach(barber => {
        barber.addEventListener('click', () => {
            barbers.forEach(b => b.classList.remove('selected'));
            barber.classList.add('selected');
            selectedBarber = barber.getAttribute('data-barber');
            barberInput.value = selectedBarber;
            console.log('Selected Barber:', selectedBarber);
            updateNextButtonState(2, true);
            if (dateField.value) {
                const formattedDate = formatDateForBackend(new Date(dateField.value));
                fetchAvailableTimes(selectedBarber, formattedDate);
            }
        });
    });

    timeSelect.addEventListener('change', () => {
        selectedTime = timeSelect.value;
        updateNextButtonState(3, !!dateField.value && !!timeSelect.value && timeSelect.value !== '');
        console.log('Date Field Value:', dateField.value, 'Time Select Value:', timeSelect.value);
    });

    userName.addEventListener('input', () => {
        validateName();
        updateNextButtonState(4, validateName() && validatePhone() && validateEmail());
    });

    userPhone.addEventListener('input', () => {
        validatePhone();
        updateNextButtonState(4, validateName() && validatePhone() && validateEmail());
    });

    userEmail.addEventListener('input', () => {
        validateEmail();
        updateNextButtonState(4, validateName() && validatePhone() && validateEmail());
    });

    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            console.log('Next button clicked, Current Step:', currentStep);
            if (currentStep < 5) {
                // Mostrar mensagem de confirmação ao avançar entre etapas
                if (currentStep === 1 && selectedService) {
                    showConfirmationToast(`Serviço selecionado: ${selectedService}`);
                } else if (currentStep === 2 && selectedBarber) {
                    showConfirmationToast(`Barbeiro selecionado: ${selectedBarber}`);
                } else if (currentStep === 3 && dateField.value && timeSelect.value) {
                    showConfirmationToast(`Data e hora confirmadas: ${formatDate(new Date(dateField.value))} às ${timeSelect.value}`);
                } else if (currentStep === 4 && validateName() && validatePhone() && validateEmail()) {
                    showConfirmationToast('Dados pessoais confirmados! Verifique o resumo.');
                }
                updateStep(currentStep + 1);
            }
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) {
                // Limpar apenas os dados das etapas futuras, mantendo os dados das etapas anteriores
                if (currentStep === 5) {
                    // Limpar dados da Etapa 4 (dados pessoais)
                    userName.value = '';
                    userPhone.value = '';
                    userEmail.value = '';
                    document.getElementById('name-error').textContent = '';
                    document.getElementById('phone-error').textContent = '';
                    document.getElementById('email-error').textContent = '';
                }
                if (currentStep >= 4) {
                    // Limpar dados da Etapa 3 (data e hora)
                    selectedDate = null;
                    selectedTime = null;
                    dateField.value = '';
                    timeSelect.innerHTML = '';
                    timeSelect.disabled = true;
                    updateTimeSelect([], 'Selecione uma data e um barbeiro');
                }
                if (currentStep >= 3) {
                    // Limpar dados da Etapa 2 (barbeiro)
                    selectedBarber = null;
                    barberInput.value = '';
                    barbers.forEach(b => b.classList.remove('selected'));
                }
                // Não limpar selectedService ao voltar para a Etapa 1
                updateStep(currentStep - 1);
            }
        });
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!validateName() || !validatePhone() || !validateEmail()) {
            updateStep(4);
            return;
        }

        if (!dateField.value) {
            alert('Por favor, selecione uma data.');
            updateStep(3);
            return;
        }

        const selectedDate = formatDateForBackend(new Date(dateField.value));
        const displayDate = formatDate(new Date(dateField.value));

        console.log('Enviando formulário:', {
            service: serviceInput.value,
            barber: barberInput.value,
            date: selectedDate,
            displayDate: displayDate,
            time: timeSelect.value,
            name: userName.value,
            phone: userPhone.value,
            email: userEmail.value
        });

        dateInput.value = selectedDate;
        timeInput.value = timeSelect.value;

        // Enviar o formulário via AJAX
        const formData = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                // Ocultar o formulário e o stepper
                form.style.display = 'none';
                document.querySelector('.stepper').style.display = 'none';
                // Exibir a mensagem de sucesso
                successMessage.classList.remove('hidden');
                successMessage.classList.add('active');
                // Disparar a animação de confetes
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ['#d4a373', '#666', '#fff']
                });
            } else {
                alert(data.message || 'Erro ao salvar a marcação. Tente novamente.');
            }
        } catch (error) {
            console.error('Erro ao enviar o formulário:', error);
            alert('Erro ao salvar a marcação. Tente novamente.');
        }
    });
});