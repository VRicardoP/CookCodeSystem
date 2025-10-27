function toggleCheckboxValue(checkbox) {
    if (checkbox.checked) {
        checkbox.value = 1;
    } else {
        checkbox.value = 0;
    }
}


document.getElementById('registro').addEventListener('submit', function(event) {
    event.preventDefault();
    var id = document.getElementById('grupo_id').value;
    var name = document.getElementById('name').value;
    var permisos = document.getElementsByName('permisos[]');
    var permisosArray = [];

    var title = "";
    var text = "";

    if (id > 0) {
        title = '¡Group edited!';
        text = 'Group was edited succesfully';
    } else {
        title = '¡New Group created!';
        text = 'Group was created succesfully';
    }



    permisos.forEach(permiso => {

        if (permiso.checked) {
            permisosArray.push({

                value: permiso.value,

            });

        }

    });


    let dataToSend = {
        id: id,
        name: name,
        permisos: permisosArray,

    };

    $.ajax({
        url: 'crearGrupo.php',
        type: 'POST',
        data: dataToSend,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: title,
                text: text,
                showConfirmButton: false,
                timer: 3000
            });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });

});

document.addEventListener('DOMContentLoaded', function() {
    var botonEnviar = document.querySelector('.submitBtn');
    var checkboxes = document.querySelectorAll('.check-permiso');
    var nombre = document.getElementById('name');

    function actualizarEstadoBoton() {
        var check = Array.from(checkboxes).some(checkbox => checkbox.checked);
        var inputName = nombre.value.trim() !== "";
        botonEnviar.disabled = !(check && inputName);
    }

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', actualizarEstadoBoton);
    });

    nombre.addEventListener('input', actualizarEstadoBoton);

    // Inicializar el estado del botón
    actualizarEstadoBoton();
});