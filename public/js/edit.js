document.addEventListener('DOMContentLoaded', function() {
    // Función para cerrar la alerta
    function closeAlert(element) {
        element.style.animation = 'fadeOut 0.5s ease-out';
        setTimeout(() => {
            element.remove();
        }, 500);
    }

    // Cerrar alerta al hacer clic en el botón
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', function() {
            closeAlert(this.parentElement);
        });
    });

    // Auto-cerrar alertas después de 5 segundos
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert) {
                closeAlert(alert);
            }
        }, 5000);
    });
});