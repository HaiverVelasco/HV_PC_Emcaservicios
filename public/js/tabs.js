document.addEventListener('DOMContentLoaded', function() {
    const headerTabs = document.querySelectorAll('.header-tab');
    const sectionContents = document.querySelectorAll('.section-content');

    headerTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remover clase active de todos los tabs y contenidos
            headerTabs.forEach(t => t.classList.remove('active'));
            sectionContents.forEach(content => content.classList.remove('active'));

            // Agregar clase active al tab clickeado
            tab.classList.add('active');

            // Mostrar el contenido correspondiente
            const sectionId = tab.getAttribute('data-section');
            const content = document.getElementById(`${sectionId}-content`);
            if (content) {
                content.classList.add('active');
            }
        });
    });
});
