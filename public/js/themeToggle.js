document.addEventListener('DOMContentLoaded', function() {
    // Create theme toggle button
    const button = document.createElement('button');
    button.className = 'theme-toggle';
    button.innerHTML = '🌙'; // Moon emoji for dark mode
    document.body.appendChild(button);

    // Check for saved theme preference or use system preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        button.innerHTML = savedTheme === 'dark' ? '☀️' : '🌙';
    } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.setAttribute('data-theme', 'dark');
        button.innerHTML = '☀️';
        localStorage.setItem('theme', 'dark');
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