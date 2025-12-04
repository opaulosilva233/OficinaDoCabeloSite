document.addEventListener("DOMContentLoaded", () => {
    const barberLinks = document.querySelectorAll(".barber-link");
    const modal = document.getElementById("barber-modal");
    const closeModal = document.querySelector(".modal-content .close");
    const modalImage = document.querySelector(".modal-image");
    const modalTitle = document.querySelector(".modal-title");
    const modalDescription = document.querySelector(".modal-description");

    const barbersData = {
        barber1: {
            name: "Bruno Martins",
            image: "./img/BBarber.png",
            description: "Bruno Martins é especialista em cortes modernos e tem mais de 10 anos de experiência na arte de barbear."
        },
        barber2: {
            name: "Hugo Alves",
            image: "./img/HBarber.png",
            description: "Hugo Alves é apaixonado por barbas clássicas e cria looks únicos para cada cliente."
        }
    };

    barberLinks.forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const barberId = link.getAttribute("data-barber");
            const barber = barbersData[barberId];

            modalImage.src = barber.image;
            modalTitle.textContent = barber.name;
            modalDescription.textContent = barber.description;
            modal.style.display = "flex";
        });
    });

    closeModal.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});