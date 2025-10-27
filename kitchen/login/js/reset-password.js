document.addEventListener('DOMContentLoaded', function () {
    const botonEnviar = document.querySelector('.submitBtn');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    function comprobarFormaPassword(password) {
        return password.value.length >= 8;
    }

    function msgFormaPassword(correcto) {
        const elementMsgForma = document.createElement('span');
        elementMsgForma.id = "passwordFormaMessage";
        elementMsgForma.textContent = correcto ? "Correct password" : "Password needs at least 8 characters";
        elementMsgForma.style.color = correcto ? "green" : "red";
        return elementMsgForma;
    }

    function confirmarPassword(password, repeticionPassword) {
        return password === repeticionPassword;
    }

    function msgConfirmarPassword(coinciden) {
        const elementMsgComprobacion = document.createElement('span');
        elementMsgComprobacion.id = "passwordMessage";
        elementMsgComprobacion.textContent = coinciden ? "Correct password confirmation" : "Password confirmation failed";
        elementMsgComprobacion.style.color = coinciden ? "green" : "red";
        return elementMsgComprobacion;
    }

    function habilitarBoton() {
        const forma = comprobarFormaPassword(password);
        const coinciden = confirmarPassword(password.value, confirmPassword.value);
        botonEnviar.disabled = !(forma && coinciden);
    }

    confirmPassword.addEventListener('input', function () {
        const coinciden = confirmarPassword(password.value, confirmPassword.value);
        const oldMessage = document.getElementById('passwordMessage');
        if (oldMessage) {
            oldMessage.remove();
        }
        confirmPassword.insertAdjacentElement('afterend', msgConfirmarPassword(coinciden));
        habilitarBoton();
    });

    password.addEventListener('input', function () {
        const forma = comprobarFormaPassword(password);
        const oldMessage = document.getElementById('passwordFormaMessage');
        if (oldMessage) {
            oldMessage.remove();
        }
        password.insertAdjacentElement('afterend', msgFormaPassword(forma));

        const coinciden = confirmarPassword(password.value, confirmPassword.value);
        const oldMessageConfirm = document.getElementById('passwordMessage');
        if (oldMessageConfirm) {
            oldMessageConfirm.remove();
        }
        confirmPassword.insertAdjacentElement('afterend', msgConfirmarPassword(coinciden));

        habilitarBoton();
    });
});
