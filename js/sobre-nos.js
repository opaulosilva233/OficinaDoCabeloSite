document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("barber-modal");
    const modalContent = modal.querySelector(".modal-text");
    const closeBtn = modal.querySelector(".close");

    // Dados dos barbeiros com links do Instagram
    const barbersInfo = {
        barber1: {
            name: "Bruno Martins",
            description: "Bruno é um barbeiro talentoso com mais de 8 anos de experiência. Especialista em cortes modernos e cuidados com a barba, ele transforma cada visita em uma experiência única.",
            instagram: "https://www.instagram.com/brunomartins_barber", // Link do Instagram
            image: "/img/BBarber.png", // Imagem do barbeiro
        },
        barber2: {
            name: "Hugo Alves",
            description: "Hugo é conhecido por sua criatividade e atenção aos detalhes. Com habilidades que combinam técnicas clássicas e modernas, ele garante resultados impecáveis para seus clientes.",
            instagram: "https://www.instagram.com/hugoalves_barber", // Link do Instagram
            image: "/img/HBarber.png", // Imagem do barbeiro
        },
    };

    // Abre o modal com informações
    document.querySelectorAll(".barber-link").forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const barberKey = link.dataset.barber;
            if (barbersInfo[barberKey]) {
                const { name, description, instagram, image } = barbersInfo[barberKey];
                modalContent.innerHTML = `
                    <h2>${name}</h2>
                    <p>${description}</p>
                    <a href="${instagram}" target="_blank" rel="noopener noreferrer">
                        <img src="${image}" alt="${name}" style="width: 150px; border-radius: 15px; margin-top: 20px; cursor: pointer;">
                    </a>
                `;
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