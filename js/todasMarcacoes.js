let currentPage = 1;
let totalPages = 1;

// Função para buscar marcações
async function fetchAppointments(page) {
    try {
        const searchInput = document.getElementById('searchInput').value;
        const response = await fetch(`/includes/todasMarc.php?page=${page}&search=${searchInput}`);
        
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status}`);
        }

        const data = await response.json();
        const tbody = document.querySelector('#appointmentsTable tbody');
        tbody.innerHTML = ''; // Limpar tabela anterior

        data.data.forEach(appointment => {
            const formattedEstado = capitalizeWords(appointment.estado);
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${appointment.nome}</td>
                <td>${appointment.telefone}</td>
                <td>${appointment.data} ${appointment.horario}</td>
                <td>${formattedEstado}</td>
                <td><button onclick="openModal(${appointment.id})">Ver Mais</button></td>
            `;
            tbody.appendChild(row);
        });

        currentPage = data.currentPage;
        totalPages = data.totalPages;
        document.getElementById('currentPage').textContent = `Página ${currentPage} de ${totalPages}`;
        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage === totalPages;
    } catch (error) {
        console.error("Erro ao buscar marcações:", error);
    }
}

// Função para mudar de página
function changePage(direction) {
    const newPage = currentPage + direction;
    if (newPage >= 1 && newPage <= totalPages) {
        console.log("Mudando para página:", newPage);
        fetchAppointments(newPage);
    }
}

function getStatusIcon(status) {
    const statusMap = {
        'pendente': { icon: 'fa-solid fa-clock', color: '#f7b538' }, // Relógio para pendente
        'concluido': { icon: 'fa-solid fa-check-circle', color: '#4caf50' }, // Check para concluído
        'cancelado': { icon: 'fa-solid fa-times-circle', color: '#f44336' } // X para cancelado
    };

    const defaultStatus = { icon: 'fa-solid fa-question-circle', color: '#9e9e9e' }; // Estado desconhecido
    const selectedStatus = statusMap[status.toLowerCase()] || defaultStatus;

    return `<i class="${selectedStatus.icon}" style="color: ${selectedStatus.color}; font-size: 1.5rem;"></i>`;
}

// Função para abrir o modal
async function openModal(id) {
    try {
        const modal = document.getElementById('detailsModal');
        if (!modal) {
            throw new Error("Modal não encontrado no DOM.");
        }

        const response = await fetch(`./includes/getDetails.php?id=${id}`);
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status}`);
        }

        const data = await response.json();
        const formattedEstado = capitalizeWords(data.estado);

        // Preencher os campos do modal
        const fields = [
            { id: 'modal-id', value: data.id },
            { id: 'modal-nome', value: data.nome },
            { id: 'modal-telefone', value: data.telefone },
            { id: 'modal-email', value: data.email },
            { id: 'modal-barbeiro', value: data.barbeiro },
            { id: 'modal-servico', value: data.servico },
            { id: 'modal-data-horario', value: `${data.data} ${data.horario}` },
            { id: 'modal-estado', value: getStatusIcon(data.estado) }, // Usar ícone em vez de texto
            { id: 'modal-criado-em', value: data.criado_em },
            { id: 'modal-atualizado-em', value: data.atualizado_em },
            { id: 'modal-total-marcacoes', value: data.total_marcacoes }
        ];

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element) {
                element.textContent = field.value;
            }
        });

        // Preencher a tabela de últimas marcações
        const ultimasMarcacoesTable = document.getElementById('ultimas-marcacoes-table');
        if (ultimasMarcacoesTable) {
            ultimasMarcacoesTable.innerHTML = ''; // Limpar tabela anterior

            if (data.ultimas_marcacoes.length > 0) {
                data.ultimas_marcacoes.forEach(marcacao => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${marcacao.data}</td>
                        <td>${marcacao.horario}</td>
                        <td>${marcacao.servico}</td>
                        <td>${marcacao.barbeiro}</td> <!-- Alterado para exibir o barbeiro -->
                    `;
                    ultimasMarcacoesTable.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="4">Nenhuma marcação encontrada.</td>`;
                ultimasMarcacoesTable.appendChild(row);
            }
        }

        modal.classList.add('show');
        modal.style.display = 'flex';
    } catch (error) {
        console.error("Erro ao abrir o modal:", error);
    }
}

// Função para fechar o modal
function closeModal() {
    const modal = document.getElementById('detailsModal');
    if (modal) {
        modal.classList.remove('show'); // Remove a classe 'show'
        modal.style.display = 'none'; // Oculta o modal
    }
}

// Função auxiliar para capitalizar palavras
function capitalizeWords(str) {
    return str.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
}

// Carregar marcações ao iniciar a página
fetchAppointments(1);