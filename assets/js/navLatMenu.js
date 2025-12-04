document.addEventListener("DOMContentLoaded", function () {
    // Destacar o link ativo com base na URL atual
    const currentPath = window.location.pathname.split('/').pop();
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const logoutLink = document.querySelector('.logout-link');
    const logoutModal = document.getElementById('logoutModal');
    const modalContent = logoutModal.querySelector('.modal-content');
    const confirmLogout = document.getElementById('confirmLogout');
    const cancelLogout = document.getElementById('cancelLogout');

    // Função para abrir o modal
    function openModal() {
        // Resetar qualquer animação anterior
        modalContent.classList.remove('closing');
        modalContent.style.animation = 'none'; // Remove a animação atual
        modalContent.offsetHeight; // Força o navegador a recalcular (reinicia a animação)
        modalContent.style.animation = null; // Permite que a animação padrão seja reaplicada
        logoutModal.classList.add('active');
    }

    // Função para fechar o modal com animação
    function closeModal() {
        modalContent.classList.add('closing');
        modalContent.addEventListener('animationend', function () {
            logoutModal.classList.remove('active');
        }, { once: true });
    }

    sidebarLinks.forEach(link => {
        const linkPath = link.getAttribute('href').split('/').pop();
        if (linkPath === currentPath) {
            link.classList.add('active');
        }

        // Adicionar indicador de carregamento ao clicar
        link.addEventListener('click', function (e) {
            // Para o link "Terminar Sessão", abrir o modal
            if (link.classList.contains('logout-link')) {
                e.preventDefault();
                openModal();
                return;
            }

            // Mostrar indicador de carregamento para outros links
            link.classList.add('loading');
        });
    });

    // Confirmar logout
    confirmLogout.addEventListener('click', function () {
        logoutLink.classList.add('loading');
        window.location.href = logoutLink.getAttribute('href');
    });

    // Cancelar logout
    cancelLogout.addEventListener('click', function () {
        closeModal();
    });

    // Fechar o modal ao clicar no overlay
    logoutModal.addEventListener('click', function (e) {
        if (e.target === logoutModal) {
            closeModal();
        }
    });

    // Fechar o modal com a tecla Esc
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && logoutModal.classList.contains('active')) {
            closeModal();
        }
    });
});