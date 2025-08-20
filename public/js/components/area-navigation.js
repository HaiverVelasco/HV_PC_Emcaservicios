document.addEventListener('DOMContentLoaded', function() {
    const navigationControls = document.querySelector('.navigation-controls');
    if (!navigationControls) return;

    // Añadir estilos dinámicamente
    const style = document.createElement('style');
    style.textContent = `
        .areas-dropdown-container {
            position: relative;
            display: inline-flex;
            min-width: 220px;
            margin: 0;
        }
        .areas-dropdown-button {
            width: 100%;
            padding: 14px 18px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-align: left;
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(39, 111, 183, 0.3);
            position: relative;
            overflow: hidden;
        }
        .areas-dropdown-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .areas-dropdown-button:hover::before {
            left: 100%;
        }
        .areas-dropdown-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(39, 111, 183, 0.4);
        }
        .areas-dropdown-button:active {
            transform: translateY(0);
        }
        .areas-dropdown-button .dropdown-icon {
            font-size: 12px;
            transition: transform 0.3s ease;
        }
        .areas-dropdown-button.active .dropdown-icon {
            transform: rotate(180deg);
        }
        .areas-navigation {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 100%;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            max-height: 320px;
            overflow-y: auto;
            border: 1px solid rgba(39, 111, 183, 0.1);
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Estilos para la barra de desplazamiento - Webkit (Chrome, Safari, Edge) */
        .areas-navigation::-webkit-scrollbar {
            width: 8px;
        }
        
        .areas-navigation::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
        }
        
        .areas-navigation::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }
        
        .areas-navigation::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
            border: 2px solid transparent;
            background-clip: padding-box;
        }
        
        /* Modo oscuro para la barra de desplazamiento */
        [data-theme="dark"] .areas-navigation::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border: 2px solid transparent;
            background-clip: padding-box;
        }
        
        [data-theme="dark"] .areas-navigation::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
        .areas-navigation.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }
        .area-nav-button {
            display: block;
            width: 100%;
            padding: 14px 20px;
            text-decoration: none;
            color: var(--text-color);
            font-size: 14px;
            font-weight: 500;
            text-align: left;
            border: none;
            background: none;
            transition: all 0.3s ease;
            position: relative;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .area-nav-button:last-child {
            border-bottom: none;
        }
        .area-nav-button::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--secondary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            border-radius: 0 4px 4px 0;
        }
        .area-nav-button:hover::before {
            transform: scaleY(1);
        }
        .area-nav-button:hover {
            background: linear-gradient(90deg, rgba(39, 111, 183, 0.08) 0%, transparent 100%);
            color: var(--primary-color);
            padding-left: 24px;
            font-weight: 600;
        }
        .area-nav-button:first-child {
            border-radius: 12px 12px 0 0;
        }
        .area-nav-button:last-child {
            border-radius: 0 0 12px 12px;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        [data-theme="dark"] .areas-navigation {
            background-color: var(--card-bg);
            border-color: rgba(255, 255, 255, 0.1);
        }
        [data-theme="dark"] .area-nav-button {
            color: var(--text-color);
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }
        [data-theme="dark"] .area-nav-button:hover {
            background: linear-gradient(90deg, rgba(39, 111, 183, 0.15) 0%, transparent 100%);
            color: white;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .areas-dropdown-container {
                min-width: 180px;
            }
            .areas-dropdown-button {
                padding: 12px 16px;
                font-size: 13px;
            }
        }
            border: 1px solid var(--border-color);
        }
        [data-theme="dark"] .area-nav-button {
            color: var(--text-color);
        }
        [data-theme="dark"] .area-nav-button:hover {
            background-color: var(--section-bg);
        }

        /* Estilos responsive */
        @media (max-width: 768px) {
            .areas-dropdown-container {
                width: 100%;
                margin: 10px 0;
                max-width: none;
            }
            
            .areas-dropdown-button {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            .areas-navigation {
                max-height: 250px;
            }
            
            .area-nav-button {
                padding: 10px 15px;
                font-size: 13px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .areas-dropdown-container {
                width: 220px;
                margin: 10px;
            }
        }

        @media (min-width: 1025px) {
            .areas-dropdown-container {
                margin: 10px 30px 10px 20px;
            }
        }
    `;
    document.head.appendChild(style);

    // Crear el contenedor del desplegable
    const dropdownContainer = document.createElement('div');
    dropdownContainer.className = 'areas-dropdown-container';

    // Crear el botón del desplegable
    const dropdownButton = document.createElement('button');
    dropdownButton.className = 'areas-dropdown-button';
    dropdownButton.innerHTML = `
        <span>🚀 Navegación Rápida</span>
        <span class="dropdown-icon">▼</span>
    `;

    // Crear contenedor para los botones de área
    const areasNav = document.createElement('div');
    areasNav.className = 'areas-navigation';
    
    // Obtener todas las secciones de área
    const areas = document.querySelectorAll('.area-section');
    
    // Crear botones para cada área
    areas.forEach(area => {
        const areaTitle = area.querySelector('.area-title');
        if (areaTitle) {
            const button = document.createElement('a');
            button.className = 'area-nav-button';
            // Obtener solo el nombre del área, eliminando todos los textos adicionales y símbolos
            let areaName = areaTitle.textContent.trim();
            
            // Primero eliminamos textos comunes
            areaName = areaName.replace(/Descargar QRs|Descargar PDFs|Generar Excel/g, '').trim();
            
            // Eliminamos todos los caracteres especiales, incluyendo dos puntos, emojis, y otros símbolos
            areaName = areaName.replace(/[^\w\sÁáÉéÍíÓóÚúÜüÑñ]/g, '').trim();
            
            button.textContent = areaName;
            button.href = '#';
            
            button.addEventListener('click', (e) => {
                e.preventDefault();
                area.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
            
            areasNav.appendChild(button);
        }
    });
    
    // Insertar el desplegable en los controles de navegación
    dropdownContainer.appendChild(dropdownButton);
    dropdownContainer.appendChild(areasNav);
    navigationControls.appendChild(dropdownContainer);

    // Manejar la apertura/cierre del desplegable
    dropdownButton.addEventListener('click', () => {
        const isOpen = areasNav.classList.contains('show');
        areasNav.classList.toggle('show');
        dropdownButton.classList.toggle('active', !isOpen);
    });

    // Cerrar el desplegable cuando se hace clic fuera de él
    document.addEventListener('click', (e) => {
        if (!dropdownContainer.contains(e.target)) {
            areasNav.classList.remove('show');
            dropdownButton.classList.remove('active');
        }
    });

    // Actualizar el texto del botón cuando se selecciona un área
    areasNav.addEventListener('click', (e) => {
        if (e.target.classList.contains('area-nav-button')) {
            const selectedArea = e.target.textContent;
            dropdownButton.innerHTML = `
                <span>${selectedArea}</span>
                <span class="dropdown-icon">▼</span>
            `;
            areasNav.classList.remove('show');
            dropdownButton.classList.remove('active');
        }
    });
});
