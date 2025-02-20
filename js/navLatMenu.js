document.addEventListener("DOMContentLoaded", function () {
    const hamburger = document.getElementById("hamburger");
    const sidebar = document.getElementById("menu");

    hamburger.addEventListener("click", function () {
        sidebar.classList.toggle("active"); // Exibe/esconde a sidebar
        hamburger.classList.toggle("active"); // Animação do botão
    });

    // Garantir que a sidebar fica sempre visível no desktop ao redimensionar a janela
    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) {
            sidebar.classList.remove("active"); // Remove classe active no desktop
        }
    });
});
