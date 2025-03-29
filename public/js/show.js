document.addEventListener('DOMContentLoaded', function() {
    // Configurar cantidad inicial de equipos a mostrar
    const ITEMS_TO_SHOW = 4;

    // Inicializar cada sección de área
    document.querySelectorAll('.area-section').forEach(section => {
        const equipmentGrid = section.querySelector('.equipment-grid');
        const equipmentCards = equipmentGrid.querySelectorAll('.equipment-card');
        
        if (equipmentCards.length > ITEMS_TO_SHOW) {
            // Ocultar equipos extras
            equipmentCards.forEach((card, index) => {
                if (index >= ITEMS_TO_SHOW) {
                    card.classList.add('hidden');
                }
            });

            // Crear y agregar botón "Ver más"
            const showMoreBtn = document.createElement('button');
            showMoreBtn.className = 'btn-show-more';
            showMoreBtn.innerHTML = `
                <span>Ver más</span>
                <span class="equipment-count">(${equipmentCards.length - ITEMS_TO_SHOW})</span>
            `;

            // Agregar evento al botón
            showMoreBtn.addEventListener('click', function() {
                const isExpanded = this.classList.contains('expanded');
                equipmentCards.forEach((card, index) => {
                    if (index >= ITEMS_TO_SHOW) {
                        card.classList.toggle('hidden');
                    }
                });
                
                this.classList.toggle('expanded');
                this.innerHTML = isExpanded ? 
                    `<span>Ver más</span><span class="equipment-count">(${equipmentCards.length - ITEMS_TO_SHOW})</span>` :
                    `<span>Ver menos</span>`;
            });

            equipmentGrid.parentNode.insertBefore(showMoreBtn, equipmentGrid.nextSibling);
        }
    });

    // Funcionalidad existente para las alertas
    function closeAlert(element) {
        element.style.animation = 'fadeOut 0.5s ease-out';
        setTimeout(() => {
            element.remove();
        }, 500);
    }

    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            closeAlert(this.parentElement);
        });
    });

    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert) {
                closeAlert(alert);
            }
        }, 5000);
    });
});