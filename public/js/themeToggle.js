document.addEventListener('DOMContentLoaded', function() {
    // Buscar si ya existe un botón de cambio de tema
    let button = document.querySelector('.theme-toggle');

    // Crear el botón solo si no existe
    if (!button) {
        button = document.createElement('button');
        button.className = 'theme-toggle';
        button.setAttribute('aria-label', 'Toggle dark mode');
        document.body.appendChild(button);
    }

    // Check for saved theme preference or use system preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        button.innerHTML = savedTheme === 'dark' ? '☀️' : '🌙';
    } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.setAttribute('data-theme', 'dark');
        button.innerHTML = '☀️';
        localStorage.setItem('theme', 'dark');
    } else {
        button.innerHTML = '🌙';  // Valor predeterminado para modo claro
    }

    // Toggle theme function
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        button.innerHTML = newTheme === 'dark' ? '☀️' : '🌙';
    }

    // Add click event listener
    button.addEventListener('click', toggleTheme);
});