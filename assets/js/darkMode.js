/**
 * darkMode.js
 * Este ficheiro contém a lógica para o modo escuro da aplicação.
 */

document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const darkModeSwitch = document.getElementById('darkModeSwitch');

    // Verifica se o tema está guardado no LocalStorage
    const currentTheme = localStorage.getItem('theme');

    // Se o tema guardado for 'dark-mode', aplica-o e marca o checkbox
    if (currentTheme === 'dark-mode') {
        body.classList.add('dark-mode');
        if (darkModeSwitch) {
            darkModeSwitch.checked = true;
        }
    }
    // Se não houver tema guardado ou for diferente de 'dark-mode', assume light mode (padrão)

    // Adiciona um event listener para o switch (evento 'change' para checkboxes)
    if (darkModeSwitch) {
        darkModeSwitch.addEventListener('change', function () {
            if (this.checked) {
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark-mode');
            } else {
                body.classList.remove('dark-mode');
                localStorage.removeItem('theme');
            }
        });
    }
});