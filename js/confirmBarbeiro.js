let currentId = null;
let currentEstado = null;

/**
 * Abre o modal de confirmação.
 * @param {number} id - O ID da marcação.
 * @param {string} estado - O estado a ser aplicado ("concluída" ou "cancelada").
 */
function abrirModal(id, estado) {
    currentId = id;
    currentEstado = estado;
    document.getElementById('modal-message').innerText = `Tem certeza que deseja marcar esta marcação como ${estado}?`;
    document.getElementById('myModal').style.display = 'block';
}

/**
 * Fecha o modal.
 */
function fecharModal() {
    document.getElementById('myModal').style.display = 'none';
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