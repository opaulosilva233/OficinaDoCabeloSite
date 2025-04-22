// Seleciona o ícone do hamburger e o menu
const hamburger = document.querySelector('.hamburger');
const menu = document.querySelector('nav ul');
const main = document.querySelector('main');

// Adiciona um ouvinte de evento ao ícone do hamburger
hamburger.addEventListener('click', () => {
    // Alterna a classe 'show' no menu para exibir/ocultar
    menu.classList.toggle('show');
    // Alterna a classe 'active' no ícone do hamburger para animação
    hamburger.classList.toggle('active');

    main.classList.toggle('menu-open');
});