document.addEventListener('DOMContentLoaded', () => {
    console.log('Marcacao.js loaded v3 (Inline Calendar)');

    // Elements
    const form = document.getElementById('appointment-form');
    const steps = document.querySelectorAll('.step');
    const stepContents = document.querySelectorAll('.step-content');
    const barbers = document.querySelectorAll('.barber');

    // New Date/Time Elements
    const inlineCalendarElement = document.getElementById('inline-calendar');
    const timeSlotsGrid = document.getElementById('time-slots-grid');
    const hiddenDateInput = document.getElementById('date');
    const hiddenTimeInput = document.getElementById('time');

    const loadingIndicator = document.getElementById('loading-indicator');
    const serviceInput = document.getElementById('service-selected');
    const barberInput = document.getElementById('barber-selected');
    const dateInputBackend = document.getElementById('date-selected');
    const timeInputBackend = document.getElementById('time-selected');

    const userName = document.getElementById('name');
    const userPhone = document.getElementById('phone');
    const userEmail = document.getElementById('email');

    // Confirmation
    const confirmService = document.getElementById('confirm-service');
    const confirmBarber = document.getElementById('confirm-barber');
    const confirmDate = document.getElementById('confirm-date');
    const confirmTime = document.getElementById('confirm-time');
    const confirmName = document.getElementById('confirm-name');
    const confirmPhone = document.getElementById('confirm-phone');
    const confirmEmail = document.getElementById('confirm-email');
    const successMessage = document.getElementById('success-message');

    // State
    let currentStep = 1;
    let selectedService = null;
    let selectedBarber = null;
    let selectedDate = null;
    let selectedTime = null;
    let fpInstance = null; // Flatpickr instance

    // --- UTILS ---
    const formatDate = (date) => {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    };

    const formatDateForBackend = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const showConfirmationToast = (message) => {
        let toast = document.querySelector('.confirmation-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.classList.add('confirmation-toast');
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    };

    const updateNextButtonState = (step, condition) => {
        const nextBtn = stepContents[step - 1].querySelector('.next-btn') || stepContents[step - 1].querySelector('.submit-btn');
        if (nextBtn) nextBtn.disabled = !condition;
    };

    // --- STEP LOGIC ---
    const updateStep = (step) => {
        // Scroll to top of properties
        const container = document.querySelector('.booking-container');
        if (container) {
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        steps.forEach(s => s.classList.remove('active'));
        stepContents.forEach(s => s.classList.remove('active'));
        steps[step - 1].classList.add('active');
        stepContents[step - 1].classList.add('active');
        steps.forEach((s, index) => {
            if (index < step - 1) s.classList.add('completed');
            else s.classList.remove('completed');
        });

        if (step === 5) {
            confirmService.textContent = selectedService || 'N/A';
            confirmBarber.textContent = selectedBarber || 'N/A';
            confirmDate.textContent = selectedDate ? formatDate(new Date(hiddenDateInput.value)) : 'N/A';
            confirmTime.textContent = selectedTime || 'N/A';
            confirmName.textContent = userName.value;
            confirmPhone.textContent = userPhone.value;
            confirmEmail.textContent = userEmail.value;
            confirmObservations.textContent = userObservations.value || 'Nenhuma';
        }

        currentStep = step;

        if (step === 1) updateNextButtonState(step, !!selectedService);
        if (step === 2) updateNextButtonState(step, !!selectedBarber);
        if (step === 3) {
            // Re-render calendar if needed to ensure size is correct
            if (fpInstance) fpInstance.redraw();
            updateNextButtonState(step, !!selectedDate && !!selectedTime);
        }
        if (step === 4) updateNextButtonState(step, validateForm());
    };

    // --- SERVICE SELECTION ---
    document.querySelector('.categories').addEventListener('click', (e) => {
        const button = e.target.closest('.option-btn');
        if (button) {
            e.preventDefault();
            e.stopPropagation();
            document.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
            button.classList.add('selected');
            selectedService = button.getAttribute('data-option');
            serviceInput.value = selectedService;
            showConfirmationToast(`Serviço: ${selectedService}`);
            updateNextButtonState(1, true);
        }
    });

    // Accordion
    document.querySelectorAll('.category-title').forEach(title => {
        title.addEventListener('click', (e) => {
            const category = title.parentElement;
            category.classList.toggle('expanded');
        });
    });

    // --- BARBER SELECTION ---
    barbers.forEach(barber => {
        barber.addEventListener('click', () => {
            barbers.forEach(b => b.classList.remove('selected'));
            barber.classList.add('selected');
            selectedBarber = barber.getAttribute('data-barber');
            barberInput.value = selectedBarber;
            showConfirmationToast(`Barbeiro: ${selectedBarber}`);
            updateNextButtonState(2, true);

            // Refresh calendar availability if already init
            if (fpInstance) {
                fpInstance.clear();
                updateBusyDays(fpInstance.currentMonth, fpInstance.currentYear);
            }
        });
    });

    // --- CALENDAR & TIME LOGIC ---

    const fetchSlots = async (dateStr) => {
        timeSlotsGrid.innerHTML = '';
        loadingIndicator.classList.remove('hidden');
        timeSlotsGrid.appendChild(loadingIndicator); // Move indicator inside

        try {
            const response = await fetch(`index.php?route=api/slots&barber=${selectedBarber}&date=${dateStr}`);
            const data = await response.json();

            timeSlotsGrid.innerHTML = ''; // Clear loading

            if (data.success && data.slots.length > 0) {
                data.slots.forEach(time => {
                    const btn = document.createElement('div');
                    btn.className = 'time-slot-btn';
                    btn.textContent = time;
                    btn.onclick = () => {
                        document.querySelectorAll('.time-slot-btn').forEach(b => b.classList.remove('selected'));
                        btn.classList.add('selected');
                        selectedTime = time;
                        hiddenTimeInput.value = time;
                        showConfirmationToast(`Horário: ${time}`);
                        updateNextButtonState(3, true);
                    };
                    timeSlotsGrid.appendChild(btn);
                });
            } else {
                timeSlotsGrid.innerHTML = '<p class="placeholder-text">Sem horários disponíveis.</p>';
            }
        } catch (e) {
            console.error(e);
            timeSlotsGrid.innerHTML = '<p class="placeholder-text">Erro ao carregar horários.</p>';
        } finally {
            loadingIndicator.classList.add('hidden');
        }
    };

    const updateBusyDays = async (month, year) => {
        // Adjust month to 1-based
        const m = month + 1;
        try {
            const response = await fetch(`index.php?route=api/busy-days&barber=${selectedBarber}&month=${m}&year=${year}`);
            const data = await response.json();
            if (data.success) {
                // Update flatpickr disable list
                const busyDays = data.busyDays; // Array of YYYY-MM-DD

                fpInstance.set('disable', [
                    function (date) { return date.getDay() === 0; }, // Sunday
                    ...busyDays
                ]);
            }
        } catch (e) {
            console.error("Failed to fetch busy days", e);
        }
    };

    // Init Flatpickr
    fpInstance = flatpickr(inlineCalendarElement, {
        inline: true,
        minDate: "today",
        dateFormat: "Y-m-d",
        locale: {
            firstDayOfWeek: 1 // Start on Monday
        },
        onChange: (selectedDates, dateStr) => {
            selectedDate = dateStr; // YYYY-MM-DD
            hiddenDateInput.value = dateStr;
            selectedTime = null;
            hiddenTimeInput.value = '';

            // Fetch slots
            fetchSlots(dateStr);
            updateNextButtonState(3, false);
        },
        onMonthChange: (selectedDates, dateStr, instance) => {
            updateBusyDays(instance.currentMonth, instance.currentYear);
        },
        onReady: (selectedDates, dateStr, instance) => {
            // Initial load
            updateBusyDays(instance.currentMonth, instance.currentYear);
        }
    });


    // --- VALIDATION & SUBMIT ---
    const validateForm = () => {
        // Simple check
        return userName.value.length > 2 && userPhone.value.length === 9 && userEmail.value.includes('@');
    };

    [userName, userPhone, userEmail].forEach(i => {
        i.addEventListener('input', () => updateNextButtonState(4, validateForm()));
    });

    // Navigation Buttons
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep < 5) updateStep(currentStep + 1);
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) updateStep(currentStep - 1);
        });
    });

    // Submit
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        dateInputBackend.value = hiddenDateInput.value;
        timeInputBackend.value = hiddenTimeInput.value;

        const formData = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                form.style.display = 'none';
                document.querySelector('.stepper').style.display = 'none';
                successMessage.classList.remove('hidden');
                confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
            } else {
                alert(data.message);
            }
        } catch (e) {
            alert("Erro ao enviar marcação.");
        }
    });

    // Initial State
    updateStep(1);
});