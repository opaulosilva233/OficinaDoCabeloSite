// Seleciona o ícone do hamburger e o menu
const hamburger = document.querySelector('.hamburger');
const menu = document.querySelector('nav ul');
const overlay = document.createElement('div');
overlay.classList.add('overlay');
document.body.appendChild(overlay);


// Função para fechar o menu e remover o overlay
function closeMenu() {
    menu.classList.remove('show');
    hamburger.classList.remove('active');
    overlay.style.display = 'none';
}

// Adiciona um ouvinte de evento ao ícone do hamburger
hamburger.addEventListener('click', () => {
    // Alterna a classe 'show' no menu para exibir/ocultar
    menu.classList.toggle('show');
    // Alterna a classe 'active' no ícone do hamburger para animação
    hamburger.classList.toggle('active');

    //mostra o overlay quando o menu é aberto
    if (menu.classList.contains('show')) {
        overlay.style.display = 'block';
    } else {
        overlay.style.display = 'none';
    }
});
menu.addEventListener('click', closeMenu)

});