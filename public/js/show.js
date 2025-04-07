document.addEventListener('DOMContentLoaded', function() {
    const ITEMS_TO_SHOW = 4;

    // Inicializar todas las funcionalidades
    initializeEquipmentSections();
    initializeAlerts();
    initializeImageGalleries();
    initializeLightbox();
    initializeFilters();

    // Función para manejar las secciones de equipos
    function initializeEquipmentSections() {
        document.querySelectorAll('.area-section').forEach(section => {
            if (!section.querySelector('.equipment-grid')) return;
            
            const equipmentGrid = section.querySelector('.equipment-grid');
            const equipmentCards = equipmentGrid.querySelectorAll('.equipment-card');
            
            if (equipmentCards.length > ITEMS_TO_SHOW) {
                const showMoreBtn = createShowMoreButton(equipmentCards.length - ITEMS_TO_SHOW);
                hideExtraCards(equipmentCards);
                addShowMoreButton(equipmentGrid, equipmentCards, showMoreBtn);
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

    function addShowMoreButton(grid, cards, button) {
        setupShowMoreButtonEvents(button, cards);
        grid.parentNode.insertBefore(button, grid.nextSibling);
    }

    function createShowMoreButton(remainingCount) {
        const btn = document.createElement('button');
        btn.className = 'btn-show-more';
        btn.style.display = remainingCount > 0 ? 'flex' : 'none';
        btn.innerHTML = `<span>Ver más</span><span class="equipment-count">(${remainingCount})</span>`;
        return btn;
    }

    function setupShowMoreButtonEvents(button, cards) {
        button.addEventListener('click', function() {
            const isExpanded = this.classList.contains('expanded');
            toggleExtraCards(cards);
            updateShowMoreButton(this, isExpanded, getVisibleCards(cards).length);
        });
    }

    function toggleExtraCards(cards) {
        cards.forEach((card, index) => {
            if (index >= ITEMS_TO_SHOW) {
                card.classList.toggle('hidden');
            }
        });
    }

    function updateShowMoreButton(button, isExpanded, totalVisibleCards) {
        const remainingCards = totalVisibleCards - ITEMS_TO_SHOW;
        if (remainingCards <= 0) {
            button.style.display = 'none';
        } else {
            button.style.display = 'flex';
            button.classList.toggle('expanded');
            button.innerHTML = isExpanded ? 
                `<span>Ver más</span><span class="equipment-count">(${remainingCards})</span>` :
                `<span>Ver menos</span>`;
        }
    }

    function getVisibleCards(cards) {
        return Array.from(cards).filter(card => !card.classList.contains('hidden'));
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
        const equipmentCard = button.closest('.equipment-card');
        const gallery = equipmentCard.querySelector('.images-gallery');
        const isVisible = gallery.classList.contains('active');
        
        if (!isVisible) {
            // Cerrar todas las otras galerías primero
            document.querySelectorAll('.equipment-card').forEach(card => {
                if (card !== equipmentCard) {
                    const otherGallery = card.querySelector('.images-gallery');
                    const otherButton = card.querySelector('.more-images');
                    if (otherGallery && otherGallery.classList.contains('active')) {
                        otherGallery.classList.remove('active');
                        otherGallery.style.display = 'none';
                        if (otherButton) {
                            otherButton.textContent = '+' + otherButton.dataset.total;
                        }
                    }
                }
            });
        }
        
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

    // Función para inicializar los filtros
    function initializeFilters() {
        document.querySelectorAll('.area-section').forEach(section => {
            const filterButtons = section.querySelectorAll('.filter-btn');
            if (!filterButtons.length) return;

            const equipmentGrid = section.querySelector('.equipment-grid');
            const equipmentCards = equipmentGrid.querySelectorAll('.equipment-card');
            const showMoreBtn = section.querySelector('.btn-show-more');
            const filterMessage = section.querySelector('.no-equipment-message.filter-message');
            const resetFilterBtn = section.querySelector('.reset-filter');

            // Agregar evento al botón de reinicio
            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', () => {
                    // Encontrar el botón "Todos" y activarlo
                    const allButton = section.querySelector('.filter-btn[data-type="all"]');
                    if (allButton) {
                        allButton.click();
                    }
                });
            }

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const type = button.dataset.type;
                    
                    // Actualizar estado activo de los botones
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    // Filtrar las tarjetas y reiniciar su visibilidad
                    let visibleCount = 0;
                    equipmentCards.forEach(card => {
                        card.classList.remove('hidden');
                        if (type === 'all' || card.dataset.type === type) {
                            card.style.display = '';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Gestionar mensajes y botón ver más
                    if (visibleCount === 0) {
                        if (filterMessage) {
                            filterMessage.style.display = 'block';
                            equipmentGrid.style.display = 'block';
                            equipmentGrid.style.gridTemplateColumns = '1fr';
                        }
                        if (showMoreBtn) showMoreBtn.style.display = 'none';
                    } else {
                        if (filterMessage) {
                            filterMessage.style.display = 'none';
                            equipmentGrid.style.gridTemplateColumns = '';
                        }
                        
                        if (visibleCount > ITEMS_TO_SHOW) {
                            const visibleCards = Array.from(equipmentCards).filter(card => card.style.display !== 'none');
                            hideExtraCards(visibleCards);
                            if (showMoreBtn) {
                                showMoreBtn.style.display = 'flex';
                                showMoreBtn.classList.remove('expanded');
                                showMoreBtn.innerHTML = `<span>Ver más</span><span class="equipment-count">(${visibleCount - ITEMS_TO_SHOW})</span>`;
                            }
                        } else if (showMoreBtn) {
                            showMoreBtn.style.display = 'none';
                        }
                    }
                });
            });
        });
    }
});