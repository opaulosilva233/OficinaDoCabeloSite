let currentPage = 1;
let currentLimit = 10;
let currentSearch = '';
let currentStatus = '';
let currentDateStart = '';
let currentDateEnd = '';
let debounceTimer;

document.addEventListener('DOMContentLoaded', () => {
    fetchAppointments();

    // Event Listeners
    document.getElementById('prevPage').addEventListener('click', () => changePage(-1));
    document.getElementById('nextPage').addEventListener('click', () => changePage(1));

    document.getElementById('searchInput').addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            currentSearch = e.target.value.trim();
            currentPage = 1;
            fetchAppointments();
        }, 500);
    });

    // Filter Listeners
    document.getElementById('statusFilter').addEventListener('change', (e) => {
        currentStatus = e.target.value;
        currentPage = 1;
        fetchAppointments();
    });

    document.getElementById('dateStart').addEventListener('change', (e) => {
        currentDateStart = e.target.value;
        currentPage = 1;
        fetchAppointments();
    });

    document.getElementById('dateEnd').addEventListener('change', (e) => {
        currentDateEnd = e.target.value;
        currentPage = 1;
        fetchAppointments();
    });

    document.getElementById('refreshBtn').addEventListener('click', () => {
        // Clear all filters
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('dateStart').value = '';
        document.getElementById('dateEnd').value = '';

        currentSearch = '';
        currentStatus = '';
        currentDateStart = '';
        currentDateEnd = '';
        currentPage = 1;

        fetchAppointments();
    });
});

async function fetchAppointments() {
    const tableBody = document.querySelector('#appointmentsTable tbody');
    const loadingRow = `<tr><td colspan="6" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">A carregar...</p></td></tr>`;
    tableBody.innerHTML = loadingRow;

    try {
        const url = `index.php?route=api/appointments&page=${currentPage}&limit=${currentLimit}&search=${encodeURIComponent(currentSearch)}&status=${encodeURIComponent(currentStatus)}&date_start=${encodeURIComponent(currentDateStart)}&date_end=${encodeURIComponent(currentDateEnd)}`;
        const response = await fetch(url);
        const data = await response.json();

        if (data.success) {
            renderTable(data.data);
            updatePagination(data.pagination);
        } else {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erro ao carregar dados: ${data.error}</td></tr>`;
        }
    } catch (error) {
        console.error('Error:', error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erro de conexão. Tente novamente.</td></tr>`;
    }
}

function renderTable(appointments) {
    const tableBody = document.querySelector('#appointmentsTable tbody');
    tableBody.innerHTML = '';

    if (appointments.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-5"><i class="fas fa-inbox fa-2x"></i><p class="mt-2">Nenhuma marcação encontrada.</p></td></tr>`;
        return;
    }

    appointments.forEach(appt => {
        const row = document.createElement('tr');
        const statusClass = appt.estado.toLowerCase().replace('á', 'a').replace('í', 'i'); // Normalize

        row.innerHTML = `
            <td>
                <div style="font-weight: 500;">${appt.nome_utilizador || 'N/A'}</div>
                <div style="font-size: 0.8rem; color: #888;">${appt.telefone_utilizador || 'N/A'}</div>
            </td>
            <td>${appt.servico}</td>
            <td>${appt.barbeiro.split(' ')[0]}</td>
            <td>
                <div>${formatDate(appt.data_marcacao)}</div>
                <div style="font-size: 0.8rem; color: #888;">${appt.horario_marcacao.substring(0, 5)}</div>
            </td>
            <td><span class="status-badge ${statusClass}">${appt.estado}</span></td>
            <td class="table-actions">
                <button onclick="openModal(${JSON.stringify(appt).replace(/"/g, '&quot;')})" title="Ver Detalhes">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function updatePagination(pagination) {
    document.getElementById('currentPageDisplay').innerText = `Pag. ${pagination.current}`;
    document.getElementById('prevPage').disabled = pagination.current <= 1;
    document.getElementById('nextPage').disabled = pagination.current >= pagination.pages;

    // Update "Showing X to Y of Z"
    const start = (pagination.current - 1) * pagination.limit + 1;
    const end = Math.min(pagination.current * pagination.limit, pagination.total);

    document.getElementById('showingStart').innerText = pagination.total === 0 ? 0 : start;
    document.getElementById('showingEnd').innerText = end;
    document.getElementById('totalItems').innerText = pagination.total;
}

function changePage(delta) {
    currentPage += delta;
    fetchAppointments();
}

function formatDate(dateString) {
    const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('pt-PT', options);
}

// Modal Functions
async function openModal(appt) {
    const modal = document.getElementById('detailsModal');
    const overlay = document.querySelector('.modal-overlay');
    const modalBody = document.getElementById('modalBody');

    // Show loading state first
    modalBody.innerHTML = `
        <div class="loading-modal">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>A carregar detalhes...</p>
        </div>
    `;
    modal.classList.add('show');
    overlay.classList.add('show');

    try {
        // Fetch full details + history
        const response = await fetch(`index.php?route=api/appointment-details&id=${appt.id}`);
        const result = await response.json();

        if (!result.success) {
            throw new Error(result.error);
        }

        const data = result.data;
        const history = result.history || [];

        // Build History HTML
        let historyHtml = '';
        if (history.length > 0) {
            historyHtml = `
                <div class="history-section">
                    <h3><i class="fas fa-history"></i> Histórico Recente</h3>
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Serviço</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${history.map(item => `
                                <tr>
                                    <td>${formatDate(item.data_marcacao)}</td>
                                    <td>${item.servico}</td>
                                    <td><span class="status-dot ${item.estado.toLowerCase()}"></span> ${item.estado}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            historyHtml = `
                <div class="history-section">
                    <h3><i class="fas fa-history"></i> Histórico Recente</h3>
                    <p class="history-empty">Nenhuma marcação anterior encontrada.</p>
                </div>
            `;
        }

        // Render Content
        modalBody.innerHTML = `
            <div class="detail-row"><span class="detail-label">ID:</span> <span class="detail-value text-muted">#${data.id}</span></div>
            <div class="detail-row"><span class="detail-label">Cliente:</span> <span class="detail-value highlight-text">${data.nome_utilizador}</span></div>
            <div class="detail-row"><span class="detail-label">Telefone:</span> <span class="detail-value">${data.telefone_utilizador}</span></div>
            <div class="detail-row"><span class="detail-label">Email:</span> <span class="detail-value">${data.email_utilizador || '-'}</span></div>
            
            <hr class="modal-divider">
            
            <div class="detail-row"><span class="detail-label">Serviço:</span> <span class="detail-value">${data.servico}</span></div>
            <div class="detail-row"><span class="detail-label">Barbeiro:</span> <span class="detail-value">${data.barbeiro}</span></div>
            <div class="detail-row"><span class="detail-label">Data:</span> <span class="detail-value">${formatDate(data.data_marcacao)}</span></div>
            <div class="detail-row"><span class="detail-label">Horário:</span> <span class="detail-value">${data.horario_marcacao.substring(0, 5)}</span></div>
            <div class="detail-row"><span class="detail-label">Estado:</span> <span class="detail-value"><span class="status-badge ${data.estado.toLowerCase()}">${data.estado}</span></span></div>
            
            ${data.observacoes ? `<div class="detail-row observations"><span class="detail-label">Observações:</span> <p>${data.observacoes}</p></div>` : ''}

            ${historyHtml}
        `;

    } catch (error) {
        console.error("Erro ao carregar detalhes:", error);
        modalBody.innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <p>Erro ao carregar detalhes. Tente novamente.</p>
            </div>
        `;
    }
}

function closeModal() {
    document.getElementById('detailsModal').classList.remove('show');
    document.querySelector('.modal-overlay').classList.remove('show');
}