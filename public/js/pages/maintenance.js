/**
 * Manejo de Alertas en la Página
 * Inicializa cuando el DOM está completamente cargado
 */
document.addEventListener('DOMContentLoaded', function() {
    
    /**
     * Función para inicializar el sistema de alertas
     * - Maneja el cierre manual de alertas
     * - Configura el cierre automático después de 5 segundos
     */
    function initializeAlerts() {
        // Manejo de cierre manual de alertas
        const alertCloseButtons = document.querySelectorAll('.alert-close');
        alertCloseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.parentElement;
                fadeOutAlert(alert);
            });
        });

        // Cierre automático de alertas después de 5 segundos
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                fadeOutAlert(alert);
            });
        }, 5000);
    }

    /**
     * Función auxiliar para desvanecer y ocultar una alerta
     * @param {HTMLElement} alert - Elemento de alerta a ocultar
     */
    function fadeOutAlert(alert) {
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.style.display = 'none';
        }, 300);
    }

    // Inicialización del sistema de alertas
    initializeAlerts();
});
