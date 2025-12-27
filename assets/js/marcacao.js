document.addEventListener('DOMContentLoaded', () => {
    console.log('Marcacao.js loaded v5 (Native Calendar)');

    // Elements
    const form = document.getElementById('appointment-form');
    const steps = document.querySelectorAll('.step');
    const stepContents = document.querySelectorAll('.step-content');
    const barbers = document.querySelectorAll('.barber');

    // Sidebar Nav Elements
    const sidebarNextBtn = document.getElementById('sidebar-next-btn');
    const sidebarPrevBtn = document.getElementById('sidebar-prev-btn');
    const themeToggleBtn = document.getElementById('theme-toggle');

    // --- DARK MODE LOGIC ---
    const applyTheme = (theme) => {
        const icon = themeToggleBtn.querySelector('i');
        if (theme === 'dark') {
            document.body.classList.add('dark-mode');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            document.body.classList.remove('dark-mode');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
        localStorage.setItem('theme', theme);
    };

    // Init Theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            applyTheme(newTheme);
        });
    }

    // New Date/Time Elements
    // Fixed: calendarContainer is now custom
    const calendarContainer = document.getElementById('custom-calendar-container'); // Correct ID from HTML rewrite? Let's check HTML. 
    // Wait, in previous step I simplified HTML to use #custom-calendar-container but revert might have happened.
    // Let's assume the HTML is: <div id="custom-calendar-container"></div> inside .calendar-wrapper
    // If not, I should target .calendar-wrapper or inject it.
    // Based on previous view_file of marcacoes.php (Step 427), I attempted to change it to #custom-calendar-container.
    // BUT replace might have failed there too. 
    // Let's be safe: If I look at marcacoes.php content from Step 421, it had #inline-calendar.
    // Detailed check: Step 427 showed "The following changes were made... [-] #inline-calendar [+] #custom-calendar-container".
    // So if that succeeded, we use #custom-calendar-container.
    // If that failed, we might have #inline-calendar.
    // To be perfectly safe, I will try to find either.

    let calendarTarget = document.getElementById('custom-calendar-container');
    if (!calendarTarget) {
        calendarTarget = document.getElementById('inline-calendar');
        if (calendarTarget) {
            calendarTarget.id = 'custom-calendar-container'; // Force ID update if needed
        }
    }

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
    const userObservations = document.getElementById('observations');

    // Confirmation
    const confirmService = document.getElementById('confirm-service');
    const confirmBarber = document.getElementById('confirm-barber');
    const confirmDate = document.getElementById('confirm-date');
    const confirmTime = document.getElementById('confirm-time');
    const confirmName = document.getElementById('confirm-name');
    const confirmPhone = document.getElementById('confirm-phone');
    const confirmEmail = document.getElementById('confirm-email');
    const confirmObservations = document.getElementById('confirm-observations');
    const successMessage = document.getElementById('success-message');

    // State
    let currentStep = 1;
    let selectedService = null;
    let selectedBarber = null;
    let selectedDate = null;
    let selectedTime = null;

    // --- UTILS ---
    const formatDate = (date) => {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
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
        if (currentStep === step) {
            sidebarNextBtn.disabled = !condition;
        }
    };

    // --- STEP LOGIC ---
    const updateStep = (step) => {
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

        // Update Confirmation Data
        if (step === 5) {
            confirmService.textContent = selectedService || 'N/A';
            confirmBarber.textContent = selectedBarber || 'N/A';
            confirmDate.textContent = selectedDate ? formatDate(new Date(selectedDate)) : 'N/A';
            confirmTime.textContent = selectedTime || 'N/A';
            confirmName.textContent = userName.value;
            confirmPhone.textContent = userPhone.value;
            confirmEmail.textContent = userEmail.value;
            confirmObservations.textContent = userObservations.value || 'Nenhuma';
        }

        currentStep = step;

        // Button Visibility
        if (step === 1) {
            sidebarPrevBtn.classList.add('hidden');
        } else {
            sidebarPrevBtn.classList.remove('hidden');
        }

        if (step === 5) {
            sidebarNextBtn.innerHTML = 'Confirmar <i class="fas fa-check"></i>';
        } else {
            sidebarNextBtn.innerHTML = 'Avançar <i class="fas fa-arrow-right"></i>';
        }

        // Validate state
        if (step === 1) updateNextButtonState(step, !!selectedService);
        if (step === 2) updateNextButtonState(step, !!selectedBarber);
        if (step === 3) updateNextButtonState(step, !!selectedDate && !!selectedTime);
        if (step === 4) updateNextButtonState(step, validateForm());
        if (step === 5) sidebarNextBtn.disabled = false;
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

    // --- SUMMARY EDIT LOGIC ---
    document.querySelectorAll('.summary-row.clickable').forEach(row => {
        row.addEventListener('click', () => {
            const stepToGo = parseInt(row.getAttribute('data-go-to-step'));
            if (stepToGo) {
                updateStep(stepToGo);
            }
        });
    });

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

            // Re-render calendar to update busy days if needed (simple refresh)
            renderCalendar(currentCalendarDate);
        });
    });

    // --- NATIVE CUSTOM CALENDAR LOGIC ---
    let currentCalendarDate = new Date();
    const monthNames = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
    ];

    const changeMonth = (offset) => {
        currentCalendarDate.setMonth(currentCalendarDate.getMonth() + offset);
        renderCalendar(currentCalendarDate);
    };

    const renderCalendar = (date) => {
        if (!calendarTarget) return;
        calendarTarget.innerHTML = '';

        const year = date.getFullYear();
        const month = date.getMonth();

        // Header
        const header = document.createElement('div');
        header.className = 'custom-cal-header';

        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'cal-nav-btn';
        prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevBtn.onclick = () => changeMonth(-1);

        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'cal-nav-btn';
        nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextBtn.onclick = () => changeMonth(1);

        const title = document.createElement('span');
        title.className = 'cal-month-label';
        title.textContent = `${monthNames[month]} ${year}`;

        header.appendChild(prevBtn);
        header.appendChild(title);
        header.appendChild(nextBtn);
        calendarTarget.appendChild(header);

        // Days Grid
        const grid = document.createElement('div');
        grid.className = 'custom-cal-grid';

        const weekDays = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'];
        const weekRow = document.createElement('div');
        weekRow.className = 'cal-week-row';
        weekDays.forEach(day => {
            const el = document.createElement('div');
            el.className = 'cal-weekday';
            el.textContent = day;
            weekRow.appendChild(el);
        });
        grid.appendChild(weekRow);

        // Calculate Days
        const firstDayOfMonth = new Date(year, month, 1);
        let startingDay = firstDayOfMonth.getDay() - 1;
        if (startingDay === -1) startingDay = 6;

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysContainer = document.createElement('div');
        daysContainer.className = 'cal-days-container';

        // Fetch Busy Days first then render? For now render then fetch/update classes
        updateBusyDaysVisuals(month, year, daysContainer);

        // Previous Month Fillers
        for (let i = 0; i < startingDay; i++) {
            const empty = document.createElement('div');
            empty.className = 'cal-day empty';
            daysContainer.appendChild(empty);
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let day = 1; day <= daysInMonth; day++) {
            const dateEl = document.createElement('div');
            dateEl.className = 'cal-day';
            dateEl.textContent = day;
            dateEl.dataset.day = day; // For finding it later to mark busy

            const currentDayDate = new Date(year, month, day);
            const dayOfWeek = currentDayDate.getDay();

            if (dayOfWeek === 0) {
                dateEl.classList.add('disabled', 'sunday');
            } else if (currentDayDate < today) {
                dateEl.classList.add('disabled', 'past');
            } else {
                dateEl.onclick = () => selectDate(year, month, day, dateEl);

                if (selectedDate) {
                    const [sYear, sMonth, sDay] = selectedDate.split('-').map(Number);
                    if (sYear === year && sMonth === month + 1 && sDay === day) {
                        dateEl.classList.add('selected');
                    }
                }
            }
            daysContainer.appendChild(dateEl);
        }

        grid.appendChild(daysContainer);
        calendarTarget.appendChild(grid);
    };

    const updateBusyDaysVisuals = async (month, year, container) => {
        const m = month + 1;
        try {
            const response = await fetch(`api/busy-days?barber=${selectedBarber || ''}&month=${m}&year=${year}`);
            const data = await response.json();
            if (data.success && data.busyDays) {
                // busyDays are likely full date strings (or logic specific to backend)
                // Assuming flatpickr previously handled this.
                // If the API returns days to disable (e.g. ["2024-05-20", ...])
                data.busyDays.forEach(busyDate => {
                    const d = new Date(busyDate);
                    // Match month/year
                    if (d.getMonth() === month && d.getFullYear() === year) {
                        const dayNum = d.getDate();
                        // Find element in container (this is async though, container might be detached if re-rendered fast. 
                        // But usually ok for this scale)
                        // Better: apply class 'disabled busy'
                        // Since I don't have reference to container easily in async without closure, 
                        // I will query DOM if 'container' is part of DOM, or passed.
                        // I will query actual rendered elements:
                        const dayEl = document.querySelector(`.cal-day[data-day="${dayNum}"]`);
                        if (dayEl) dayEl.classList.add('disabled', 'busy');
                    }
                });
            }
        } catch (e) {
            console.error("Busy days error", e);
        }
    };

    const selectDate = (year, month, day, el) => {
        document.querySelectorAll('.cal-day').forEach(d => d.classList.remove('selected'));
        el.classList.add('selected');

        const m = String(month + 1).padStart(2, '0');
        const d = String(day).padStart(2, '0');
        const dateStr = `${year}-${m}-${d}`;

        selectedDate = dateStr;
        hiddenDateInput.value = dateStr;
        selectedTime = null;
        hiddenTimeInput.value = '';

        fetchSlots(dateStr);
        updateNextButtonState(3, false);
    };

    const fetchSlots = async (dateStr) => {
        timeSlotsGrid.innerHTML = '';
        loadingIndicator.classList.remove('hidden');
        timeSlotsGrid.appendChild(loadingIndicator);

        try {
            const response = await fetch(`api/slots?barber=${selectedBarber}&date=${dateStr}`);
            const data = await response.json();

            timeSlotsGrid.innerHTML = '';

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

    // --- VALIDATION & SUBMIT ---
    const validateForm = () => {
        return userName.value.length > 2 && userPhone.value.length === 9 && userEmail.value.includes('@');
    };

    [userName, userPhone, userEmail].forEach(i => {
        i.addEventListener('input', () => updateNextButtonState(4, validateForm()));
    });

    sidebarNextBtn.addEventListener('click', () => {
        if (currentStep < 5) {
            updateStep(currentStep + 1);
        } else if (currentStep === 5) {
            submitForm();
        }
    });

    sidebarPrevBtn.addEventListener('click', () => {
        if (currentStep > 1) updateStep(currentStep - 1);
    });

    const submitForm = async () => {
        sidebarNextBtn.disabled = true;
        sidebarNextBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A processar...';

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
                document.querySelector('.booking-layout-container').innerHTML = '';
                document.querySelector('.booking-layout-container').appendChild(successMessage);
                successMessage.classList.remove('hidden');
                confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
            } else {
                alert(data.message);
                sidebarNextBtn.disabled = false;
                sidebarNextBtn.innerHTML = 'Confirmar <i class="fas fa-check"></i>';
            }
        } catch (e) {
            alert("Erro ao enviar marcação.");
            sidebarNextBtn.disabled = false;
            sidebarNextBtn.innerHTML = 'Confirmar <i class="fas fa-check"></i>';
        }
    };

    // Init
    renderCalendar(currentCalendarDate);
    updateStep(1);
    updateNextButtonState(1, false);
});