/**
 * Script para mejorar la experiencia de scroll en contenedores de botones
 * Administra los indicadores visuales de scroll y mejora la navegación
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Función para verificar si un elemento puede hacer scroll
    function checkScrollability(element) {
        const canScrollLeft = element.scrollLeft > 0;
        const canScrollRight = element.scrollLeft < (element.scrollWidth - element.clientWidth);
        
        // Agregar/remover clases CSS para los indicadores
        element.classList.toggle('can-scroll-left', canScrollLeft);
        element.classList.toggle('can-scroll-right', canScrollRight);
    }
    
    // Función para hacer scroll suave hacia la izquierda o derecha
    function smoothScroll(element, direction) {
        const scrollAmount = element.clientWidth * 0.8; // 80% del ancho visible
        const currentScroll = element.scrollLeft;
        const targetScroll = direction === 'left' 
            ? Math.max(0, currentScroll - scrollAmount)
            : Math.min(element.scrollWidth - element.clientWidth, currentScroll + scrollAmount);
        
        element.scrollTo({
            left: targetScroll,
            behavior: 'smooth'
        });
    }
    
    // Inicializar contenedores de botones con scroll
    function initButtonContainers() {
        const containers = document.querySelectorAll('.btn-action-container, .button-container, .btn-group, .download-buttons');
        
        containers.forEach(container => {
            // Verificar scrollabilidad inicial
            checkScrollability(container);
            
            // Agregar listener para el evento scroll
            container.addEventListener('scroll', function() {
                checkScrollability(this);
            });
            
            // Agregar listener para cambios de tamaño de ventana
            window.addEventListener('resize', function() {
                checkScrollability(container);
            });
            
            // Agregar funcionalidad de scroll con rueda del mouse (horizontal)
            container.addEventListener('wheel', function(e) {
                if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
                    // Ya es scroll horizontal, no hacer nada
                    return;
                }
                
                // Convertir scroll vertical en horizontal
                if (this.scrollWidth > this.clientWidth) {
                    e.preventDefault();
                    this.scrollLeft += e.deltaY * 0.5; // Factor de velocidad
                    checkScrollability(this);
                }
            });
            
            // Agregar soporte para navegación por teclado
            container.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    smoothScroll(this, 'left');
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    smoothScroll(this, 'right');
                }
            });
            
            // Hacer el contenedor focusable para la navegación por teclado
            if (!container.hasAttribute('tabindex')) {
                container.setAttribute('tabindex', '0');
            }
        });
    }
    
    // Función para manejar el scroll suave en dropdowns
    function initDropdownScrolls() {
        const dropdowns = document.querySelectorAll('.dropdown-menu, .select-options');
        
        dropdowns.forEach(dropdown => {
            // Mejorar la experiencia de scroll en dropdowns
            dropdown.addEventListener('wheel', function(e) {
                e.stopPropagation(); // Evitar que se propague al contenedor padre
            });
        });
    }
    
    // Función para observar cambios dinámicos en el DOM
    function initMutationObserver() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    // Re-inicializar contenedores nuevos
                    initButtonContainers();
                    initDropdownScrolls();
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    // Función para agregar efectos de animación en scroll
    function initScrollAnimations() {
        const animatedContainers = document.querySelectorAll('.equipment-card-container, .maintenance-list');
        
        animatedContainers.forEach(container => {
            let scrollTimeout;
            
            container.addEventListener('scroll', function() {
                // Agregar clase durante el scroll
                this.classList.add('scrolling');
                
                // Remover clase después de un delay
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    this.classList.remove('scrolling');
                }, 150);
            });
        });
    }
    
    // Función para mejorar la accesibilidad
    function improveAccessibility() {
        const scrollableElements = document.querySelectorAll('[class*="scrollbar"], [class*="scroll"]');
        
        scrollableElements.forEach(element => {
            // Agregar atributos ARIA si no los tiene
            if (!element.hasAttribute('aria-label') && !element.hasAttribute('aria-labelledby')) {
                element.setAttribute('aria-label', 'Contenido desplazable');
            }
            
            // Agregar información sobre controles de teclado
            if (element.classList.contains('btn-action-container') || 
                element.classList.contains('button-container')) {
                element.setAttribute('title', 'Use las flechas ← → para navegar, o desplace horizontalmente');
            }
        });
    }
    
    // Función para optimizar el rendimiento
    function optimizePerformance() {
        // Usar Intersection Observer para elementos que salen de vista
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-viewport');
                    } else {
                        entry.target.classList.remove('in-viewport');
                    }
                });
            });
            
            const observableElements = document.querySelectorAll('.equipment-card-container, .btn-action-container');
            observableElements.forEach(el => observer.observe(el));
        }
    }
    
    // Inicializar todas las funcionalidades
    try {
        initButtonContainers();
        initDropdownScrolls();
        initScrollAnimations();
        improveAccessibility();
        optimizePerformance();
        
        // Solo inicializar el observer si hay contenido dinámico
        if (document.querySelector('[data-dynamic-content]')) {
            initMutationObserver();
        }
        
        console.log('✅ Sistema de scrollbars mejorado inicializado correctamente');
    } catch (error) {
        console.warn('⚠️ Error al inicializar el sistema de scrollbars:', error);
    }
});

// CSS adicional aplicado dinámicamente para mejorar la experiencia
const dynamicCSS = `
    .scrolling {
        scroll-behavior: auto !important;
    }
    
    .in-viewport {
        will-change: scroll-position;
    }
    
    .btn-action-container:focus {
        outline: 2px solid var(--scrollbar-thumb-bg, #276fb7);
        outline-offset: 2px;
    }
    
    @media (prefers-reduced-motion: reduce) {
        .btn-action-container {
            scroll-behavior: auto !important;
        }
    }
`;

// Aplicar CSS dinámico
const styleSheet = document.createElement('style');
styleSheet.textContent = dynamicCSS;
document.head.appendChild(styleSheet);
