// utils.js - Funciones utilitarias reutilizables

/**
 * Muestra una notificación de error con SweetAlert2
 * @param {string} message - Mensaje de error a mostrar
 */
export function showErrorNotification(message = 'Ocurrió un error inesperado') {
    return Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonText: 'Ok',
        confirmButtonColor: '#2E8B57'
    });
}

/**
 * Muestra una notificación de éxito
 * @param {string} title - Título de la notificación
 * @param {string} text - Texto descriptivo
 * @param {number} timer - Tiempo en ms para auto-cierre (opcional)
 */
export function showSuccessNotification(title, text, timer = 2500) {
    return Swal.fire({
        title,
        text,
        icon: 'success',
        showConfirmButton: false,
        timer
    });
}


/**
 * Muestra una notificación de espera
 * @param {string} title - Título de la notificación
 * @param {string} text - Texto descriptivo
 */
export function showLoadingNotification(title = "Save...", text = "Please wait") {
    return Swal.fire({
        title,
        text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}


/**
 * Validar archivo por extensión
 * @param {File} file - Objeto File a validar
 * @param {string[]} allowedTypes - Extensiones permitidas
 * @returns {boolean}
 */
export function validateFileType(file, allowedTypes) {
    if (!file) return false;
    const extension = file.name.split('.').pop().toLowerCase();
    return allowedTypes.includes(extension);
}

/**
 * Formatear precio con símbolo de euro
 * @param {number} price - Precio a formatear
 * @param {number} decimals - Decimales a mostrar
 * @returns {string}
 */
export function formatPrice(price, decimals = 2) {
    return `€${parseFloat(price).toFixed(decimals)}`;
}
