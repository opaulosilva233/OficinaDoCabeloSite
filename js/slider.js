document.addEventListener("DOMContentLoaded", () => {
    let currentSlide = 0;
    const slides = document.querySelectorAll(".slide");
    const totalSlides = slides.length;

    // Função para exibir o slide específico
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.display = "none"; // Oculta todos os slides
            slide.classList.remove("active"); // Remove a classe 'active' de todos os slides
        });
        slides[index].style.display = "block"; // Exibe o slide atual
        slides[index].classList.add("active"); // Adiciona a classe 'active' ao slide atual
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

    // Troca de slide automaticamente a cada 5 segundos
    setInterval(nextSlide, 5000);

    // Adiciona botões de navegação manual (opcional)
    const prevButton = document.createElement("button");
    prevButton.textContent = "❮"; // Símbolo de seta esquerda
    prevButton.classList.add("slider-button", "prev");
    prevButton.addEventListener("click", prevSlide);

    const nextButton = document.createElement("button");
    nextButton.textContent = "❯"; // Símbolo de seta direita
    nextButton.classList.add("slider-button", "next");
    nextButton.addEventListener("click", nextSlide);

    // Insere os botões na página
    const sliderContainer = document.querySelector(".slider");
    sliderContainer.appendChild(prevButton);
    sliderContainer.appendChild(nextButton);
}); 