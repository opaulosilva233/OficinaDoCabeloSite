document.addEventListener('DOMContentLoaded', function () {
    // Seleção de elementos do DOM
    const buttons = document.querySelectorAll('.option-btn');
    const modal = document.getElementById('modal');
    const closeModal = document.getElementById('modal-close');
    const confirmSelection = document.getElementById('confirm-selection');
    const serviceInput = document.getElementById('service-selected');
    const barberInput = document.getElementById('barber-selected');
    const dateField = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const barbers = document.querySelectorAll('.barber');
    const body = document.body;
    const modalContainer = modal ? modal.querySelector('.modal-container') : null;
    const serviceDisplay = document.getElementById('selected-service');
    const userName = document.getElementById('name');
    const userPhone = document.getElementById('phone');
    const userEmail = document.getElementById('email');
    let selectedBarber = null;

    // Abrir modal ao selecionar um tipo de corte
    buttons.forEach(button => button.addEventListener('click', openModal));

    // Fechar modal
    if (closeModal) {
        closeModal.addEventListener('click', closeModalWindow);
    }

    // Selecionar barbeiro e ativar campo de data
    barbers.forEach(barber => barber.addEventListener('click', selectBarber));

    // Alterar data e buscar horários disponíveis
    dateField.addEventListener('change', handleDateChange);

    // Confirmar seleção
    confirmSelection.addEventListener('click', confirmBooking);

    // Funções

    // Função para abrir o modal ao clicar em um tipo de serviço
    function openModal() {
        const selectedService = this.getAttribute('data-option');
        if (serviceInput && serviceDisplay) {
            serviceInput.value = selectedService; // Armazena o tipo de serviço selecionado
            serviceDisplay.textContent = `Corte Selecionado: ${selectedService}`; // Exibe o serviço selecionado
            serviceDisplay.classList.add('highlight-service'); // Destaca o serviço

            // Exibe o modal
            showModal();
        }
        resetModal(); // Reseta os campos do modal
    }

    // Função para mostrar o modal
    function showModal() {
        if (modal && document.getElementById('modal-backdrop')) {
            modal.style.display = 'flex'; // Exibe o modal
            document.getElementById('modal-backdrop').style.display = 'block'; // Exibe o fundo semitransparente
            body.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; // Aplica o fundo escurecido
            modalContainer.style.backgroundColor = '#ffffff'; // Define o fundo do modal
        }
    }

    // Função para fechar o modal
    function closeModalWindow() {
        if (modal && document.getElementById('modal-backdrop')) {
            modal.style.display = 'none'; // Esconde o modal
            document.getElementById('modal-backdrop').style.display = 'none'; // Esconde o fundo
            body.style.backgroundColor = ''; // Restaura a cor do fundo
            modalContainer.style.backgroundColor = ''; // Restaura o fundo do modal
        }
        resetModal(); // Reseta os campos do modal
    }

    // Função para resetar o modal (limpar campos)
    function resetModal() {
        barbers.forEach(b => b.classList.remove('selected')); // Remove a seleção do barbeiro
        dateField.value = ''; // Limpa a data
        timeSelect.innerHTML = ''; // Limpa as opções de horário
        dateField.disabled = true; // Desabilita o campo de data
        timeSelect.disabled = true; // Desabilita o campo de horário
    }

    // Função para selecionar o barbeiro
    function selectBarber() {
        barbers.forEach(b => b.classList.remove('selected')); // Remove a seleção dos barbeiros
        this.classList.add('selected'); // Adiciona a seleção ao barbeiro clicado
        selectedBarber = this.getAttribute('data-barber'); // Armazena o barbeiro selecionado
        barberInput.value = selectedBarber; // Armazena o barbeiro no campo oculto
        dateField.disabled = false; // Habilita o campo de data
        timeSelect.innerHTML = ''; // Limpa os horários
        timeSelect.disabled = true; // Desabilita o campo de horário
        configureDateField(); // Configura o campo de data (se necessário)
    }

    // Função para configurar o campo de data (pode ser personalizada conforme necessário)
    function configureDateField() {
        // Lógica de configuração do campo de data (se necessário)
    }

    // Função chamada quando a data é alterada
    function handleDateChange() {
        const selectedDate = formatDate(new Date(dateField.value)); // Formata a data selecionada
        if (selectedBarber && selectedDate) {
            fetchAvailableTimes(selectedBarber, selectedDate); // Busca horários disponíveis para o barbeiro e data selecionados
        }
    }

    // Função para formatar a data no formato "DD-MM-YYYY"
    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0'); // Adiciona zero à esquerda se necessário
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Adiciona zero à esquerda se necessário
        const year = date.getFullYear(); // Ano
        return `${day}-${month}-${year}`; // Retorna a data formatada
    }

    // Função para buscar os horários disponíveis via AJAX
    function fetchAvailableTimes(barber, date) {
        fetch(`includes/getAvailableTimes.php?barber=${barber}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                updateTimeSelect(data.slots); // Atualiza os horários no select
            })
            .catch(() => updateTimeSelect([], 'Erro ao buscar horários')); // Exibe erro se falhar
    }

    // Função para atualizar a lista de horários no select
    function updateTimeSelect(slots, errorMessage = '') {
        timeSelect.innerHTML = ''; // Limpa as opções anteriores
        if (slots.length > 0) {
            // Se houver horários disponíveis
            slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;
                timeSelect.appendChild(option); // Adiciona as opções de horário
            });
            timeSelect.disabled = false; // Habilita o select de horário
        } else {
            // Se não houver horários disponíveis
            const noSlots = document.createElement('option');
            noSlots.value = '';
            noSlots.textContent = errorMessage || 'Nenhum horário disponível';
            timeSelect.appendChild(noSlots); // Adiciona a opção de "Nenhum horário disponível"
            timeSelect.disabled = true; // Desabilita o select de horário
        }
    }

    // Função chamada ao confirmar a marcação
    function confirmBooking() {
        const selectedDate = formatDate(new Date(dateField.value)); // Formata a data selecionada
        const selectedTime = timeSelect.value; // Obtém o horário selecionado

        // Validação dos campos obrigatórios
        if (!selectedBarber || !selectedDate || !selectedTime) {
            alert('Por favor, preencha todos os campos.');
            return;
        }

        const bookingData = {
            service: serviceInput.value,
            barber: selectedBarber,
            date: selectedDate,
            time: selectedTime,
            name: userName.value,
            phone: userPhone.value,
            email: userEmail.value,
        };

        // Validar o formulário
        const validationResult = validateBookingForm(bookingData);

        if (!validationResult.isValid) {
            alert(validationResult.errors.join('\n')); // Exibe os erros de validação
            return;
        }

        // Armazenar os dados no localStorage
        localStorage.setItem('bookingData', JSON.stringify(bookingData));

        // Enviar os dados ao backend
        sendBookingData();
    }

    // Função para validar os dados do formulário
    function validateBookingForm(data) {
        const errors = [];
        if (!data.name) errors.push('Nome é obrigatório.');
        if (!data.phone) errors.push('Telefone é obrigatório.');
        if (!data.email) errors.push('E-mail é obrigatório.');
        if (!data.service) errors.push('Serviço é obrigatório.');
        if (!data.barber) errors.push('Barbeiro é obrigatório.');
        if (!data.date) errors.push('Data é obrigatória.');
        if (!data.time) errors.push('Hora é obrigatória.');

        return { isValid: errors.length === 0, errors }; // Retorna a validação
    }

    // Função para enviar os dados ao backend
    function sendBookingData() {
        const storedData = localStorage.getItem('bookingData');
        if (storedData) {
            const bookingData = JSON.parse(storedData);
            const formData = new FormData();
            for (let key in bookingData) {
                formData.append(key, bookingData[key]); // Adiciona os dados ao FormData
            }

            fetch('includes/saveBooking.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(responseData => handleBookingResponse(responseData)) // Lida com a resposta do backend
            .catch(() => redirectToErrorPage('Houve um erro ao tentar realizar a marcação.')); // Redireciona em caso de erro
        }
    }

    // Função para tratar a resposta do backend após tentar salvar a marcação
    function handleBookingResponse(responseData) {
        if (responseData.success) {
            alert('Reserva realizada com sucesso!');
            localStorage.removeItem('bookingData'); // Limpa os dados armazenados
            window.location.href = "marcacoes.php"; // Redireciona para a página de marcações
        } else {
            redirectToErrorPage(responseData.message); // Redireciona em caso de erro
        }
    }

    // Função para redirecionar para uma página de erro
    function redirectToErrorPage(message) {
        const errorMessage = encodeURIComponent(message);
        window.location.href = `erroMarcacao.php?error=${errorMessage}`; // Redireciona para a página de erro
    }
});