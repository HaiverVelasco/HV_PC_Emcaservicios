/**
 * Archivo JS para gestionar las alertas de tiempo de sesión
 */

document.addEventListener('DOMContentLoaded', function() {
    // Verificamos si el usuario está autenticado como administrador
    if (typeof sessionExpiryTime === 'undefined') {
        // Si la variable no está definida, no hacer nada
        return;
    }

    // Calculamos tiempos importantes
    const sessionDuration = 120 * 60 * 1000; // 2 horas en milisegundos
    const halfSessionTime = sessionDuration / 2; // 1 hora
    const warningTime = 10 * 60 * 1000; // 10 minutos

    // Estado para controlar qué notificaciones ya se han mostrado
    let initialAlertShown = false;
    let halfTimeAlertShown = false;
    let warningAlertShown = false;

    // Función para mostrar una alerta estilizada
    function showCustomAlert(message, type = 'info') {
        // Crear el contenedor de la alerta si no existe
        let alertContainer = document.querySelector('.session-alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'session-alert-container';
            document.body.appendChild(alertContainer);
        }

        // Crear la alerta
        const alert = document.createElement('div');
        alert.className = `session-alert session-alert-${type}`;
        alert.innerHTML = `
            <div class="session-alert-content">
                <span class="session-alert-icon">${type === 'warning' ? '⚠️' : 'ℹ️'}</span>
                <span class="session-alert-message">${message}</span>
                <button class="session-alert-close">&times;</button>
            </div>
        `;

        // Agregar al contenedor
        alertContainer.appendChild(alert);

        // Configurar el botón de cierre
        const closeButton = alert.querySelector('.session-alert-close');
        closeButton.addEventListener('click', function() {
            alert.classList.add('session-alert-hiding');
            setTimeout(() => {
                alert.remove();
            }, 300);
        });

        // Auto-cerrar después de 6 segundos
        setTimeout(() => {
            if (alert.parentElement) {
                alert.classList.add('session-alert-hiding');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }, 6000);
    }

    // Función para formatear el tiempo restante en horas y minutos
    function formatTimeRemaining(milliseconds) {
        const minutes = Math.floor(milliseconds / (60 * 1000));
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = minutes % 60;
        
        if (hours > 0) {
            return `${hours} hora${hours > 1 ? 's' : ''} y ${remainingMinutes} minuto${remainingMinutes !== 1 ? 's' : ''}`;
        } else {
            return `${minutes} minuto${minutes !== 1 ? 's' : ''}`;
        }
    }

    // Función que se ejecutará periódicamente
    function checkSessionTime() {
        // Calculamos el tiempo restante
        const currentTime = new Date().getTime();
        const timeElapsed = currentTime - sessionStartTime;
        const timeRemaining = sessionDuration - timeElapsed;

        // Alerta inicial (solo se muestra una vez al inicio)
        if (!initialAlertShown && timeElapsed < 10000) { // Mostrar en los primeros 10 segundos
            initialAlertShown = true;
            showCustomAlert(`Sesión iniciada correctamente. Tienes 2 horas de acceso como administrador.`, 'info');
        }

        // Alerta a mitad del tiempo
        if (!halfTimeAlertShown && timeElapsed >= halfSessionTime && timeElapsed < halfSessionTime + 60000) {
            halfTimeAlertShown = true;
            const remaining = formatTimeRemaining(timeRemaining);
            showCustomAlert(`Has usado la mitad de tu tiempo de sesión. Te quedan ${remaining}.`, 'info');
        }

        // Alerta de advertencia cuando quedan 10 minutos
        if (!warningAlertShown && timeRemaining <= warningTime && timeRemaining > warningTime - 60000) {
            warningAlertShown = true;
            const remaining = formatTimeRemaining(timeRemaining);
            showCustomAlert(`¡Atención! Tu sesión expirará pronto. Te quedan ${remaining}.`, 'warning');
        }

        // Si el tiempo ha expirado, redireccionar automáticamente
        if (timeRemaining <= 0) {
            window.location.href = '/';
            return;
        }

        // Planificar la siguiente verificación
        setTimeout(checkSessionTime, 5000); // Verificar cada 5 segundos
    }

    // Iniciar la verificación del tiempo
    checkSessionTime();
});