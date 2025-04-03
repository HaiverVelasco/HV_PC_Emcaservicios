document.addEventListener('DOMContentLoaded', function() {
    const ITEMS_TO_SHOW = 4;

    // Inicializar todas las funcionalidades
    initializeEquipmentSections();
    initializeAlerts();
    initializeImageGalleries();
    initializeLightbox();

    // Función para manejar las secciones de equipos
    function initializeEquipmentSections() {
        document.querySelectorAll('.area-section').forEach(section => {
            const equipmentGrid = section.querySelector('.equipment-grid');
            const equipmentCards = equipmentGrid.querySelectorAll('.equipment-card');
            
            if (equipmentCards.length > ITEMS_TO_SHOW) {
                hideExtraCards(equipmentCards);
                addShowMoreButton(equipmentGrid, equipmentCards);
            }
        });
    }

    function hideExtraCards(cards) {
        cards.forEach((card, index) => {
            if (index >= ITEMS_TO_SHOW) {
                card.classList.add('hidden');
            }
        });
    }

    function addShowMoreButton(grid, cards) {
        const showMoreBtn = createShowMoreButton(cards.length - ITEMS_TO_SHOW);
        setupShowMoreButtonEvents(showMoreBtn, cards);
        grid.parentNode.insertBefore(showMoreBtn, grid.nextSibling);
    }

    function createShowMoreButton(remainingCount) {
        const btn = document.createElement('button');
        btn.className = 'btn-show-more';
        btn.innerHTML = `<span>Ver más</span><span class="equipment-count">(${remainingCount})</span>`;
        return btn;
    }

    function setupShowMoreButtonEvents(button, cards) {
        button.addEventListener('click', function() {
            const isExpanded = this.classList.contains('expanded');
            toggleExtraCards(cards);
            updateShowMoreButton(this, isExpanded, cards.length);
        });
    }

    function toggleExtraCards(cards) {
        cards.forEach((card, index) => {
            if (index >= ITEMS_TO_SHOW) {
                card.classList.toggle('hidden');
            }
        });
    }

    function updateShowMoreButton(button, isExpanded, totalCards) {
        button.classList.toggle('expanded');
        button.innerHTML = isExpanded ? 
            `<span>Ver más</span><span class="equipment-count">(${totalCards - ITEMS_TO_SHOW})</span>` :
            `<span>Ver menos</span>`;
    }

    // Funciones para manejar las alertas
    function initializeAlerts() {
        setupAlertCloseButtons();
        setupAutoCloseAlerts();
    }

    function setupAlertCloseButtons() {
        document.querySelectorAll('.alert-close').forEach(button => {
            button.addEventListener('click', () => closeAlert(button.parentElement));
        });
    }

    function setupAutoCloseAlerts() {
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => alert && closeAlert(alert), 5000);
        });
    }

    function closeAlert(element) {
        element.style.animation = 'fadeOut 0.5s ease-out';
        setTimeout(() => element.remove(), 500);
    }

    // Funciones para manejar las galerías de imágenes
    function initializeImageGalleries() {
        document.querySelectorAll('.more-images').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const equipmentId = this.closest('.equipment-card').dataset.equipmentId;
                toggleGalleryVisibility(this, equipmentId);
            });
        });
    }

    function toggleGalleryVisibility(button, equipmentId) {
        const equipmentCard = button.closest('.equipment-images');
        const gallery = equipmentCard.querySelector('.images-gallery');
        const isVisible = gallery.classList.contains('active');
        
        // Cerrar todas las otras galerías primero
        document.querySelectorAll('.images-gallery').forEach(g => {
            if (g !== gallery) {
                g.classList.remove('active');
                g.style.display = 'none';
                
                // Resetear el texto del botón correspondiente
                const parentImages = g.closest('.equipment-images');
                const otherButton = parentImages.querySelector('.more-images');
                if (otherButton) {
                    otherButton.textContent = '+' + otherButton.dataset.total;
                }
            }
        });
        
        // Alternar la galería actual
        gallery.classList.toggle('active');
        gallery.style.display = isVisible ? 'none' : 'grid';
        button.textContent = isVisible ? '+' + button.dataset.total : 'Ver Menos';
    }

    // Configuración del lightbox
    function initializeLightbox() {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': "Imagen %1 de %2",
            'fadeDuration': 300,
            'imageFadeDuration': 300
        });
    }
});