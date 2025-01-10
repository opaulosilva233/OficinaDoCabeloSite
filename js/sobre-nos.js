// js/sobre-nos.js
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("barber-modal");
    const modalContent = modal.querySelector(".modal-text");
    const closeBtn = modal.querySelector(".close");

    // Dados dos barbeiros
    const barbersInfo = {
        barber1: {
            name: "Bruno Martins",
            description: "João é um barbeiro experiente com mais de 10 anos no mercado. Especialista em cortes modernos e barbas personalizadas.",
        },
        barber2: {
            name: "Hugo Alves",
            description: "Pedro é conhecido por sua habilidade em criar estilos clássicos e modernos. Um profissional dedicado e detalhista.",
        },
    };

    // Abre o modal com informações
    document.querySelectorAll(".barber-link").forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const barberKey = link.dataset.barber;
            if (barbersInfo[barberKey]) {
                const { name, description } = barbersInfo[barberKey];
                modalContent.innerHTML = `<h2>${name}</h2><p>${description}</p>`;
                modal.style.display = "flex";
            }
        });
    });

    // Fecha o modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Fecha o modal ao clicar fora do conteúdo
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});
