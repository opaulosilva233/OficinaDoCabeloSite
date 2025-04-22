/**
 * darkMode.js
 * Este ficheiro contém a lógica para o modo escuro da aplicação.
 */

document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const darkModeButton = document.getElementById('dark-mode-toggle');

    // Verifica se o tema está guardado no LocalStorage
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark-mode') {
        body.classList.add('dark-mode');
    }

    // Adiciona um event listener para o botão
    if (darkModeButton) {
        darkModeButton.addEventListener('click', toggleDarkMode);
    }

    function toggleDarkMode() {

        // Toggle na classe dark-mode no body
        body.classList.toggle('dark-mode');

        // Guarda o estado do tema no LocalStorage
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark-mode');
        } else {
            localStorage.removeItem('theme');
        }
    }
});