/**
 * Archivo JS para gestionar las alertas de tiempo de sesión
 */

document.addEventListener('DOMContentLoaded', () => {
    // Verificar si el usuario está autenticado como administrador
    if (typeof sessionExpiryTime === 'undefined') return;

    // Constantes de tiempo
    const SESSION_DURATION = 2 * 60 * 60 * 1000; // 2 horas en ms
    const HALF_SESSION = SESSION_DURATION / 2;
    const WARNING_TIME = 10 * 60 * 1000; // 10 minutos en ms

    // Estado de alertas
    let initialAlertShown = false;
    let halfTimeAlertShown = false;
    let warningAlertShown = false;

    // Mostrar alerta estilizada
    function showCustomAlert(message, type = 'info') {
        let alertContainer = document.querySelector('.session-alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'session-alert-container';
            document.body.appendChild(alertContainer);
        }

        const alert = document.createElement('div');
        alert.className = `session-alert session-alert-${type}`;
        alert.innerHTML = `
            <div class="session-alert-content">
                <span class="session-alert-icon">${type === 'warning' ? '⚠️' : 'ℹ️'}</span>
                <span class="session-alert-message">${message}</span>
                <button class="session-alert-close">&times;</button>
            </div>
        `;
        alertContainer.appendChild(alert);

        alert.querySelector('.session-alert-close').onclick = () => {
            alert.classList.add('session-alert-hiding');
            setTimeout(() => alert.remove(), 300);
        };

        setTimeout(() => {
            if (alert.parentElement) {
                alert.classList.add('session-alert-hiding');
                setTimeout(() => alert.remove(), 300);
            }
        }, 6000);
    }

    // Formatear tiempo restante
    function formatTimeRemaining(ms) {
        const minutes = Math.floor(ms / 60000);
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = minutes % 60;
        if (hours > 0) {
            return `${hours} hora${hours > 1 ? 's' : ''} y ${remainingMinutes} minuto${remainingMinutes !== 1 ? 's' : ''}`;
        }
        return `${minutes} minuto${minutes !== 1 ? 's' : ''}`;
    }

    // Verificar tiempo de sesión periódicamente
    function checkSessionTime() {
        const now = Date.now();
        const timeElapsed = now - sessionStartTime;
        const timeRemaining = SESSION_DURATION - timeElapsed;

        if (!initialAlertShown && timeElapsed < 10000) {
            initialAlertShown = true;
            showCustomAlert('Sesión iniciada correctamente. Tienes 2 horas de acceso como administrador.', 'info');
        }

        if (!halfTimeAlertShown && timeElapsed >= HALF_SESSION && timeElapsed < HALF_SESSION + 60000) {
            halfTimeAlertShown = true;
            showCustomAlert(`Has usado la mitad de tu tiempo de sesión. Te quedan ${formatTimeRemaining(timeRemaining)}.`, 'info');
        }

        if (!warningAlertShown && timeRemaining <= WARNING_TIME && timeRemaining > WARNING_TIME - 60000) {
            warningAlertShown = true;
            showCustomAlert(`¡Atención! Tu sesión expirará pronto. Te quedan ${formatTimeRemaining(timeRemaining)}.`, 'warning');
        }

        if (timeRemaining <= 0) {
            window.location.href = '/';
            return;
        }

        setTimeout(checkSessionTime, 5000);
    }

    checkSessionTime();
});
