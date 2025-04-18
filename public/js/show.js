document.addEventListener('DOMContentLoaded', function() {
    const ITEMS_TO_SHOW = 4;

    // Inicializar todas las funcionalidades
    initializeEquipmentSections();
    initializeAlerts();
    initializeImageGalleries();
    initializeLightbox();
    initializeFilters();
    initializeSearch();

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

            // Reset filter button event
            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', () => {
                    const allButton = section.querySelector('.filter-btn[data-type="all"]');
                    if (allButton) allButton.click();
                });
            }

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const type = button.dataset.type;
                    
                    // Update active state of buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    // Filter cards
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

                    // Manage messages and show more button
                    if (visibleCount === 0) {
                        if (filterMessage) {
                            filterMessage.style.display = 'block';
                            // Mantener el grid normal, solo ocultar las cards
                            equipmentGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
                        }
                        if (showMoreBtn) showMoreBtn.style.display = 'none';
                    } else {
                        if (filterMessage) {
                            filterMessage.style.display = 'none';
                        }
                        equipmentGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
                        
                        if (visibleCount > ITEMS_TO_SHOW) {
                            const visibleCards = Array.from(equipmentCards)
                                .filter(card => card.style.display !== 'none');
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

    // Función para inicializar la búsqueda
    function initializeSearch() {
        const searchInput = document.getElementById('searchEquipment');
        const searchResults = document.querySelector('.search-results');
        if (!searchInput || !searchResults) return;

        // Array para almacenar búsquedas recientes
        let recentSearches = JSON.parse(localStorage.getItem('recentSearches') || '[]');
        let selectedIndex = -1;

        // Función para calcular la puntuación de relevancia
        function calculateRelevanceScore(text, searchTerm) {
            const normalizedText = text.toLowerCase();
            const normalizedSearch = searchTerm.toLowerCase();
            
            // Coincidencia exacta tiene el mayor peso
            if (normalizedText === normalizedSearch) return 100;
            
            // Coincidencia al inicio tiene más peso
            if (normalizedText.startsWith(normalizedSearch)) return 80;
            
            // Coincidencia de palabras completas tiene peso medio
            if (normalizedText.includes(' ' + normalizedSearch + ' ')) return 60;
            
            // Coincidencia parcial tiene peso bajo
            if (normalizedText.includes(normalizedSearch)) return 40;
            
            // Coincidencia de palabras individuales
            const searchWords = normalizedSearch.split(' ');
            const textWords = normalizedText.split(' ');
            let wordMatchCount = 0;
            
            searchWords.forEach(word => {
                if (textWords.some(w => w.includes(word))) wordMatchCount++;
            });
            
            return (wordMatchCount / searchWords.length) * 30;
        }

        // Función para resaltar términos de búsqueda
        function highlightText(text, searchTerm) {
            if (!searchTerm) return text;
            const words = searchTerm.toLowerCase().split(' ').filter(w => w.length > 0);
            let highlightedText = text;
            
            words.forEach(word => {
                const regex = new RegExp(word, 'gi');
                highlightedText = highlightedText.replace(regex, match => `<mark>${match}</mark>`);
            });
            
            return highlightedText;
        }

        // Función para actualizar búsquedas recientes
        function updateRecentSearches(term) {
            if (!term) return;
            recentSearches = recentSearches.filter(s => s !== term);
            recentSearches.unshift(term);
            recentSearches = recentSearches.slice(0, 3); // Limitar a 3 búsquedas recientes
            localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
        }

        // Función para manejar el botón de limpiar historial
        function handleClearHistory(clearButton, searchResults) {
            let isConfirming = false;
            let confirmationTimeout;

            clearButton.addEventListener('click', (e) => {
                e.stopPropagation();
                
                if (!isConfirming) {
                    // Primera pulsación - pedir confirmación
                    isConfirming = true;
                    clearButton.textContent = '¿Confirmar?';
                    clearButton.classList.add('confirming');
                    
                    // Establecer timeout para reset
                    confirmationTimeout = setTimeout(() => {
                        resetClearButton();
                    }, 3000);
                } else {
                    // Segunda pulsación - limpiar historial
                    clearButton.textContent = '¡Listo!';
                    clearButton.classList.remove('confirming');
                    recentSearches = [];
                    localStorage.removeItem('recentSearches');
                    
                    // Cerrar resultados después de una breve pausa
                    setTimeout(() => {
                        searchResults.style.display = 'none';
                    }, 500);
                }
            });

            // Función para resetear el botón
            function resetClearButton() {
                isConfirming = false;
                clearButton.textContent = '';
                clearButton.classList.remove('confirming');
                clearTimeout(confirmationTimeout);
            }

            // Resetear el botón cuando el mouse sale del área
            clearButton.addEventListener('mouseleave', () => {
                if (isConfirming) {
                    setTimeout(resetClearButton, 1000);
                }
            });
        }

        // Cerrar resultados al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        // Navegación con teclado
        searchInput.addEventListener('keydown', (e) => {
            const items = searchResults.querySelectorAll('.search-result-item');
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0 && items[selectedIndex]) {
                        items[selectedIndex].click();
                    }
                    return;
                case 'Escape':
                    searchResults.style.display = 'none';
                    searchInput.blur();
                    return;
                default:
                    return;
            }
            
            // Actualizar selección visual
            items.forEach((item, index) => {
                item.classList.toggle('selected', index === selectedIndex);
            });
        });

        // Mostrar búsquedas recientes al focusear el input
        searchInput.addEventListener('focus', () => {
            if (!searchInput.value && recentSearches.length > 0) {
                const recentSearchesHtml = `
                    <div class="recent-searches">
                        <div class="recent-searches-header">
                            Búsquedas recientes
                            <button class="clear-history" title="Limpiar historial"></button>
                        </div>
                        ${recentSearches.map(term => `
                            <div class="search-result-item recent-search" data-term="${term}">
                                <div class="result-title">
                                    <span class="search-history-icon">🕒</span>
                                    ${term}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
                searchResults.innerHTML = recentSearchesHtml;
                searchResults.style.display = 'block';

                // Manejar clics en búsquedas recientes
                searchResults.querySelectorAll('.recent-search').forEach(item => {
                    item.addEventListener('click', () => {
                        searchInput.value = item.dataset.term;
                        searchInput.dispatchEvent(new Event('input'));
                    });
                });

                // Inicializar el botón de limpiar historial con la nueva funcionalidad
                const clearButton = searchResults.querySelector('.clear-history');
                if (clearButton) {
                    handleClearHistory(clearButton, searchResults);
                }
            }
        });

        searchInput.addEventListener('input', debounce(function() {
            const searchTerm = this.value.toLowerCase().trim();
            selectedIndex = -1;
            
            if (searchTerm === '') {
                searchResults.style.display = 'none';
                return;
            }

            // Mostrar indicador de carga
            searchResults.innerHTML = '<div class="searching-message">Buscando...</div>';
            searchResults.style.display = 'block';

            let results = [];
            document.querySelectorAll('.area-section').forEach(section => {
                const areaName = section.querySelector('.area-title').textContent.trim();
                const areaColor = section.querySelector('.area-title').style.backgroundColor;
                
                section.querySelectorAll('.equipment-card').forEach(card => {
                    const inventoryCode = card.querySelector('.inventory-code')?.textContent;
                    const equipmentName = card.querySelector('.equipment-body h3')?.textContent;
                    const details = Array.from(card.querySelectorAll('.equipment-body p'))
                        .map(p => p.textContent).join(' ');
                    const searchText = `${inventoryCode} ${equipmentName} ${details}`;

                    const relevanceScore = calculateRelevanceScore(searchText, searchTerm);
                    if (relevanceScore > 0) {
                        if (!card.id) {
                            card.id = `card-${inventoryCode.replace(/\s+/g, '-')}`;
                        }
                        
                        results.push({
                            title: equipmentName,
                            code: inventoryCode,
                            area: areaName,
                            areaColor: areaColor,
                            details: `${card.querySelector('.equipment-body p:nth-child(1)')?.textContent} - ${card.querySelector('.equipment-body p:nth-child(3)')?.textContent}`,
                            cardId: card.id,
                            relevanceScore: relevanceScore
                        });
                    }
                });
            });

            // Ordenar resultados por relevancia
            results.sort((a, b) => b.relevanceScore - a.relevanceScore);

            // Actualizar el dropdown de resultados
            if (results.length > 0) {
                updateRecentSearches(searchTerm);
                
                const resultsHtml = results.map(result => `
                    <div class="search-result-item" data-card-id="${result.cardId}">
                        <div class="result-title">
                            ${highlightText(result.code + ' - ' + result.title, searchTerm)}
                        </div>
                        <div class="result-area" style="color: ${result.areaColor}">
                            ${highlightText(result.area, searchTerm)}
                        </div>
                        <div class="result-details">
                            ${highlightText(result.details, searchTerm)}
                        </div>
                        <div class="result-score" title="Relevancia: ${result.relevanceScore}%">
                            ${'★'.repeat(Math.ceil(result.relevanceScore/20))}
                        </div>
                    </div>
                `).join('');
                
                searchResults.innerHTML = resultsHtml;
                searchResults.style.display = 'block';

                // Agregar event listeners a los resultados
                searchResults.querySelectorAll('.search-result-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const cardId = this.dataset.cardId;
                        const targetCard = document.getElementById(cardId);
                        if (targetCard) {
                            // Ocultar resultados de búsqueda
                            searchResults.style.display = 'none';
                            searchInput.value = '';
                            
                            // Desplazarse al equipo
                            targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            
                            // Resaltar el equipo temporalmente
                            targetCard.classList.add('highlight');
                            setTimeout(() => {
                                targetCard.classList.remove('highlight');
                            }, 2000);
                        }
                    });
                });
            } else {
                searchResults.innerHTML = '<div class="no-results-message">No se encontraron resultados</div>';
            }
        }, 300));
    }

    // Función para desplazarse al equipo seleccionado
    window.scrollToEquipment = function(cardId) {
        const card = document.getElementById(cardId);
        if (!card) return;

        // Asignar ID si no existe
        card.id = cardId;

        // Desplazarse al equipo
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Resaltar el equipo
        card.style.animation = 'highlight 2s';
        setTimeout(() => card.style.animation = '', 2000);
    };

    // Agregar estilos de animación
    const style = document.createElement('style');
    style.textContent = `
        @keyframes highlight {
            0%, 100% { transform: scale(1); box-shadow: var(--card-shadow); }
            50% { transform: scale(1.02); box-shadow: 0 0 20px rgba(39, 111, 183, 0.3); }
        }
    `;
    document.head.appendChild(style);

    // Función debounce para optimizar la búsqueda
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});

// Función para descargar el QR
window.downloadQR = function() {
    const canvas = document.querySelector("#qrcode canvas");
    const image = canvas.toDataURL("image/png");
    const link = document.createElement('a');
    link.download = 'qr-code.png';
    link.href = image;
    link.click();
}

// Función para descargar todos los QR
window.downloadAllQRs = function() {
    // Mostrar mensaje de proceso
    const modal = document.getElementById('bulkQRModal');
    modal.style.display = 'block';
    const statusElement = document.getElementById('bulkQRStatus');
    statusElement.textContent = "Preparando códigos QR...";
    
    // Obtener todos los equipos
    const equipments = [];
    document.querySelectorAll('.equipment-card').forEach(card => {
        const qrButton = card.querySelector('.btn-qr');
        if (qrButton) {
            // Extraer los datos necesarios del botón QR
            const dataString = qrButton.getAttribute('onclick');
            const matches = dataString.match(/generateQR\(\s*'([^']+)',\s*'([^']+)',\s*'([^']+)'\s*\)/);
            
            if (matches && matches.length === 4) {
                equipments.push({
                    id: matches[1],
                    name: matches[2],
                    pdfUrl: matches[3]
                });
            }
        }
    });
    
    statusElement.textContent = `Generando ${equipments.length} códigos QR...`;
    
    // Crear un contenedor temporal invisible para generar todos los QR
    const tempContainer = document.createElement('div');
    tempContainer.style.position = 'absolute';
    tempContainer.style.left = '-9999px';
    tempContainer.style.top = '-9999px';
    document.body.appendChild(tempContainer);
    
    // Crear un ZIP para contener todos los QR
    const JSZip = window.JSZip;
    const zip = new JSZip();
    let processedCount = 0;
    
    // Generar cada QR y añadirlo al ZIP
    equipments.forEach((equipment, index) => {
        const qrDiv = document.createElement('div');
        qrDiv.id = `temp-qr-${index}`;
        tempContainer.appendChild(qrDiv);
        
        new QRCode(qrDiv, {
            text: equipment.pdfUrl,
            width: 256,
            height: 256,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.L
        });
        
        // Esperar a que se genere el QR
        setTimeout(() => {
            const canvas = qrDiv.querySelector('canvas');
            if (canvas) {
                const imageData = canvas.toDataURL("image/png").replace(/^data:image\/(png|jpg);base64,/, "");
                
                // Nombre del archivo formateado con código de inventario si está disponible
                let inventoryCode = '';
                const equipmentCard = document.getElementById(`card-${equipment.id}`) || 
                                    document.querySelector(`.equipment-card[data-card-id="${equipment.id}"]`);
                if (equipmentCard) {
                    inventoryCode = equipmentCard.querySelector('.inventory-code')?.textContent || '';
                    inventoryCode = inventoryCode.replace(/\//g, '-').trim();
                }
                
                // Crear nombre de archivo con código de inventario o ID si está disponible
                const fileName = inventoryCode ? 
                    `QR_${inventoryCode}_${equipment.name.replace(/[^\w\s]/gi, '')}.png` : 
                    `QR_${equipment.id}_${equipment.name.replace(/[^\w\s]/gi, '')}.png`;
                
                zip.file(fileName, imageData, {base64: true});
                
                processedCount++;
                statusElement.textContent = `Procesando... (${processedCount}/${equipments.length})`;
                
                // Cuando todos los QR están listos, descargar el ZIP
                if (processedCount === equipments.length) {
                    zip.generateAsync({type: "blob"}).then(function(content) {
                        statusElement.textContent = "¡Descarga lista!";
                        
                        const zipLink = document.createElement('a');
                        zipLink.href = URL.createObjectURL(content);
                        zipLink.download = "todos_los_qr_equipos.zip";
                        document.body.appendChild(zipLink);
                        zipLink.click();
                        document.body.removeChild(zipLink);
                        
                        // Limpiar
                        setTimeout(() => {
                            document.body.removeChild(tempContainer);
                            modal.style.display = 'none';
                        }, 2000);
                    });
                }
            }
        }, 100 * index); // Pequeño retraso para evitar problemas de renderizado
    });
}

// Función para descargar todos los QR de un área específica
window.downloadAreaQRs = function(areaId, areaName) {
    // Mostrar mensaje de proceso
    const modal = document.getElementById('bulkQRModal');
    modal.style.display = 'block';
    const statusElement = document.getElementById('bulkQRStatus');
    statusElement.textContent = `Preparando códigos QR para ${areaName}...`;
    
    // Obtener todos los equipos del área específica de forma más simple y compatible
    const equipments = [];
    
    // Método compatible: buscar por el botón que contiene el ID del área
    const downloadButton = document.querySelector(`button[onclick*="downloadAreaQRs('${areaId}'"]`);
    let areaSection = null;
    
    if (downloadButton) {
        areaSection = downloadButton.closest('.area-section');
    }
    
    // Si no encontramos por el botón, intentamos buscar todas las secciones y comparar
    if (!areaSection) {
        const allSections = document.querySelectorAll('.area-section');
        for (let i = 0; i < allSections.length; i++) {
            const titleElement = allSections[i].querySelector('.area-title');
            if (titleElement && titleElement.textContent.trim().includes(areaName)) {
                areaSection = allSections[i];
                break;
            }
        }
    }
    
    // Si encontramos la sección, recolectar los equipos
    if (areaSection) {
        collectEquipmentsFromSection(areaSection);
    }
    
    function collectEquipmentsFromSection(section) {
        // Buscar todos los botones QR en las tarjetas de equipos de esta sección
        const qrButtons = section.querySelectorAll('.btn-qr');
        qrButtons.forEach(button => {
            const dataString = button.getAttribute('onclick');
            const matches = dataString.match(/generateQR\(\s*'([^']+)',\s*'([^']+)',\s*'([^']+)'\s*\)/);
            
            if (matches && matches.length === 4) {
                // Extraer el código de inventario directamente de la tarjeta actual
                const card = button.closest('.equipment-card');
                let inventoryCode = '';
                if (card) {
                    const inventoryElement = card.querySelector('.inventory-code');
                    if (inventoryElement) {
                        inventoryCode = inventoryElement.textContent.trim();
                    }
                }
                
                equipments.push({
                    id: matches[1],
                    name: matches[2],
                    pdfUrl: matches[3],
                    inventoryCode: inventoryCode
                });
            }
        });
    }
    
    // Verificación simple
    console.log(`Encontrados ${equipments.length} equipos en el área ${areaName}`);
    
    // Si no hay equipos, mostrar mensaje y salir
    if (equipments.length === 0) {
        statusElement.textContent = `No se encontraron equipos para generar QR en el área ${areaName}`;
        setTimeout(() => {
            modal.style.display = 'none';
        }, 3000);
        return;
    }
    
    statusElement.textContent = `Generando ${equipments.length} códigos QR para ${areaName}...`;
    
    // Crear un contenedor temporal invisible para generar todos los QR
    const tempContainer = document.createElement('div');
    tempContainer.style.position = 'absolute';
    tempContainer.style.left = '-9999px';
    tempContainer.style.top = '-9999px';
    document.body.appendChild(tempContainer);
    
    // Crear un ZIP para contener todos los QR
    const JSZip = window.JSZip;
    const zip = new JSZip();
    let processedCount = 0;
    
    // Generar cada QR y añadirlo al ZIP
    equipments.forEach((equipment, index) => {
        const qrDiv = document.createElement('div');
        qrDiv.id = `temp-qr-${index}`;
        tempContainer.appendChild(qrDiv);
        
        new QRCode(qrDiv, {
            text: equipment.pdfUrl,
            width: 256,
            height: 256,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.L
        });
        
        // Esperar a que se genere el QR
        setTimeout(() => {
            const canvas = qrDiv.querySelector('canvas');
            if (canvas) {
                const imageData = canvas.toDataURL("image/png").replace(/^data:image\/(png|jpg);base64,/, "");
                
                // Usar el código de inventario ya extraído en el paso anterior
                const safeInventoryCode = equipment.inventoryCode.replace(/\//g, '-');
                
                // Crear nombre de archivo con código de inventario o ID si está disponible
                const fileName = safeInventoryCode ? 
                    `QR_${safeInventoryCode}_${equipment.name.replace(/[^\w\s]/gi, '')}.png` : 
                    `QR_${equipment.id}_${equipment.name.replace(/[^\w\s]/gi, '')}.png`;
                
                zip.file(fileName, imageData, {base64: true});
                
                processedCount++;
                statusElement.textContent = `Procesando... (${processedCount}/${equipments.length})`;
                
                // Cuando todos los QR están listos, descargar el ZIP
                if (processedCount === equipments.length) {
                    zip.generateAsync({type: "blob"}).then(function(content) {
                        statusElement.textContent = "¡Descarga lista!";
                        
                        const zipLink = document.createElement('a');
                        zipLink.href = URL.createObjectURL(content);
                        
                        // Simplificar el nombre del archivo
                        const date = new Date().toISOString().split('T')[0]; // formato YYYY-MM-DD
                        zipLink.download = `QR_${areaName.replace(/[^\w\s]/gi, '')}_${date}.zip`;
                        
                        document.body.appendChild(zipLink);
                        zipLink.click();
                        document.body.removeChild(zipLink);
                        
                        // Limpiar
                        setTimeout(() => {
                            document.body.removeChild(tempContainer);
                            modal.style.display = 'none';
                        }, 2000);
                    });
                }
            }
        }, 100 * index); // Pequeño retraso para evitar problemas de renderizado
    });
}

// Función auxiliar para obtener el color del área por su ID
function getAreaColor(areaId) {
    // Buscar dinámicamente el color en el DOM
    const areaTitle = document.querySelector(`[onclick*="downloadAreaQRs('${areaId}'"]`);
    if (areaTitle) {
        const parentSection = areaTitle.closest('.area-section');
        if (parentSection) {
            const borderColor = window.getComputedStyle(parentSection).borderColor;
            return borderColor;
        }
    }
    return ''; // Si no se encuentra, devolver cadena vacía
}

// Cerrar modal
document.querySelector('.close-modal')?.addEventListener('click', function() {
    document.getElementById('qrModal').style.display = "none";
});

// Cerrar modal de bulk QR
document.querySelector('.close-bulk-modal')?.addEventListener('click', function() {
    document.getElementById('bulkQRModal').style.display = "none";
});

// Cerrar modal al hacer clic fuera
window.addEventListener('click', function(event) {
    const modal = document.getElementById('qrModal');
    const bulkModal = document.getElementById('bulkQRModal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
    if (event.target === bulkModal) {
        bulkModal.style.display = "none";
    }
});