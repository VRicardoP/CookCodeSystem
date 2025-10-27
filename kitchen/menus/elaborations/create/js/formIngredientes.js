



function mostrarValorSeleccionado() {
  var select = document.getElementById("fName");
  var valorSeleccionado = select.value;
  console.log("Valor seleccionado:", valorSeleccionado);
}

function agregarProducto(data) {
  // URL del archivo PHP que maneja la solicitud POST
  const url = "http://localhost:8080/ecommerce/apiwoo/crearProducto.php";

  const requestOptions = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  };

  // Realizar la solicitud y devolver la promesa
  return fetch(url, requestOptions)
    .then((response) => response.text()) // Convertir la respuesta a texto
    .then((data) => {
      //console.log(data); // Imprimir la respuesta
    })
    .catch((error) => {
      console.error("Error al agregar el producto:", error);
      throw error; // Lanzar el error para que pueda ser manejado fuera de la función
    });
}

document
  .getElementById("formIngrediente")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    var idIngedienteAlmacen = document.getElementById("ingrediente_id").value;

    var tipoProduct = "Ingredient";

    var input = document.getElementById("fNameInputIng");
    var idIngredient = input.getAttribute("data-id");
    console.log("Ingrediente id: " + idIngredient);
    var merma = 0.0;
    var packaging = document.getElementById("packagingIng").value;
    var cantidad = document.getElementById("productamountIng").value;
    var pesoPaquete = document.getElementById("atrValoresTiendaSelect").value;
    var unidad = document.getElementById("units");
    var textUnidad = unidad.textContent;
    var fechaElab = document.getElementById("fechaElabIng").value;
    var caducidad = document.getElementById("caducidadIng").value;
    var warehouse = document.getElementById("warehouseIng").value;
    var costCurrency = document.getElementById("costCurrencyIng").value;
    var costPrice = document.getElementById("costPriceIng").value;
    var salePrice = document.getElementById("salePriceIng").value;
    var saleCurrency = document.getElementById("saleCurrencyIng").value;

    var valoresOpciones = [];

    // Iterar sobre todas las opciones del select
    for (
      var i = 0;
      i < document.getElementById("atrValoresTiendaSelect").options.length;
      i++
    ) {
      valoresOpciones.push(
        document.getElementById("atrValoresTiendaSelect").options[i].value
      );
    }

    console.log(valoresOpciones);

    //Guardar elaborado base de datos kitchentag
    let dataToSend = {
      idIngedienteAlmacen: idIngedienteAlmacen,
      tipoProduct: tipoProduct,
      idIngredient: idIngredient,
      merma: merma,
      packaging: packaging,
      cantidad: cantidad,
      pesoPaquete: pesoPaquete,
      unidad: textUnidad,
      fechaElab: fechaElab,
      caducidad: caducidad,
      warehouse: warehouse,
      costCurrency: costCurrency,
      costPrice: costPrice,
      salePrice: salePrice,
      saleCurrency: saleCurrency,
      valoresOpciones: valoresOpciones,
    };

    $.ajax({
      url: "./../../../controllers/crearIngrediente.php",
      type: "POST",
      data: dataToSend,
      beforeSend: function () {
        // Mostrar círculo de carga antes de realizar la petición AJAX
        Swal.fire({
          title: "Please wait...",
          text: "Saving the ingredient...",
          allowOutsideClick: false, // No permitir que el usuario cierre el diálogo haciendo clic fuera
          didOpen: () => {
            Swal.showLoading(); // Muestra el círculo de carga
          },
        });
      },
      success: async function (response) {
        console.log("Raw response:", response);

        if (response.error) {
          // Cerrar el círculo de carga
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "¡New ingredient failed!",
            text: response.error, // Mostrar el mensaje de error específico
            showConfirmButton: false,
            timer: 2500,
          });
        } else if (response.success) {
          // Cerrar el círculo de carga
          Swal.close();
          Swal.fire({
            icon: "success",
            title: "¡New ingredient created!",
            text: response.success, // Mostrar mensaje de éxito
            showConfirmButton: false,
            timer: 2500,
          });
          setTimeout(function () {
            location.reload();
          }, 2500);
          // Resetear los campos del formulario

          var input = document.getElementById("fNameInputIng");
          input.setAttribute("data-id", "");
          document.getElementById("fNameInputIng").value = "";
          document.getElementById("packagingIng").value = "Bag";
          document.getElementById("productamountIng").value = "";
          document.getElementById("fechaElabIng").value = "";
          document.getElementById("caducidadIng").value = "";
          document.getElementById("warehouseIng").value = "Freezer";
          document.getElementById("costCurrencyIng").value = "Euro";
          document.getElementById("costPriceIng").value = 0;
          document.getElementById("salePriceIng").value = 0;
          document.getElementById("saleCurrencyIng").value = "Euro";
          document.getElementById("units").value = "";
          document.getElementById("atrValoresTiendaSelect").value = 0;
          document.getElementById("submitIng").disabled = true;
          document.getElementById("imagenChIng").src =
            "./../../../svg/image.svg";
          document.getElementById("formIngrediente").reset();
        }
      },
      error: function (xhr, status, error) {
        // Cerrar el círculo de carga en caso de error
        Swal.close();

        console.error("AJAX Error:", error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "There was an issue with the request. Please try again later.",
          showConfirmButton: false,
          timer: 2500,
        });
        console.log("XHR:", xhr);
        console.log("Status:", status);
        console.log("Error:", error);
      },
    });
  });

function ventaSugeridaIng() {
  var costPrice = document.getElementById("costPriceIng");

    var salePrice = document.getElementById('salePriceIng');

  //  var sale = costPrice.value * 1.5;
   // salePrice.value = sale.toFixed(2);


}

function costeUnidadIngredienteSeleccionado(listaIngredientes) {
    var inputName = document.getElementById('fNameInputIng');
    var idIngredienteSeleccionado = inputName.getAttribute('data-id');
    var costPrice = 0;
    var salePrice = 0;
    var unidad = "";
    for (var i = 0; i < listaIngredientes.length; i++) {
        if (listaIngredientes[i]['id'] == idIngredienteSeleccionado) {

            costPrice = listaIngredientes[i]['costPrice'];
            salePrice = listaIngredientes[i]['salePrice'];
            unidad = listaIngredientes[i]['unidad'];
            break; // Salir del bucle una vez encontramos la receta
        }
    }

    var costeYUnidad = [];
    costeYUnidad.push({
        costPrice: costPrice,
        salePrice: salePrice,
        unidad: unidad
    });

  return costeYUnidad;
}

function mostrarDatosIngrediente(listaIngredientes) {

    var imgReceta = document.getElementById('imagenChIng');
    var caducidad = document.getElementById('caducidadIng');
    var packaging = document.getElementById('packagingIng');
    var warehouse = document.getElementById('warehouseIng');
    var selectCantidadPaquete = document.getElementById('atrValoresTiendaSelect');
    var costPrice = document.getElementById('costPriceIng');
    var salePrice = document.getElementById('salePriceIng');
    var inputName = document.getElementById('fNameInputIng');
    var productAmount = document.getElementById('productamountIng');


    inputName.addEventListener('change', function () {
       

        var pathImagen = "";
        var inputName = document.getElementById('fNameInputIng');
        var idIngredienteSeleccionado = inputName.getAttribute('data-id');
       
        for (var i = 0; i < listaIngredientes.length; i++) {


            if (listaIngredientes[i]['id'] == idIngredienteSeleccionado) {
                console.log("Ingrediente seleccionado: ", listaIngredientes[i]);
                pathImagen = listaIngredientes[i]['imagen'];

        imgReceta.src = "./../../" + pathImagen;

        caducidad.value = listaIngredientes[i]["caducidad"];
        packaging.value = listaIngredientes[i]["packaging"];
        warehouse.value = listaIngredientes[i]["warehouse"];
        costCurrency.value = listaIngredientes[i]["saleCurrency"];
        saleCurrency.value = listaIngredientes[i]["saleCurrency"];

        productAmount.value = 1;

                // Asignar el precio de coste del ingrediente seleccionado
                costPrice.value = listaIngredientes[i]['costPrice'];

                salePrice.value = listaIngredientes[i]['salePrice'];
                 // Limpiar el select antes de agregar nuevas opciones
                 selectCantidadPaquete.innerHTML = '';

        // Agregar opciones al select
        if (
          listaIngredientes[i]["atr_valores_tienda"] &&
          listaIngredientes[i]["atr_valores_tienda"].length > 0
        ) {
          listaIngredientes[i]["atr_valores_tienda"].forEach((atr_valor) => {
            var opt = document.createElement("option");
            opt.value = atr_valor;
            opt.textContent = atr_valor; // Texto visible en el select
            selectCantidadPaquete.appendChild(opt);
          });
        } else {
          // Si no hay valores, muestra una opción predeterminada
          var defaultOption = document.createElement("option");
          defaultOption.value = "";
          defaultOption.textContent = "No disponible";
          selectCantidadPaquete.appendChild(defaultOption);
        }

        // Llamar a ventaSugerida después de un pequeño retraso (200ms)
        setTimeout(ventaSugeridaIng, 200);
        break;
      }
    }
  });
}
