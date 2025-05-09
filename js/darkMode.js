/**
 * darkMode.js
 * Este ficheiro contém a lógica para o modo escuro da aplicação.
 */

document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const darkModeToggle = document.getElementById('dark-mode-toggle');

    // Verifica se o tema está guardado no LocalStorage
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        body.classList.add(currentTheme);
    }

    // Adiciona um event listener para o botão
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
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