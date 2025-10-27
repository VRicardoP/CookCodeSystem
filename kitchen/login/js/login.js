document.addEventListener('DOMContentLoaded', (event) => {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rememberMeCheckbox = document.getElementById('rememberMe');
    
    // Leer cookies
    const emailCookie = getCookie('email');
    const passwordCookie = getCookie('password');
    
    if (emailCookie && passwordCookie) {
        emailInput.value = emailCookie;
        passwordInput.value = passwordCookie;
        rememberMeCheckbox.checked = true;
    }

    // Manejar el evento de envío del formulario
    document.getElementById('loginForm').addEventListener('submit', (e) => {
        if (rememberMeCheckbox.checked) {
            setCookie('email', emailInput.value, 1000); // Expira en 1000 días
            setCookie('password', passwordInput.value, 1000); // Expira en 1000 días
        } else {
            // Eliminar cookies si "Remember Me" no está marcado
            setCookie('email', '', 0);
            setCookie('password', '', 0);
        }
    });
});

// Función para establecer una cookie
function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

// Función para obtener una cookie por su nombre
function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
