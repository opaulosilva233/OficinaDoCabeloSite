// Variáveis globais para armazenar o ID e o estado da marcação selecionada
let currentId = null;
let currentEstado = null;

/**
 * Abre o modal de confirmação.
 * @param {number} id - O ID da marcação.
 * @param {string} estado - O estado a ser aplicado ("concluída" ou "cancelada").
 */
function abrirModal(id, estado) {
    // Define as variáveis globais com os valores passados
    currentId = id;
    currentEstado = estado;

    // Atualiza a mensagem do modal com base no estado
    const modalMessage = document.getElementById('modal-message');
    modalMessage.innerText = `Tem certeza que deseja marcar esta marcação como ${estado}?`;

    // Exibe o modal
    const modal = document.getElementById('myModal');
    modal.style.display = 'block';
}

/**
 * Fecha o modal.
 */
function fecharModal() {
    const modal = document.getElementById('myModal');
    modal.style.display = 'none';
}

/**
 * Confirma a ação e envia o formulário correspondente.
 */
function confirmarAcao() {
    if (currentId && currentEstado) {
        const form = document.getElementById(`form-${currentId}`);
        form.estado.value = currentEstado;
        form.submit();
    }
    fecharModal();
}

// Função para adicionar funcionalidade de ordenação à tabela
document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("appointmentsTable");
    if (!table) {
        console.error("Tabela não encontrada. Verifique o ID 'appointmentsTable'.");
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