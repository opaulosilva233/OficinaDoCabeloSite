document.addEventListener("DOMContentLoaded", function () {
    const hamburger = document.getElementById("hamburger");
    const sidebar = document.getElementById("menu");

    // Adiciona um evento de clique ao ícone "hamburger"
    hamburger.addEventListener("click", function () {
        sidebar.classList.toggle("active"); // Exibe ou esconde a barra lateral
        hamburger.classList.toggle("active"); // Aplica/remova a animação do botão "hamburger"
    });

    // Garantir que a barra lateral fica sempre visível no desktop ao redimensionar a janela
    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) {
            sidebar.classList.remove("active"); // Remove a classe 'active' no desktop
        }
    });
});