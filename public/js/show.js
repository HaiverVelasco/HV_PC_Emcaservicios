document.addEventListener('DOMContentLoaded', function () {
    const ITEMS_TO_SHOW = 4;

    // Inicialización principal
    initializeEquipmentSections();
    initializeAlerts();
    initializeImageGalleries();
    initializeLightbox();
    initializeFilters();
    initializeSearch();

    // --- SECCIONES DE EQUIPOS ---
    function initializeEquipmentSections() {
        document.querySelectorAll('.area-section').forEach(section => {
            const equipmentGrid = section.querySelector('.equipment-grid');
            if (!equipmentGrid) return;
            const equipmentCards = equipmentGrid.querySelectorAll('.equipment-card');
            if (equipmentCards.length > ITEMS_TO_SHOW) {
                const showMoreBtn = createShowMoreButton(equipmentCards.length - ITEMS_TO_SHOW);
                hideExtraCards(equipmentCards);
                addShowMoreButton(equipmentGrid, equipmentCards, showMoreBtn);
            }
        });
    }

    function hideExtraCards(cards) {
        cards.forEach((card, i) => {
            if (i >= ITEMS_TO_SHOW) card.classList.add('hidden');
        });
    }

    function addShowMoreButton(grid, cards, button) {
        setupShowMoreButtonEvents(button, cards);
        const container = document.createElement('div');
        container.className = 'btn-show-more-container';
        container.appendChild(button);
        grid.parentNode.insertBefore(container, grid.nextSibling);
        Object.assign(button.style, {
            position: 'absolute',
            left: '50%',
            transform: 'translateX(-50%)'
        });
    }

    function createShowMoreButton(remainingCount) {
        const btn = document.createElement('button');
        btn.className = 'btn-show-more';
        btn.style.display = 'flex';
        if (remainingCount <= 0) btn.style.visibility = 'hidden';
        btn.innerHTML = `<span>Ver más</span><span class="equipment-count">(${remainingCount})</span>`;
        return btn;
    }

    function setupShowMoreButtonEvents(button, cards) {
        button.addEventListener('click', function () {
            if (this.classList.contains('animating')) return;
            this.classList.add('animating');
            this.style.transform = "translateX(-50%) scale(0.95)";
            setTimeout(() => { this.style.transform = ""; }, 100);

            const isExpanded = this.classList.contains('expanded');
            if (isExpanded) {
                updateShowMoreButton(this, isExpanded, cards.length);
                setTimeout(() => {
                    toggleExtraCards(cards);
                    setTimeout(() => this.classList.remove('animating'), 400);
                }, 300);
            } else {
                toggleExtraCardsWithAnimation(cards);
                setTimeout(() => updateShowMoreButton(this, isExpanded, cards.length), 50);
                setTimeout(() => {
                    const visibleCards = Array.from(cards).filter(card => !card.classList.contains('hidden'));
                    if (visibleCards.length > ITEMS_TO_SHOW) {
                        visibleCards[ITEMS_TO_SHOW].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    setTimeout(() => this.classList.remove('animating'), 300);
                }, 400);
            }
        });
    }

    function toggleExtraCardsWithAnimation(cards) {
        cards.forEach((card, i) => {
            if (i >= ITEMS_TO_SHOW) {
                if (card.classList.contains('hidden')) {
                    card.classList.remove('hidden');
                    card.style.opacity = "0";
                    card.style.transform = "translateY(20px)";
                    setTimeout(() => {
                        card.style.transition = "opacity 0.5s, transform 0.5s";
                        card.style.opacity = "1";
                        card.style.transform = "translateY(0)";
                        setTimeout(() => { card.style.transition = ""; }, 500);
                    }, (i - ITEMS_TO_SHOW) * 50);
                } else {
                    card.classList.add('hidden');
                }
            }
        });
    }

    function toggleExtraCards(cards) {
        cards.forEach((card, i) => {
            if (i >= ITEMS_TO_SHOW) card.classList.toggle('hidden');
        });
    }

    function updateShowMoreButton(button, isExpanded, totalCards) {
        const remaining = totalCards - ITEMS_TO_SHOW;
        button.style.display = 'flex';
        if (!isExpanded) {
            button.style.transition = "all 0.3s";
            button.style.opacity = "0.8";
            button.style.transform = "translateX(-50%) scale(0.95)";
            setTimeout(() => {
                button.innerHTML = `<span>Ver menos</span>`;
                button.classList.add('expanded');
                setTimeout(() => {
                    button.style.opacity = "1";
                    button.style.transform = "translateX(-50%) scale(1)";
                }, 150);
            }, 150);
        } else {
            button.style.transition = "all 0.3s";
            button.style.opacity = "0.8";
            button.classList.remove('expanded');
            setTimeout(() => {
                if (remaining <= 0) {
                    button.style.display = 'none';
                } else {
                    button.style.transform = "translateX(-50%) scale(0.95)";
                    button.innerHTML = `<span>Ver más</span><span class="equipment-count">(${remaining})</span>`;
                    setTimeout(() => {
                        button.style.opacity = "1";
                        button.style.transform = "translateX(-50%) scale(1)";
                    }, 150);
                }
            }, 350);
        }
    }

    // --- ALERTAS ---
    function initializeAlerts() {
        setupAlertCloseButtons();
        setupAutoCloseAlerts();
    }

    function setupAlertCloseButtons() {
        document.querySelectorAll('.alert-close').forEach(btn => {
            btn.addEventListener('click', () => closeAlert(btn.parentElement));
        });
    }

    function setupAutoCloseAlerts() {
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => alert && closeAlert(alert), 5000);
        });
    }

    function closeAlert(element) {
        element.style.animation = 'fadeOut 0.5s';
        setTimeout(() => element.remove(), 500);
    }

    // --- GALERÍAS DE IMÁGENES ---
    function initializeImageGalleries() {
        document.querySelectorAll('.more-images').forEach(btn => {
            btn.addEventListener('click', function (e) {
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
            document.querySelectorAll('.equipment-card').forEach(card => {
                if (card !== equipmentCard) {
                    const otherGallery = card.querySelector('.images-gallery');
                    const otherButton = card.querySelector('.more-images');
                    if (otherGallery && otherGallery.classList.contains('active')) {
                        otherGallery.classList.remove('active');
                        otherGallery.style.display = 'none';
                        if (otherButton) otherButton.textContent = '+' + otherButton.dataset.total;
                    }
                }
            });
        }
        gallery.classList.toggle('active');
        gallery.style.display = isVisible ? 'none' : 'grid';
        button.textContent = isVisible ? '+' + button.dataset.total : 'Ver Menos';
    }

    // --- LIGHTBOX ---
    function initializeLightbox() {
        lightbox.option({
            resizeDuration: 200,
            wrapAround: true,
            albumLabel: "Imagen %1 de %2",
            fadeDuration: 300,
            imageFadeDuration: 300
        });
    }

    // --- FILTROS ---
    function initializeFilters() {
        document.querySelectorAll('.area-section').forEach(section => {
            const filterButtons = section.querySelectorAll('.filter-btn');
            if (!filterButtons.length) return;
            const equipmentGrid = section.querySelector('.equipment-grid');
            const equipmentCards = equipmentGrid.querySelectorAll('.equipment-card');
            const showMoreBtn = section.querySelector('.btn-show-more');
            const filterMessage = section.querySelector('.no-equipment-message.filter-message');
            const resetFilterBtn = section.querySelector('.reset-filter');

            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', () => {
                    const allBtn = section.querySelector('.filter-btn[data-type="all"]');
                    if (allBtn) allBtn.click();
                });
            }

            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const type = btn.dataset.type;
                    filterButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
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
                    if (visibleCount === 0) {
                        if (filterMessage) {
                            filterMessage.style.display = 'block';
                            equipmentGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
                        }
                        if (showMoreBtn) showMoreBtn.style.display = 'none';
                    } else {
                        if (filterMessage) filterMessage.style.display = 'none';
                        equipmentGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
                        if (visibleCount > ITEMS_TO_SHOW) {
                            const visibleCards = Array.from(equipmentCards).filter(card => card.style.display !== 'none');
                            hideExtraCards(visibleCards);
                            if (showMoreBtn) {
                                showMoreBtn.style.display = 'flex';
                                showMoreBtn.classList.remove('expanded');
                                showMoreBtn.innerHTML = `<span>Ver más</span><span class="equipment-count">(${visibleCount - ITEMS_TO_SHOW})</span>`;
                            }
                        } else if (showMoreBtn) {
                            equipmentCards.forEach(card => {
                                if (card.style.display !== 'none') card.classList.remove('hidden');
                            });
                            showMoreBtn.style.display = 'none';
                        }
                    }
                });
            });
        });
    }

    // --- BÚSQUEDA ---
    function initializeSearch() {
        const searchInput = document.getElementById('searchEquipment');
        const searchResults = document.querySelector('.search-results');
        if (!searchInput || !searchResults) return;
        let recentSearches = JSON.parse(localStorage.getItem('recentSearches') || '[]');
        let selectedIndex = -1;
        
        // Agregar evento al enviar el formulario de búsqueda
        const searchForm = searchInput.closest('form') || searchInput.parentElement;
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const searchTerm = searchInput.value.trim();
                if (searchTerm) {
                    const items = searchResults.querySelectorAll('.search-result-item');
                    if (items.length > 0) {
                        items[0].click(); // Clic en el primer resultado
                    }
                }
            });
        }

        function calculateRelevanceScore(text, searchTerm) {
            const t = text.toLowerCase(), s = searchTerm.toLowerCase();
            if (t === s) return 100;
            if (t.startsWith(s)) return 80;
            if (t.includes(' ' + s + ' ')) return 60;
            if (t.includes(s)) return 40;
            const searchWords = s.split(' '), textWords = t.split(' ');
            let wordMatch = 0;
            searchWords.forEach(word => {
                if (textWords.some(w => w.includes(word))) wordMatch++;
            });
            return (wordMatch / searchWords.length) * 30;
        }

        function highlightText(text, searchTerm) {
            if (!searchTerm) return text;
            const words = searchTerm.toLowerCase().split(' ').filter(Boolean);
            let highlighted = text;
            words.forEach(word => {
                const regex = new RegExp(word, 'gi');
                highlighted = highlighted.replace(regex, m => `<mark>${m}</mark>`);
            });
            return highlighted;
        }

        function updateRecentSearches(term) {
            if (!term) return;
            recentSearches = recentSearches.filter(s => s !== term);
            recentSearches.unshift(term);
            recentSearches = recentSearches.slice(0, 3);
            localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
        }

        function handleClearHistory(clearButton, searchResults) {
            let isConfirming = false, confirmationTimeout;
            clearButton.addEventListener('click', e => {
                e.stopPropagation();
                if (!isConfirming) {
                    isConfirming = true;
                    clearButton.textContent = '¿Confirmar?';
                    clearButton.classList.add('confirming');
                    confirmationTimeout = setTimeout(resetClearButton, 3000);
                } else {
                    clearButton.textContent = '¡Listo!';
                    clearButton.classList.remove('confirming');
                    recentSearches = [];
                    localStorage.removeItem('recentSearches');
                    setTimeout(() => { searchResults.style.display = 'none'; }, 500);
                }
            });
            function resetClearButton() {
                isConfirming = false;
                clearButton.textContent = '';
                clearButton.classList.remove('confirming');
                clearTimeout(confirmationTimeout);
            }
            clearButton.addEventListener('mouseleave', () => {
                if (isConfirming) setTimeout(resetClearButton, 1000);
            });
        }

        document.addEventListener('click', e => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        searchInput.addEventListener('keydown', e => {
            const items = searchResults.querySelectorAll('.search-result-item');
            switch (e.key) {
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
                    } else if (items.length > 0) {
                        // Si no hay selección pero hay resultados, selecciona el primero
                        items[0].click();
                    }
                    return;
                case 'Escape':
                    searchResults.style.display = 'none';
                    searchInput.blur();
                    return;
                default: return;
            }
            items.forEach((item, i) => item.classList.toggle('selected', i === selectedIndex));
        });

        searchInput.addEventListener('focus', () => {
            if (!searchInput.value && recentSearches.length > 0) {
                searchResults.innerHTML = `
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
                searchResults.style.display = 'block';
                searchResults.querySelectorAll('.recent-search').forEach(item => {
                    item.addEventListener('click', () => {
                        searchInput.value = item.dataset.term;
                        searchInput.dispatchEvent(new Event('input'));
                    });
                });
                const clearButton = searchResults.querySelector('.clear-history');
                if (clearButton) handleClearHistory(clearButton, searchResults);
            }
        });

        searchInput.addEventListener('input', debounce(function () {
            const searchTerm = this.value.toLowerCase().trim();
            selectedIndex = -1;
            if (!searchTerm) {
                searchResults.style.display = 'none';
                return;
            }
            searchResults.innerHTML = '<div class="searching-message">Buscando...</div>';
            searchResults.style.display = 'block';
            let results = [];
            document.querySelectorAll('.area-section').forEach(section => {
                const areaName = section.querySelector('.area-title').textContent.trim();
                const areaColor = section.querySelector('.area-title').style.backgroundColor;
                section.querySelectorAll('.equipment-card').forEach(card => {
                    const inventoryCode = card.querySelector('.inventory-code')?.textContent;
                    const equipmentName = card.querySelector('.equipment-body h3')?.textContent;
                    const details = Array.from(card.querySelectorAll('.equipment-body p')).map(p => p.textContent).join(' ');
                    const searchText = `${inventoryCode} ${equipmentName} ${details}`;
                    const relevanceScore = calculateRelevanceScore(searchText, searchTerm);
                    if (relevanceScore > 0) {
                        if (!card.id) card.id = `card-${inventoryCode.replace(/\s+/g, '-')}`;
                        results.push({
                            title: equipmentName,
                            code: inventoryCode,
                            area: areaName,
                            areaColor: areaColor,
                            details: `${card.querySelector('.equipment-body p:nth-child(1)')?.textContent} - ${card.querySelector('.equipment-body p:nth-child(3)')?.textContent}`,
                            cardId: card.id,
                            relevanceScore,
                            card: card
                        });
                    }
                });
            });
            results.sort((a, b) => b.relevanceScore - a.relevanceScore);
            if (results.length > 0) {
                updateRecentSearches(searchTerm);
                searchResults.innerHTML = results.map(result => `
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
                            ${'★'.repeat(Math.ceil(result.relevanceScore / 20))}
                        </div>
                    </div>
                `).join('');
                searchResults.style.display = 'block';
                searchResults.querySelectorAll('.search-result-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const cardId = this.dataset.cardId;
                        const targetCard = document.getElementById(cardId);
                        if (targetCard) {
                            searchResults.style.display = 'none';
                            searchInput.value = '';
                            navigateToCard(targetCard);
                        }
                    });
                });
            } else {
                searchResults.innerHTML = '<div class="no-results-message">No se encontraron resultados</div>';
            }
        }, 300));

        // Función para navegar a una tarjeta de equipo
        function navigateToCard(card) {
            // Revelar equipos ocultos si es necesario
            const section = card.closest('.area-section');
            const showMoreBtn = section.querySelector('.btn-show-more');
            
            // Si la tarjeta está oculta y hay un botón para mostrar más, hacemos clic en él
            if (card.classList.contains('hidden') && showMoreBtn) {
                showMoreBtn.click();
            }
            
            // Si el equipo pertenece a un tipo filtrado, resetear el filtro
            if (card.style.display === 'none') {
                section.querySelectorAll('.filter-btn').forEach(btn => {
                    if (btn.dataset.type === 'all') {
                        btn.click();
                    }
                });
            }
            
            // Aplicar un borde temporal para resaltar mejor la tarjeta
            const originalBorder = card.style.border;
            const originalBoxShadow = card.style.boxShadow;
            const originalZIndex = card.style.zIndex;
            
            // Aplicar resaltado mejorado (más sutil)
            card.style.border = '2px solid #FF9800';
            card.style.boxShadow = '0 0 15px rgba(255, 152, 0, 0.5)';
            card.style.zIndex = '10';
            card.classList.add('highlight');
            
            // Hacer scroll para mostrar la tarjeta
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Eliminar el resaltado después de un tiempo
            setTimeout(() => {
                card.classList.remove('highlight');
                card.style.border = originalBorder;
                card.style.boxShadow = originalBoxShadow;
                card.style.zIndex = originalZIndex;
            }, 2000);
        }
    }

    // --- SCROLL Y ANIMACIÓN DE EQUIPO ---
    window.scrollToEquipment = function (cardId) {
        const card = document.getElementById(cardId);
        if (!card) return;
        card.id = cardId;
        
        // Guardar estilos originales
        const originalBorder = card.style.border;
        const originalBoxShadow = card.style.boxShadow;
        const originalZIndex = card.style.zIndex;
        
        // Aplicar resaltado mejorado (más sutil)
        card.style.border = '2px solid #FF9800';
        card.style.boxShadow = '0 0 15px rgba(255, 152, 0, 0.5)';
        card.style.zIndex = '10';
        card.style.animation = 'highlight 2s';
        
        // Scroll hacia la tarjeta
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Restaurar estilos después de un tiempo
        setTimeout(() => {
            card.style.animation = '';
            card.style.border = originalBorder;
            card.style.boxShadow = originalBoxShadow;
            card.style.zIndex = originalZIndex;
        }, 2000);
    };

    // --- ESTILOS DE ANIMACIÓN ---
    const style = document.createElement('style');
    style.textContent = `
        @keyframes highlight {
            0% { transform: scale(1); }
            20% { transform: scale(1.01); box-shadow: 0 0 15px rgba(255, 152, 0, 0.6); }
            40% { transform: scale(1.005); box-shadow: 0 0 10px rgba(255, 152, 0, 0.4); }
            60% { transform: scale(1.01); box-shadow: 0 0 15px rgba(255, 152, 0, 0.6); }
            80% { transform: scale(1.005); box-shadow: 0 0 10px rgba(255, 152, 0, 0.4); }
            100% { transform: scale(1); box-shadow: var(--card-shadow); }
        }
        
        .highlight {
            position: relative;
            border: 2px solid #FF9800 !important;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }
        
        .highlight::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border: 1px dashed #FF9800;
            border-radius: 6px;
            animation: pulse 1.5s infinite;
            pointer-events: none;
        }
        
        @keyframes pulse {
            0% { opacity: 0.4; }
            50% { opacity: 0.8; }
            100% { opacity: 0.4; }
        }
    `;
    document.head.appendChild(style);

    // --- DEBOUNCE ---
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
});

// --- QR DESCARGA INDIVIDUAL ---
window.downloadQR = function () {
    const canvas = document.querySelector("#qrcode canvas");
    const image = canvas.toDataURL("image/png");
    const link = document.createElement('a');
    link.download = 'qr-code.png';
    link.href = image;
    link.click();
};

// --- QR DESCARGA MASIVA ---
window.downloadAllQRs = function () {
    const modal = document.getElementById('bulkQRModal');
    modal.style.display = 'block';
    const statusElement = document.getElementById('bulkQRStatus');
    statusElement.textContent = "Preparando códigos QR...";
    const equipments = [];
    document.querySelectorAll('.equipment-card').forEach(card => {
        const qrButton = card.querySelector('.btn-qr');
        if (qrButton) {
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
    const tempContainer = document.createElement('div');
    tempContainer.style.position = 'absolute';
    tempContainer.style.left = '-9999px';
    tempContainer.style.top = '-9999px';
    document.body.appendChild(tempContainer);
    const JSZip = window.JSZip;
    const zip = new JSZip();
    let processedCount = 0;
    equipments.forEach((equipment, index) => {
        const qrDiv = document.createElement('div');
        qrDiv.id = `temp-qr-${index}`;
        tempContainer.appendChild(qrDiv);
        new QRCode(qrDiv, {
            text: equipment.pdfUrl,
            width: 256,
            height: 256,
            colorDark: "#000",
            colorLight: "#fff",
            correctLevel: QRCode.CorrectLevel.L
        });
        setTimeout(() => {
            const canvas = qrDiv.querySelector('canvas');
            if (canvas) {
                const imageData = canvas.toDataURL("image/png").replace(/^data:image\/(png|jpg);base64,/, "");
                let inventoryCode = '';
                const equipmentCard = document.getElementById(`card-${equipment.id}`) ||
                    document.querySelector(`.equipment-card[data-card-id="${equipment.id}"]`);
                if (equipmentCard) {
                    inventoryCode = equipmentCard.querySelector('.inventory-code')?.textContent || '';
                    inventoryCode = inventoryCode.replace(/\//g, '-').trim();
                }
                const fileName = inventoryCode ?
                    `QR_${inventoryCode}_${equipment.name.replace(/[^\w\s]/gi, '')}.png` :
                    `QR_${equipment.id}_${equipment.name.replace(/[^\w\s]/gi, '')}.png`;
                zip.file(fileName, imageData, { base64: true });
                processedCount++;
                statusElement.textContent = `Procesando... (${processedCount}/${equipments.length})`;
                if (processedCount === equipments.length) {
                    zip.generateAsync({ type: "blob" }).then(function (content) {
                        statusElement.textContent = "¡Descarga lista!";
                        const zipLink = document.createElement('a');
                        zipLink.href = URL.createObjectURL(content);
                        zipLink.download = "todos_los_qr_equipos.zip";
                        document.body.appendChild(zipLink);
                        zipLink.click();
                        document.body.removeChild(zipLink);
                        setTimeout(() => {
                            document.body.removeChild(tempContainer);
                            modal.style.display = 'none';
                        }, 2000);
                    });
                }
            }
        }, 100 * index);
    });
};

// --- QR DESCARGA POR ÁREA ---
window.downloadAreaQRs = function (areaId, areaName) {
    const modal = document.getElementById('bulkQRModal');
    modal.style.display = 'block';
    const statusElement = document.getElementById('bulkQRStatus');
    statusElement.textContent = `Preparando códigos QR para ${areaName}...`;
    const equipments = [];
    let areaSection = null;
    const downloadButton = document.querySelector(`.dropdown-item[onclick*="downloadAreaQRs('${areaId}'"]`);
    if (downloadButton) areaSection = downloadButton.closest('.area-section');
    if (!areaSection) {
        document.querySelectorAll('.area-section').forEach(section => {
            const title = section.querySelector('.area-title');
            if (title && title.textContent.trim().includes(areaName)) areaSection = section;
        });
    }
    if (areaSection) {
        areaSection.querySelectorAll('.btn-qr').forEach(button => {
            const dataString = button.getAttribute('onclick');
            const matches = dataString.match(/generateQR\(\s*'([^']+)',\s*'([^']+)',\s*'([^']+)'\s*\)/);
            if (matches && matches.length === 4) {
                const card = button.closest('.equipment-card');
                let inventoryCode = '';
                if (card) {
                    const inventoryElement = card.querySelector('.inventory-code');
                    if (inventoryElement) inventoryCode = inventoryElement.textContent.trim();
                }
                equipments.push({
                    id: matches[1],
                    name: matches[2],
                    pdfUrl: matches[3],
                    inventoryCode
                });
            }
        });
    }
    if (equipments.length === 0) {
        statusElement.textContent = `No se encontraron equipos para generar QR en el área ${areaName}`;
        setTimeout(() => { modal.style.display = 'none'; }, 3000);
        return;
    }
    statusElement.textContent = `Generando ${equipments.length} códigos QR para ${areaName}...`;
    const tempContainer = document.createElement('div');
    tempContainer.style.position = 'absolute';
    tempContainer.style.left = '-9999px';
    tempContainer.style.top = '-9999px';
    document.body.appendChild(tempContainer);
    const JSZip = window.JSZip;
    const zip = new JSZip();
    let processedCount = 0;
    equipments.forEach((equipment, index) => {
        const qrDiv = document.createElement('div');
        qrDiv.id = `temp-qr-${index}`;
        tempContainer.appendChild(qrDiv);
        new QRCode(qrDiv, {
            text: equipment.pdfUrl,
            width: 256,
            height: 256,
            colorDark: "#000",
            colorLight: "#fff",
            correctLevel: QRCode.CorrectLevel.L
        });
        setTimeout(() => {
            const canvas = qrDiv.querySelector('canvas');
            if (canvas) {
                const imageData = canvas.toDataURL("image/png").replace(/^data:image\/(png|jpg);base64,/, "");
                const safeInventoryCode = equipment.inventoryCode.replace(/\//g, '-');
                const fileName = safeInventoryCode ?
                    `QR_${safeInventoryCode}_${equipment.name.replace(/[^\w\s]/gi, '')}.png` :
                    `QR_${equipment.id}_${equipment.name.replace(/[^\w\s]/gi, '')}.png`;
                zip.file(fileName, imageData, { base64: true });
                processedCount++;
                statusElement.textContent = `Procesando... (${processedCount}/${equipments.length})`;
                if (processedCount === equipments.length) {
                    zip.generateAsync({ type: "blob" }).then(function (content) {
                        statusElement.textContent = "¡Descarga lista!";
                        const zipLink = document.createElement('a');
                        zipLink.href = URL.createObjectURL(content);
                        const date = new Date().toISOString().split('T')[0];
                        zipLink.download = `QR_${areaName.replace(/[^\w\s]/gi, '')}_${date}.zip`;
                        document.body.appendChild(zipLink);
                        zipLink.click();
                        document.body.removeChild(zipLink);
                        setTimeout(() => {
                            document.body.removeChild(tempContainer);
                            modal.style.display = 'none';
                        }, 2000);
                    });
                }
            }
        }, 100 * index);
    });
};

// --- PDF DESCARGA POR ÁREA ---
window.downloadAreaPDFs = function (areaId, areaName) {
    const modal = document.getElementById('bulkQRModal');
    modal.style.display = 'block';
    const statusElement = document.getElementById('bulkQRStatus');
    statusElement.textContent = `Preparando PDFs para ${areaName}...`;

    const activeFilter = document.querySelector('.filter-btn.active');
    const filterType = activeFilter ? activeFilter.getAttribute('data-type') : 'all';

    // En lugar de hacer una llamada AJAX normal
    window.open(`/equipment/area/${areaId}/pdfs?type=${filterType}`, '_blank');
};

// --- COLOR DE ÁREA ---
function getAreaColor(areaId) {
    // Primero intentamos encontrar el dropdown que contiene el botón para este ID de área
    const dropdown = document.querySelector(`.download-dropdown .dropdown-item[onclick*="downloadAreaQRs('${areaId}'"]`);
    if (dropdown) {
        const parentSection = dropdown.closest('.area-section');
        if (parentSection) return window.getComputedStyle(parentSection).borderColor;
    }
    
    // Si no lo encontramos, buscamos por la sección directamente
    const areaSections = document.querySelectorAll('.area-section');
    for (const section of areaSections) {
        if (section.innerHTML.includes(`downloadAreaQRs('${areaId}'`)) {
            return window.getComputedStyle(section).borderColor;
        }
    }
    
    return '';
}

    // --- MODALES ---
    document.querySelector('.close-modal')?.addEventListener('click', function () {
        document.getElementById('qrModal').style.display = "none";
    });
    document.querySelector('.close-bulk-modal')?.addEventListener('click', function () {
        document.getElementById('bulkQRModal').style.display = "none";
    });
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('qrModal');
        const bulkModal = document.getElementById('bulkQRModal');
        if (event.target === modal) modal.style.display = "none";
        if (event.target === bulkModal) bulkModal.style.display = "none";
        
        // Cerrar todos los dropdowns al hacer clic fuera
        const dropdowns = document.querySelectorAll('.download-dropdown');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target)) {
                const menu = dropdown.querySelector('.dropdown-menu');
                if (menu) menu.style.display = 'none';
            }
        });
    });
    
    // Manejar los clicks en los botones dropdown
    document.querySelectorAll('.btn-dropdown').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = this.nextElementSibling;
            
            // Cerrar todos los demás menús
            document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
                if (otherMenu !== menu) {
                    otherMenu.style.display = 'none';
                }
            });
            
            // Alternar la visualización del menú actual
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        });
    });