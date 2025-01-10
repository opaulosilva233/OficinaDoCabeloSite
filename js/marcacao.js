document.addEventListener('DOMContentLoaded', function () {
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
    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            const selectedService = button.getAttribute('data-option');
            if (serviceInput && serviceDisplay) {
                serviceInput.value = selectedService;
                serviceDisplay.textContent = `Corte Selecionado: ${selectedService}`;
                serviceDisplay.classList.add('highlight-service');

                if (modal && document.getElementById('modal-backdrop')) {
                    modal.style.display = 'flex';
                    document.getElementById('modal-backdrop').style.display = 'block';
                    body.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                    modalContainer.style.backgroundColor = '#ffffff';
                }
                resetModal();
            }
        });
    });

    // Fechar modal
    if (closeModal) {
        closeModal.addEventListener('click', function () {
            if (modal && document.getElementById('modal-backdrop')) {
                modal.style.display = 'none';
                document.getElementById('modal-backdrop').style.display = 'none';
                body.style.backgroundColor = '';
                modalContainer.style.backgroundColor = '';
            }
            resetModal();
        });
    }

    // Selecionar barbeiro e ativar campo de data
    barbers.forEach(function (barber) {
        barber.addEventListener('click', function () {
            barbers.forEach(b => b.classList.remove('selected'));
            barber.classList.add('selected');

            selectedBarber = barber.getAttribute('data-barber');
            barberInput.value = selectedBarber;

            dateField.disabled = false;
            dateField.value = '';
            timeSelect.innerHTML = '';
            timeSelect.disabled = true;

            configureDateField();
        });
    });

    // Alterar data e buscar horários disponíveis
    dateField.addEventListener('change', function () {
        const selectedDate = formatDate(new Date(dateField.value));
        if (selectedBarber && selectedDate) {
            fetchAvailableTimes(selectedBarber, selectedDate);
        }
    });

    // Função para formatar uma data em "DD-MM-YYYY"
    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }

    // Função para buscar horários disponíveis via AJAX
    function fetchAvailableTimes(barber, date) {
        fetch(`includes/getAvailableTimes.php?barber=${barber}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                timeSelect.innerHTML = '';
                if (data.slots && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSelect.appendChild(option);
                    });
                    timeSelect.disabled = false;
                } else {
                    const noSlots = document.createElement('option');
                    noSlots.value = '';
                    noSlots.textContent = 'Nenhum horário disponível';
                    timeSelect.appendChild(noSlots);
                    timeSelect.disabled = true;
                }
            })
            .catch(error => {
                const noSlots = document.createElement('option');
                noSlots.value = '';
                noSlots.textContent = 'Erro ao buscar horários';
                timeSelect.appendChild(noSlots);
                timeSelect.disabled = true;
            });
    }

    // Confirmar seleção, armazenar localmente e enviar ao backend
    confirmSelection.addEventListener('click', function () {
        const selectedDate = formatDate(new Date(dateField.value));
        const selectedTime = timeSelect.value;

        if (!selectedBarber) {
            alert('Por favor, selecione um barbeiro.');
            return;
        }
        if (!selectedDate) {
            alert('Por favor, selecione uma data.');
            return;
        }
        if (!selectedTime) {
            alert('Por favor, selecione um horário.');
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
            alert(validationResult.errors.join('\n'));
            return;
        }

        // Armazenar os dados no localStorage
        localStorage.setItem('bookingData', JSON.stringify(bookingData));

        // Enviar os dados ao backend
        sendBookingData();
    });

    // Função para enviar os dados ao backend usando FormData
    function sendBookingData() {
        const storedData = localStorage.getItem('bookingData');
        if (storedData) {
            const bookingData = JSON.parse(storedData);

            const formData = new FormData();
            for (let key in bookingData) {
                formData.append(key, bookingData[key]);
            }

            fetch('saveBooking.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(responseData => {
                if (responseData.success) {
                    alert('Reserva realizada com sucesso!');
                    localStorage.removeItem('bookingData');
                    window.location.href = "marcacoes.php";  // Redirecionar para a página de marcações
                } else {
                    // Redirecionar para erroMarcacao.php em caso de erro
                    const errorMessage = encodeURIComponent(responseData.message);
                    window.location.href = `erroMarcacao.php?error=${errorMessage}`;
                }
            })
            .catch(error => {
                // Redirecionar para erroMarcacao.php se houver erro ao tentar realizar a marcação
                window.location.href = `erroMarcacao.php?error=Houve um erro ao tentar realizar a marcação.`;
            });
        }
    }

    // Função para resetar o modal
    function resetModal() {
        barbers.forEach(b => b.classList.remove('selected'));
        dateField.value = '';
        timeSelect.innerHTML = '';
        dateField.disabled = true;
        timeSelect.disabled = true;
    }

    // Configuração do campo de data
    function configureDateField() {
        // Lógica de configuração do campo de data (se necessário)
    }
});
