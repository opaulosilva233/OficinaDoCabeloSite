document.addEventListener("DOMContentLoaded", () => {
    const barberLinks = document.querySelectorAll(".barber-link");
    const modal = document.getElementById("barber-modal");
    const closeModal = document.querySelector(".modal-content .close");
    const modalImage = document.querySelector(".modal-image");
    const modalTitle = document.querySelector(".modal-title");
    const modalDescription = document.querySelector(".modal-description");

    const modalInstagram = document.querySelector(".instagram-button");

    const barbersData = {
        barber1: {
            name: "Bruno Martins",
            image: "assets/img/BBarber.png",
            description: "Bruno Martins é especialista em cortes modernos e tem mais de 10 anos de experiência na arte de barbear.",
            instagram: "https://www.instagram.com/brunomartins_barber/" // Placeholder
        },
        barber2: {
            name: "Hugo Alves",
            image: "assets/img/HBarber.png",
            description: "Hugo Alves é apaixonado por barbas clássicas e cria looks únicos para cada cliente.",
            instagram: "https://www.instagram.com/hugoalves_barber/" // Placeholder
        }
    };

    barberLinks.forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const barberId = link.getAttribute("data-barber");
            const barber = barbersData[barberId];

            if (barber) {
                modalImage.src = barber.image;
                modalTitle.textContent = barber.name;
                modalDescription.textContent = barber.description;
                modalInstagram.href = barber.instagram || "#";

                modal.style.display = "flex";
                // Small timeout to allow display change to register before ensuring opacity transition
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            }
        });
    });

    if (closeModal) {
        closeModal.addEventListener("click", () => {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = "none";
            }, 300); // Wait for transition
        });
    }

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }
    });
});