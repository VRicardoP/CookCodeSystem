
//*****************Ventana con Qr que aparece al guardar el ingrediente***********************

function modalQrGuardado(nameFile) {

    var imgQr = document.getElementById("imgQr");
    imgQr.src = "temp/" + nameFile + ".png";
    // Ventana modal
    var modal = document.getElementById("ventanaModal");
    modal.style.display = "block";

    var span = document.getElementsByClassName("cerrar")[0];

    // Si el usuario hace clic en la x, la ventana se cierra
    span.addEventListener("click", function () {
        modal.style.display = "none";
    });
    // Si el usuario hace clic fuera de la ventana, se cierra.
    window.addEventListener("click", function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
}


//*****************Coste de precio y unidad del ingrediente seleccionado**********************

function costeUnidadIngredienteSeleccionado(listaIngredientes) {
    var inputName = document.getElementById('fNameInput');
    var idIngredienteSeleccionado = inputName.getAttribute('data-id');
    var costPrice = 0;
    var unidad = "";
    for (var i = 0; i < listaIngredientes.length; i++) {
        if (listaIngredientes[i]['id'] == idIngredienteSeleccionado) {

            costPrice = listaIngredientes[i]['costPrice'];
            unidad = listaIngredientes[i]['unidad'];
            break; // Salir del bucle una vez encontramos la receta
        }
    }

    var costeYUnidad = [];
    costeYUnidad.push({
        costPrice: costPrice,
        unidad: unidad
    });

    return costeYUnidad;

}

//**************Inserccion en el formulario del ingrediente seleccionado***************


function mostrarDatosIngrediente(listaIngredientes) {

    var imgIngrediente = document.getElementById('imagenCh');
    var caducidad = document.getElementById('caducidad');
    var packaging = document.getElementById('packaging');
    var warehouse = document.getElementById('warehouse');

    var inputName = document.getElementById('fNameInput');

    console.log("DEnro de funcion")
    inputName.addEventListener('change', function () {

        var inputName = document.getElementById('fNameInput');

        var list = document.getElementById('list_ing');
        var options = list.getElementsByTagName('option');
        var inputValue = inputName.value;

        for (var i = 0; i < options.length; i++) {
            if (options[i].value === inputValue) {
                var selectedId = options[i].getAttribute('data-id');
                inputName.setAttribute('data-id', selectedId);
                console.log('Selected ID:', selectedId); 
                break;
            }
        }

        var pathImagen = "";
        var inputName = document.getElementById('fNameInput');
        var idIngredienteSeleccionado = inputName.getAttribute('data-id');
        console.log("idIngredienteSeleccionado" + idIngredienteSeleccionado)

        for (var i = 0; i < listaIngredientes.length; i++) {


            if (listaIngredientes[i]['id'] == selectedId) {
                if (listaIngredientes[i]['imagen'] != null) {
                    pathImagen = listaIngredientes[i]['imagen'];
                }
                else {
                    pathImagen = "./../svg/image.svg"
                }


                imgIngrediente.src = "./../" + pathImagen;

                caducidad.value = listaIngredientes[i]['caducidad'];
                packaging.value = listaIngredientes[i]['packaging'];
                warehouse.value = listaIngredientes[i]['warehouse'];
                //cantidadPaquete.value = listaIngredientes[i]['num_raciones'];
                break;
            }
        }


    });

}


//**********Habilitar boton de guardado de ingrediente al rellenar los campos obligatorios****************

function camposObligatorio() {
    // Obtener todos los campos de entrada obligatorios
    var camposObligatorios = document.querySelectorAll('.campo-obligatorio');

    var botonEnviar = document.querySelector('.submitBtn');

    camposObligatorios.forEach(function (campo) {
        campo.addEventListener('input', function () {
            // Verificar si todos los campos obligatorios están llenos
            var todosLlenos = Array.from(camposObligatorios).every(function (campo) {
                return campo.value.trim() !== '';
            });

            botonEnviar.disabled = !todosLlenos;
        });
    });
}


//*********Guardar el ingrediente**********

function guardarTag() {
    var idElaborado = document.getElementById('elaborado_id').value;
    var tipoProduct = "Elaborado";
    var input = document.getElementById('fNameInput');
    var idIngrediente = input.getAttribute('data-id');
    var packaging = document.getElementById('packaging').value;
    var productAmount = document.getElementById('productamount').value;
    var fechaElab = document.getElementById('fechaElab').value;
    var fechaCad = document.getElementById('caducidad').value;
    var warehouse = document.getElementById('warehouse').value;
    var costCurrency = document.getElementById('costCurrency').value;
    var costPrice = document.getElementById('costPrice').value;
    var salePrice = document.getElementById('salePrice').value;
    var saleCurrency = document.getElementById('saleCurrency').value;

    let fechaElaboracion = new Date(fechaElab);

   
    fechaElaboracion.setDate(fechaElaboracion.getDate() + fechaCad);

    // Convertir la nueva fecha de caducidad a un formato legible
    let fechaCaducidad = fechaElaboracion.toISOString().split('T')[0];

    let dataToSend = {
        idElaborado: idElaborado,
        tipoProduct: tipoProduct,
        idIngrediente: idIngrediente,
        packaging: packaging,
        productAmount: productAmount,
        fechaElab: fechaElab,
        fechaCad: fechaCaducidad,
        warehouse: warehouse,
        costCurrency: costCurrency,
        costPrice: costPrice,
        salePrice: salePrice,
        saleCurrency: saleCurrency,
    };

    $.ajax({
        url: 'crearEditarTagIngrediente.php',
        type: 'POST',
        data: dataToSend,
        success: function (response) {
            console.log(response);
            document.getElementById('fNameInput').value = "";
            document.getElementById('packaging').value = "Bag";
            document.getElementById('productamount').value = "";
            document.getElementById('fechaElab').value = "";
            document.getElementById('caducidad').value = "";
            document.getElementById('warehouse').value = "Freezer";
            document.getElementById('costCurrency').value = "Euro";
            document.getElementById('costPrice').value = 0;
            document.getElementById('salePrice').value = 0;
            document.getElementById('saleCurrency').value = "Euro";
            document.querySelector('.submitBtn').disabled = true;

            modalQrGuardado(nameFile);

        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });

}

document.addEventListener('DOMContentLoaded', function () {
    camposObligatorio();
    mostrarDatosIngrediente(listaIngredientes);

    var inputProductAmount = document.getElementById('productamount');

    inputProductAmount.addEventListener('input', function () {
        var coste = 0;
        var costPrice = document.getElementById('costPrice');
        var inputPesoPaquete = document.getElementById('cantidadPaquete');

        var costeYUnidad = costeUnidadIngredienteSeleccionado(listaIngredientes);
        console.log('costeYUnidad[0][]' + costeYUnidad[0]['costPrice']);
        console.log('inputProductAmount.value' + inputProductAmount.value);
        coste = costeYUnidad[0]['costPrice'] * inputProductAmount.value * inputPesoPaquete.value;
        costPrice.value = coste.toFixed(2);
    });


    var inputPesoPaquete = document.getElementById('cantidadPaquete');

    inputPesoPaquete.addEventListener('input', function () {
        var costPrice = document.getElementById('costPrice');
        var inputProductAmount = document.getElementById('productamount');
        var costeYUnidad = costeUnidadIngredienteSeleccionado(listaIngredientes);
        console.log('costeYUnidad[0][]' + costeYUnidad[0]['costPrice']);
        console.log('inputProductAmount.value' + inputProductAmount.value);
        costPrice.value = costeYUnidad[0]['costPrice'] * inputPesoPaquete.value * inputProductAmount.value;

    });

});




