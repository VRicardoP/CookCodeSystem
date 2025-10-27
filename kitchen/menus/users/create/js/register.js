
import {BASE_URL} from './../../../../config';


document.addEventListener('DOMContentLoaded', function () {
    const camposObligatorios = document.querySelectorAll('.campo-obligatorio');
    const botonEnviar = document.querySelector('.submitBtn');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const selectGroup = document.getElementById('grupo');
    const emailInput = document.getElementById('email');

    function limpiarCampoPassword(input) {
        if (input.value === "****") {
            input.value = "";
        }
    }

    async function confirmarEmail(id, email) {
        try {
            const response = await $.ajax({
                url: './../../../controllers/checkEmail.php',
                type: 'POST',
                data: {
                    id: id,
                    email: email
                }
            });
            const result = JSON.parse(response);
            return result.exists;
        } catch (error) {
            console.error(error);
            return false;
        }
    }

    function msgConfirmarEmail(existe) {
        const elementMsgComprobacion = document.getElementById('emailMessage') || document.createElement('span');
        elementMsgComprobacion.id = "emailMessage";
        elementMsgComprobacion.textContent = existe ? "Email already exists" : "";
        elementMsgComprobacion.style.color = existe ? "red" : "";
        emailInput.insertAdjacentElement('afterend', elementMsgComprobacion);
    }

    function comprobarFormaPassword(password){


        let formaPassword = false;

        if (password.value.length >= 8 ) {
            formaPassword = true;
        } 

        return formaPassword;

    }

    function msgFormaPassword(correcto) {
        const elementMsgForma = document.createElement('span');
        elementMsgForma.id = "passwordFormaMessage";
        elementMsgForma.textContent = correcto ? "Correct password " : "Password needs at least 8 characters";
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

    function toggleSubmitButton() {
        const todosLlenos = Array.from(camposObligatorios).every(campo => campo.value.trim() !== '');


        if (password.value.length >= 8 && todosLlenos) {
            botonEnviar.disabled = false;
        } else {
            botonEnviar.disabled = true;
        }

      //  botonEnviar.disabled = !todosLlenos;
    }

    password.addEventListener('focus', () => limpiarCampoPassword(password));
    confirmPassword.addEventListener('focus', () => limpiarCampoPassword(confirmPassword));

    confirmPassword.addEventListener('change', function () {
        const coinciden = confirmarPassword(password.value, confirmPassword.value);
        const oldMessage = document.getElementById('passwordMessage');
        if (oldMessage) {
            oldMessage.remove();
        }
        if (password.value !== '') {
            confirmPassword.insertAdjacentElement('afterend', msgConfirmarPassword(coinciden));
        }
    });

    emailInput.addEventListener('input', async function () {
        const id = document.getElementById('user_id').value;
        const emailExists = await confirmarEmail(id, emailInput.value);
        msgConfirmarEmail(emailExists);
        toggleSubmitButton();
    });

    password.addEventListener('change', function (){

        const forma = comprobarFormaPassword(password);
        const oldMessage = document.getElementById('passwordFormaMessage');
        if (oldMessage) {
            oldMessage.remove();
        }
        if (password.value !== '') {
            password.insertAdjacentElement('afterend',msgFormaPassword(forma));
        }

        const coinciden = confirmarPassword(password.value, confirmPassword.value);
        const oldMessageConfirm = document.getElementById('passwordMessage');
        if (oldMessageConfirm) {
            oldMessageConfirm.remove();
        }
        if (password.value !== '') {
            confirmPassword.insertAdjacentElement('afterend', msgConfirmarPassword(coinciden));
        }

    });

    password.addEventListener('input', function (){

        if (password.value.length < 8) {
            botonEnviar.disabled = true;
        } else {
            botonEnviar.disabled = false;
        }

    });


    camposObligatorios.forEach(campo => {
        campo.addEventListener('input', toggleSubmitButton);
    });

    selectGroup.addEventListener('change', toggleSubmitButton);

    document.getElementById('registro').addEventListener('submit', async function (event) {
        event.preventDefault();

        const id = document.getElementById('user_id').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const grupo_id = document.getElementById('grupo').value;
        const name = document.getElementById('name').value;
        const phone = document.getElementById('phone').value;
        const surname = document.getElementById('surname').value;
        const address = document.getElementById('address').value;
        const city = document.getElementById('city').value;
        const cp = document.getElementById('cp').value;
        const country = document.getElementById('country').value;
        const province = document.getElementById('province').value;

        const title = id > 0 ? '¡User edited!' : '¡New User created!';
        const text = `User ${email} was ${id > 0 ? 'edited' : 'created'} successfully`;

        const emailExists = await confirmarEmail(id, email);
        if (emailExists) {
            Swal.fire({
                icon: 'error',
                title: 'Email already exists',
                text: 'Please use a different email.',
                showConfirmButton: true
            });
            return;
        }

        if (password === confirmPassword) {
            const dataToSend = {
                id,
                email,
                password,
                grupo_id,
                name,
                phone,
                surname,
                address,
                city,
                cp,
                country,
                province
            };

            $.ajax({
                url: './../../../controllers/crearUsuario.php',
                type: 'POST',
                data: dataToSend,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: title,
                        text: text,
                        showConfirmButton: false,
                        timer: 3000
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });

            // Guardar usuario en base de datos ecommerce
            const ecommerceDataToSend = {
                email,
                password,
                grupo_id,
                first_name: name,
                last_name: surname,
                username: email,
                billing: {
                    first_name: name,
                    last_name: surname,
                    address_1: address,
                    city: city,
                    postcode: cp,
                    country: country,
                    state: province
                },
                shipping: {
                    first_name: name,
                    last_name: surname,
                    address_1: address,
                    city: city,
                    postcode: cp,
                    country: country,
                    state: province
                }
            };

            $.ajax({
                url: `${BASE_URL}/ecommerce/apiwoo/crearUsuario.php`,
                type: 'POST',
                data: ecommerceDataToSend,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡New User created!',
                        text: 'User ' + email + ' was created successfully',
                        showConfirmButton: false,
                        timer: 5000
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });

            // Reset form fields
            document.getElementById('registro').reset();
            document.getElementById('passwordMessage').textContent = "";
            document.getElementById('passwordFormaMessage').textContent = "";
            botonEnviar.disabled = true;
        }
    });
});
