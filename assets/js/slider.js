document.addEventListener("DOMContentLoaded", () => {
    let currentSlide = 0;
    const slides = document.querySelectorAll(".slide");
    const slideTexts = document.querySelectorAll(".text");
    const totalSlides = slides.length;
    const sliderContainer = document.querySelector(".slider");

    // Cria um contentor para os pontos de navegação
    const dotsContainer = document.createElement("div");
    dotsContainer.classList.add("dots-container");
    sliderContainer.appendChild(dotsContainer);

    // Cria os pontos de navegação
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement("span");
        dot.classList.add("dot");
        dot.addEventListener("click", () => {
            goToSlide(i);
        });
        dotsContainer.appendChild(dot);
    }
    const dots = document.querySelectorAll(".dot");

    // Função para exibir o slide específico
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove("active");
        });
        slides[index].classList.add("active");

        dots.forEach((dot, i) => {
            dot.classList.remove("active");
        });
        dots[index].classList.add("active");

        slideTexts.forEach((text, i) => {
            text.classList.remove("active");
        });
        // Adiciona um pequeno atraso para o texto aparecer
        setTimeout(() => {
            slideTexts[index].classList.add("active");
        }, 300); // Atraso de 300ms para o texto
    }

    // Função para ir para um slide específico
    function goToSlide(index) {
        currentSlide = index;
        showSlide(currentSlide);
    }

    // Função para avançar para o próximo slide
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    // Função para voltar ao slide anterior
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    }

    // Inicializa o slider mostrando o primeiro slide
    showSlide(currentSlide);

    // Troca de slide automaticamente a cada 6 segundos
    setInterval(() => {
        nextSlide();
    }, 6000);

    // Adiciona botões de navegação manual
    const prevButton = document.createElement("button");
    prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>'; // Ícone do Font Awesome
    prevButton.classList.add("slider-button", "prev");
    prevButton.addEventListener("click", prevSlide);

    const nextButton = document.createElement("button");
    nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>'; // Ícone do Font Awesome
    nextButton.classList.add("slider-button", "next");
    nextButton.addEventListener("click", nextSlide);

    // Insere os botões na página
    sliderContainer.appendChild(prevButton);
    sliderContainer.appendChild(nextButton);
});