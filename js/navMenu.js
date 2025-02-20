// Seleciona o ícone do hamburger e o menu
const hamburger = document.querySelector('.hamburger');
const menu = document.querySelector('nav ul');

// Adiciona um ouvinte de evento ao ícone do hamburger
hamburger.addEventListener('click', () => {
    // Alternar a classe 'show' no menu
    menu.classList.toggle('show');
    // Alternar a classe 'active' no hamburger
    hamburger.classList.toggle('active');
});