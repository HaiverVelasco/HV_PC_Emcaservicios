document.addEventListener('DOMContentLoaded', function() {
    // Solo manejo de alertas
    function initializeAlerts() {
        const alertCloseButtons = document.querySelectorAll('.alert-close');

        alertCloseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.parentElement;
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            });
        });

        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            });
        }, 5000);
    }

    initializeAlerts();
});
