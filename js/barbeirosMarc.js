// Variáveis globais para armazenar o ID e o estado da marcação selecionada
let currentId = null;
let currentEstado = null;

/**
 * Abre o modal de confirmação.
 * @param {number} id - O ID da marcação.
 * @param {string} estado - O estado a ser aplicado ("concluída" ou "cancelada").
 */
function abrirModal(id, estado) {
    console.log("Abrindo modal para ID:", id, "e estado:", estado); // Depuração

    // Define as variáveis globais com os valores passados
    currentId = id;
    currentEstado = estado;

    // Atualiza a mensagem do modal com base no estado
    const modalMessage = document.getElementById('modal-message');
    if (!modalMessage) {
        console.error("Elemento 'modal-message' não encontrado.");
        return;
    }
    modalMessage.innerText = `Tem certeza que deseja marcar esta marcação como ${estado}?`;

    // Exibe o modal
    const modal = document.getElementById('myModal');
    if (!modal) {
        console.error("Elemento 'myModal' não encontrado.");
        return;
    }

    // Remove classes anteriores e adiciona a classe correspondente ao estado
    const modalContent = modal.querySelector('.modal-content');
    modalContent.classList.remove('concluida', 'cancelada'); // Remove classes antigas
    modalContent.classList.add(estado === 'concluída' ? 'concluida' : 'cancelada'); // Adiciona a classe correta

    // Adiciona um ícone correspondente à ação
    const icon = modal.querySelector('.icon');
    if (icon) {
        icon.innerHTML = estado === 'concluída' ? '✅' : '❌'; // Ícone de check ou X
    }

    modal.classList.add('show'); // Adiciona a classe .show
    modal.style.display = 'block'; // Garante que o modal seja exibido
}

/**
 * Fecha o modal.
 * A única maneira de fechar o modal é clicando no botão "Cancelar".
 */
function fecharModal() {
    const modal = document.getElementById('myModal');
    if (!modal) {
        console.error("Elemento 'myModal' não encontrado.");
        return;
    }
    modal.classList.remove('show'); // Remove a classe .show
    modal.style.display = 'none'; // Oculta o modal
}

/**
 * Confirma a ação e envia o formulário correspondente.
 */
function confirmarAcao() {
    console.log("Confirmando ação para ID:", currentId, "e estado:", currentEstado); // Depuração

    if (currentId && currentEstado) {
        const form = document.getElementById(`form-${currentId}`);
        if (!form) {
            console.error(`Formulário com ID 'form-${currentId}' não encontrado.`);
            return;
        }
        form.querySelector('input[name="estado"]').value = currentEstado;
        form.submit();
    } else {
        console.error("ID ou estado não definidos.");
    }

    fecharModal();
}

/**
 * Função para alternar entre tabelas.
 */
function toggleTables() {
    const futureTable = document.getElementById('futureAppointmentsTable');
    const pastTable = document.getElementById('pastPendingAppointmentsTable');
    const button = document.querySelector('.toggle-button');

    if (!futureTable || !pastTable || !button) {
        console.error("Elementos das tabelas ou botão não encontrados.");
        return;
    }

    if (futureTable.style.display === 'none') {
        futureTable.style.display = 'table';
        pastTable.style.display = 'none';
        button.textContent = 'Ver Marcações Passadas Pendentes';
    } else {
        futureTable.style.display = 'none';
        pastTable.style.display = 'table';
        button.textContent = 'Ver Marcações Futuras';
    }
}

// Função para adicionar funcionalidade de ordenação à tabela
document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("futureAppointmentsTable"); // Altere aqui para o ID correto
    if (!table) {
        console.error("Tabela não encontrada. Verifique o ID 'futureAppointmentsTable'.");
        return;
    }

    const headers = table.querySelectorAll("th");
    let currentSortColumn = null;
    let isAscending = true;

    /**
     * Ordena a tabela com base na coluna clicada.
     * @param {number} columnIndex - Índice da coluna a ser ordenada.
     * @param {boolean} isAscending - Direção da ordenação (true = crescente, false = decrescente).
     */
    function sortTable(columnIndex, isAscending) {
        const tbody = table.querySelector("tbody");
        if (!tbody) {
            console.error("Corpo da tabela (tbody) não encontrado.");
            return;
        }

        const rows = Array.from(tbody.querySelectorAll("tr"));

        // Função para extrair o valor da célula
        const getValue = (row, index) => {
            const cell = row.children[index];
            return cell.innerText.trim();
        };

        // Ordenar as linhas
        rows.sort((a, b) => {
            const aValue = getValue(a, columnIndex);
            const bValue = getValue(b, columnIndex);

            // Verificar se é número ou texto
            const isNumber = !isNaN(aValue) && !isNaN(bValue);
            if (isNumber) {
                return isAscending ? aValue - bValue : bValue - aValue;
            } else {
                return isAscending
                    ? aValue.localeCompare(bValue)
                    : bValue.localeCompare(aValue);
            }
        });

        // Limpar e reordenar as linhas na tabela
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        rows.forEach(row => tbody.appendChild(row));
    }

    // Adicionar event listeners aos cabeçalhos
    headers.forEach((header, index) => {
        if (header.dataset.column) { // Ignorar colunas sem data-column
            header.addEventListener("click", () => {
                const isSameColumn = currentSortColumn === index;
                isAscending = isSameColumn ? !isAscending : true;
                currentSortColumn = index;

                // Remover classe de ordenação de todos os cabeçalhos
                headers.forEach(h => h.classList.remove("sort-asc", "sort-desc"));

                // Adicionar classe de ordenação ao cabeçalho clicado
                header.classList.add(isAscending ? "sort-asc" : "sort-desc");

                // Ordenar a tabela
                sortTable(index, isAscending);
            });
        }
    });
});