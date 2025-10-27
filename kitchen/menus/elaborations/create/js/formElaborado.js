

function mostrarValorSeleccionado() {
  var select = document.getElementById("fName");
  var valorSeleccionado = select.value;
  console.log("Valor seleccionado:", valorSeleccionado);
}

function filtrarRecetas() {
  var input, filter, sugerenciasDiv, i, txtValue;
  input = document.getElementById("fNameInput");
  filter = input.value.toUpperCase();
  sugerenciasDiv = document.getElementById("sugerencias");
  sugerenciasDiv.innerHTML = ""; // Limpiar las sugerencias
  for (i = 0; i < listaRecetas.length; i++) {
    txtValue = listaRecetas[i]["nombre"];
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      // Mostrar la receta como sugerencia
      var sugerencia = document.createElement("div");
      sugerencia.innerHTML = txtValue;
      sugerencia.setAttribute(
        "onclick",
        "seleccionarReceta(" + listaRecetas[i]["id"] + ', "' + txtValue + '")'
      );
      sugerenciasDiv.appendChild(sugerencia);
    }
  }
}

function seleccionarReceta(id, nombre) {
  // Mostrar el nombre de la receta seleccionada dentro del campo de texto
  var input = document.getElementById("fNameInput");
  input.value = nombre;
  input.setAttribute("data-id", id);

  console.log("Receta seleccionada con ID:", id);
  // Ocultar las sugerencias después de seleccionar una receta
  document.getElementById("sugerencias").innerHTML = "";
}

function guardarIngrediente() {
  var ingrediente = document.getElementById("ingrediente").value;
  var cantidad = document.getElementById("ingredienteCant").value;
  var unidad = document.getElementById("unitsIng").value;
  var alergeno = document.getElementById("alergenos").value;

  // Agregar el ingrediente a la lista en el navegador
  var nuevoIngrediente = {
    ingrediente: ingrediente,
    cantidad: cantidad,
    unidad: unidad,
    alergeno: alergeno,
  };
  ingredientes.push(nuevoIngrediente);
  actualizarListaIngredientes();

  // Actualizar el campo de datos oculto con los ingredientes para enviar al servidor
  document.getElementById("ingredientData").value =
    JSON.stringify(ingredientes);
}

function actualizarListaIngredientes() {
  var textArea = document.getElementById("textarea1");

  // Crear una cadena de texto para contener los ingredientes
  var listaIngredientes = "";

  // Iterar sobre los ingredientes y agregarlos a la cadena de texto
  ingredientes.forEach(function (ingrediente) {
    listaIngredientes +=
      ingrediente["cantidad"] +
      " " +
      ingrediente["unidad"] +
      " de " +
      ingrediente["ingrediente"] +
      " Alergeno: " +
      ingrediente["alergeno"] +
      "\n";
  });

  // Asignar la cadena de texto al valor del textarea
  textArea.value = listaIngredientes;
}

function deleteIngrediente() {
  var textArea = document.getElementById("textarea1");

  textArea.value = "";
  ingredientes = [];
}

function mostrarVistaPrevia() {
  var archivo = document.getElementById("imagen").files[0];
  var vistaPrevia = document.getElementById("vista-previa");
  var contenedorVistaPrevia = document.getElementById(
    "contenedor-vista-previa"
  );

  if (archivo) {
    var lector = new FileReader();
    lector.onload = function (evento) {
      vistaPrevia.src = evento.target.result;
      vistaPrevia.style.display = "block";
    };
    lector.readAsDataURL(archivo);
  } else {
    vistaPrevia.src = "#";
    vistaPrevia.style.display = "none";
  }
}

function numeroRaciones(listaRecetas) {
  var inputName = document.getElementById("fNameInput");
  var idRecetaSeleccionada = inputName.getAttribute("data-id");
  var numRaciones = 0;

  for (var i = 0; i < listaRecetas.length; i++) {
    if (listaRecetas[i]["id"] == idRecetaSeleccionada) {
      numRaciones = listaRecetas[i]["num_raciones"];

      break; // Salir del bucle una vez encontramos la receta
    }
  }

  return numRaciones;
}

function listaIdCantidadIngrediente(listaRecetaIngrediente, listaIngredients) {
  var idsIngredientesReceta = [];
  var inputName = document.getElementById("fNameInput");
  var idRecetaSeleccionada = inputName.getAttribute("data-id");

  for (var i = 0; i < listaRecetaIngrediente.length; i++) {
    if (listaRecetaIngrediente[i]["receta"] == idRecetaSeleccionada) {
      listaIngredients.forEach((ing) => {
        if (listaRecetaIngrediente[i]["ingrediente"] == ing["id"]) {
          idsIngredientesReceta.push({
            idIngrediente: listaRecetaIngrediente[i]["ingrediente"],
            cantidad: listaRecetaIngrediente[i]["cantidad"],
            nombre: ing["fName"],
          });
        }
      });
    }
  }
  return idsIngredientesReceta;
}

function calcularCosteSegunRaciones(
  listaRecetas,
  listaRecetaIngrediente,
  listaIngredients
) {
  var inputProductamount = document.getElementById("productamount");

  var inputRaciones = document.getElementById("numRaciones");

  inputRaciones.addEventListener("input", function () {
    var costPrice = document.getElementById("costPrice");

    var numRaciones = numeroRaciones(listaRecetas);

    var idsIngredientesReceta = listaIdCantidadIngrediente(
      listaRecetaIngrediente
    );

    var costeCantidadIngrediente = 0;
    var costeMermaIngrediente = 0;

    var precioCosteReceta = 0;

    for (var i = 0; i < listaIngredients.length; i++) {
      for (var j = 0; j < idsIngredientesReceta.length; j++) {
        if (
          listaIngredients[i]["id"] == idsIngredientesReceta[j]["idIngrediente"]
        ) {
          costeCantidadIngrediente =
            listaIngredients[i]["costPrice"] *
            idsIngredientesReceta[j]["cantidad"];

          costeMermaIngrediente =
            listaIngredients[i]["merma"] * idsIngredientesReceta[j]["cantidad"];

          precioCosteReceta += costeCantidadIngrediente + costeMermaIngrediente;
        }
      }
    }

    var inputRacionesValue = inputRaciones.value;

    if (
      !isNaN(numRaciones) &&
      !isNaN(inputRacionesValue) &&
      numRaciones > 0 &&
      inputRacionesValue > 0
    ) {
      var coste = 0;
      var precioCosteRacion = precioCosteReceta / numRaciones;

      coste =
        precioCosteRacion *
        parseFloat(inputRacionesValue) *
        inputProductamount.value;
      costPrice.value = coste.toFixed(2);
    } else {
      console.log(
        "Error: numRaciones o inputRacionesValue no son números válidos."
      );
      costPrice.value = 0;
    }
    ventaSugerida();
  });
}

function calcularCosteSegunPaquetes(
  listaRecetas,
  listaRecetaIngrediente,
  listaIngredients
) {
  var inputRaciones = document.getElementById("numRaciones");

  var inputProductamount = document.getElementById("productamount");
  inputProductamount.addEventListener("input", function () {
    var numRaciones = numeroRaciones(listaRecetas);

    var idsIngredientesReceta = listaIdCantidadIngrediente(
      listaRecetaIngrediente
    );

    var costeCantidadIngrediente = 0;
    var costeMermaIngrediente = 0;

    var precioCosteReceta = 0;

    for (var i = 0; i < listaIngredients.length; i++) {
      for (var j = 0; j < idsIngredientesReceta.length; j++) {
        if (
          listaIngredients[i]["id"] == idsIngredientesReceta[j]["idIngrediente"]
        ) {
          costeCantidadIngrediente =
            listaIngredients[i]["costPrice"] *
            idsIngredientesReceta[j]["cantidad"];

          costeMermaIngrediente =
            listaIngredients[i]["merma"] * idsIngredientesReceta[j]["cantidad"];

          precioCosteReceta += costeCantidadIngrediente + costeMermaIngrediente;
        }
      }
    }

    var inputRacionesValue = inputRaciones.value;

    // console.log(numRaciones);
    // console.log(inputRacionesValue);

    if (!isNaN(numRaciones) && !isNaN(inputRacionesValue)) {
      var coste = 0;
      var precioCosteRacion = precioCosteReceta / numRaciones;

      coste =
        precioCosteRacion *
        parseFloat(inputRacionesValue) *
        inputProductamount.value;
      costPrice.value = coste.toFixed(2);
    } else {
      console.log(
        "Error: numRaciones o inputRacionesValue no son números válidos."
      );
      costPrice.value = 0;
    }

    ventaSugerida();
  });
}

function ventaSugerida() {
  var costPrice = document.getElementById("costPrice");

  var salePrice = document.getElementById("salePrice");

  // Calcula el precio de venta multiplicando por 1.5
  var calculatedPrice = costPrice.value * 1.5;

  // Redondea a 2 decimales y asigna el valor al campo de precio de venta
  salePrice.value = calculatedPrice.toFixed(2);
}

function mostrarVistaPrevia() {
  let archivo = document.getElementById("imagen").files[0];
  let imagenForm = document.getElementById("imagenCh");
  let parrafoForm = document.getElementById("pCh");

  if (archivo) {
    // Verificar si el archivo es una imagen
    if (archivo.type.startsWith("image/")) {
      var lector = new FileReader();
      lector.onload = function (evento) {
        imagenForm.src = evento.target.result;
        imagenForm.style.display = "block";
      };
      parrafoForm.textContent = archivo.name;
      lector.readAsDataURL(archivo);
    } else {
      // Si no es una imagen, mostrar un mensaje de error
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Please select a valid image file.",
        confirmButtonText: "OK",
      });

      // Limpiar el valor del input de archivo para deseleccionar el archivo no válido
      parrafoForm.textContent = "Click on the photo to Insert Image";
      imagenForm.value = "";
      imagenForm.src = "../svg/image.svg";
      imagenForm.style.display = "block";
    }
  } else {
    parrafoForm.textContent = "Click on the photo to Insert Image";
    imagenForm.src = "../svg/image.svg";
    imagenForm.style.display = "block";
  }
}

function mostrarDatosReceta(
  listaRecetas,
  listaRecetaIngrediente,
  listaIngredients
) {
  var imgReceta = document.getElementById("imagenCh");
  var caducidad = document.getElementById("caducidad");
  var packaging = document.getElementById("packaging");
  var warehouse = document.getElementById("warehouse");
  var numRaciones = document.getElementById("numRaciones");
  var costPrice = document.getElementById("costPrice");
  var salePrice = document.getElementById("salePrice");
  var inputName = document.getElementById("fNameInput");
  var inputProductamount = document.getElementById("productamount");

  function recalcularCosteYVenta() {
    var numRacionesBase = numeroRaciones(listaRecetas);
    var idsIngredientesReceta = listaIdCantidadIngrediente(
      listaRecetaIngrediente,
      listaIngredients
    );
    var costeCantidadIngrediente = 0;
    var costeMermaIngrediente = 0;
    var precioCosteReceta = 0;
    for (var i = 0; i < listaIngredients.length; i++) {
      for (var j = 0; j < idsIngredientesReceta.length; j++) {
        if (
          listaIngredients[i]["id"] == idsIngredientesReceta[j]["idIngrediente"]
        ) {
          costeCantidadIngrediente =
            listaIngredients[i]["costPrice"] *
            idsIngredientesReceta[j]["cantidad"];
          costeMermaIngrediente =
            listaIngredients[i]["merma"] * idsIngredientesReceta[j]["cantidad"];
          precioCosteReceta += costeCantidadIngrediente + costeMermaIngrediente;
        }
      }
    }
    var inputRacionesValue = parseFloat(numRaciones.value) || 1;
    var inputProductamountValue = parseFloat(inputProductamount.value) || 1;
    if (
      !isNaN(numRacionesBase) &&
      !isNaN(inputRacionesValue) &&
      numRacionesBase > 0 &&
      inputRacionesValue > 0
    ) {
      var precioCosteRacion = precioCosteReceta / numRacionesBase;
      var coste =
        precioCosteRacion * inputRacionesValue * inputProductamountValue;
      costPrice.value = coste.toFixed(2);
      salePrice.value = (coste * 1.5).toFixed(2);
    } else {
      costPrice.value = 0;
      salePrice.value = 0;
    }
  }

  inputName.addEventListener("change", function () {
    var pathImagen = "";
    var idRecetaSeleccionada = inputName.getAttribute("data-id");
    for (var i = 0; i < listaRecetas.length; i++) {
      if (listaRecetas[i]["id"] == idRecetaSeleccionada) {
        pathImagen = listaRecetas[i]["imagen"];
        imgReceta.src = "./../../" + pathImagen;
        caducidad.value = listaRecetas[i]["caducidad"];
        packaging.value = listaRecetas[i]["packaging"];
        warehouse.value = listaRecetas[i]["warehouse"];
        numRaciones.value = listaRecetas[i]["num_raciones"];
        break;
      }
    }
    recalcularCosteYVenta();
  });

  numRaciones.addEventListener("input", recalcularCosteYVenta);
  inputProductamount.addEventListener("input", recalcularCosteYVenta);
}

function agregarProducto(data) {
  // URL del archivo PHP que maneja la solicitud POST
const url = `${BASE_URL}/ecommerce/apiwoo/crearProducto.php`;

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

function CambiaStock(sku, nuevoStock) {
  var url = `${BASE_URL}/ecommerce/apiwoo/cambiarStock.php`;
  var data = new URLSearchParams();
  data.append("sku", sku);
  data.append("nuevoStock", nuevoStock);

  fetch(url, {
    method: "POST",
    body: data,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        document.body.innerHTML += data.message + "<br>";
        console.log(data.message);
      } else {
        console.log(data.success);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

document
  .getElementById("formElaborado")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    var idElaborado = document.getElementById("elaborado_id").value;
    var tipoProduct = "Elaborado";
    var input = document.getElementById("fNameInput");
    var idReceta = input.getAttribute("data-id");
    var packaging = document.getElementById("packaging").value;
    var numRaciones = document.getElementById("numRaciones").value;
    var productAmount = document.getElementById("productamount").value;
    var fechaElab = document.getElementById("fechaElab").value;
    var caducidad = document.getElementById("caducidad").value;
    var warehouse = document.getElementById("warehouse").value;
    var costCurrency = document.getElementById("costCurrency").value;
    var costPrice = document.getElementById("costPrice").value;
    var salePrice = document.getElementById("salePrice").value;
    var saleCurrency = document.getElementById("saleCurrency").value;
    var ingredientData = document.getElementById("ingredientData").value;

    var merma = 0.0;

    //Guardar elaborado base de datos kitchentag
    let dataToSend = {
      idElaborado: idElaborado,
      tipoProduct: tipoProduct,
      idReceta: idReceta,
      merma: merma,
      packaging: packaging,
      numRaciones: numRaciones,
      productAmount: productAmount,
      fechaElab: fechaElab,
      caducidad: caducidad,
      warehouse: warehouse,
      costCurrency: costCurrency,
      costPrice: costPrice,
      salePrice: salePrice,
      saleCurrency: saleCurrency,
      ingredientData: ingredientData,
    };

    console.log("Data to send:", dataToSend);

    $.ajax({
      url: "./../../../controllers/crearElaborado.php",
      type: "POST",
      data: dataToSend,
      dataType: "json",
      beforeSend: function () {
        // Mostrar círculo de carga antes de realizar la petición AJAX
        Swal.fire({
          title: "Please wait...",
          text: "Saving the elaboration...",
          allowOutsideClick: false, // No permitir que el usuario cierre el diálogo haciendo clic fuera
          didOpen: () => {
            Swal.showLoading(); // Muestra el círculo de carga
          },
        });
      },
      success: async function (response) {
        if (response.error) {
          // Cerrar el círculo de carga
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "¡New elaborated failed!",
            text: response.error, // Mostrar el mensaje de error específico
            showConfirmButton: true, // Mostrar el botón de confirmación
          });
        } else if (response.success) {
          // Cerrar el círculo de carga
          Swal.close();
          Swal.fire({
            icon: "success",
            title: "¡New elaborated created!",
            text: response.success, // Mostrar mensaje de éxito
            showConfirmButton: false,
            timer: 2500,
          });

          // Resetear los campos del formulario
          resetForm();
          // setTimeout(function () {
          //   location.reload();
          // }, 2500);
        }

        console.log("Response:", response);
      },
      error: function (xhr, status, error) {
        console.log("Respuesta cruda del servidor:", xhr.responseText);

        // Cerrar el círculo de carga
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

    // Función para resetear el formulario
    function resetForm() {
      document.getElementById("fNameInput").value = "";
      document.getElementById("packaging").value = "Bag";
      document.getElementById("productamount").value = "";
      document.getElementById("fechaElab").value = "";
      document.getElementById("caducidad").value = "";
      document.getElementById("warehouse").value = "Freezer";
      document.getElementById("costCurrency").value = "Euro";
      document.getElementById("costPrice").value = 0;
      document.getElementById("salePrice").value = 0;
      document.getElementById("saleCurrency").value = "Euro";
      document.getElementById("numRaciones").value = 0;
      document.getElementById("submitElab").disabled = true;
      document.getElementById("imagenCh").src = "./../../../svg/image.svg";
    }
  });

/*

$(document).ready(function () {
    let form = document.querySelector('#formElaborado');

    form.addEventListener('submit', function (event) {

        //Guardar imagen en kitchen/img
        let formData = new FormData(); // Crea un objeto FormData
      //  let fileInput = document.getElementById('imagen').files[0]; // Obtiene el archivo seleccionado

        formData.append('image', fileInput);

        fetch('procesar_imagen.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.ok) {
                    console.log('Imagen subida exitosamente');

                } else {
                    console.error('Error al subir la imagen');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

        //Guardar imagen en ecommerce
        event.preventDefault();
       var formDataEcommerce = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost:8080/ecommerce/apiwoo/crearProducto.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
            } else {
                console.error('Error en la solicitud.');
            }
        };
        xhr.send(formDataEcommerce);
        Swal.fire({
            icon: 'success',
            title: '¡New Elaboration!',
            text: 'Elaboration was created succesfully',
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            //form.submit();
        });
      
  //  });
//});
*/

/*
document.addEventListener('click', function (event) {
    var sugerenciasDiv = document.getElementById('sugerencias');
    if (!sugerenciasDiv.contains(event.target)) {
        sugerenciasDiv.innerHTML = ""; // Cerrar sugerencias
    }
});
*/

function recalcularCosteYVenta(
  listaRecetas,
  listaRecetaIngrediente,
  listaIngredients
) {
  var inputRaciones = document.getElementById("numRaciones");
  var inputProductamount = document.getElementById("productamount");
  var costPrice = document.getElementById("costPrice");
  var salePrice = document.getElementById("salePrice");

  var numRaciones = numeroRaciones(listaRecetas);
  var idsIngredientesReceta = listaIdCantidadIngrediente(
    listaRecetaIngrediente,
    listaIngredients
  );

  var costeCantidadIngrediente = 0;
  var costeMermaIngrediente = 0;
  var precioCosteReceta = 0;

  for (var i = 0; i < listaIngredients.length; i++) {
    for (var j = 0; j < idsIngredientesReceta.length; j++) {
      if (
        listaIngredients[i]["id"] == idsIngredientesReceta[j]["idIngrediente"]
      ) {
        costeCantidadIngrediente =
          listaIngredients[i]["costPrice"] *
          idsIngredientesReceta[j]["cantidad"];
        costeMermaIngrediente =
          listaIngredients[i]["merma"] * idsIngredientesReceta[j]["cantidad"];
        precioCosteReceta += costeCantidadIngrediente + costeMermaIngrediente;
      }
    }
  }

  var inputRacionesValue = parseFloat(inputRaciones.value) || 1;
  var inputProductamountValue = parseFloat(inputProductamount.value) || 1;

  if (
    !isNaN(numRaciones) &&
    !isNaN(inputRacionesValue) &&
    numRaciones > 0 &&
    inputRacionesValue > 0
  ) {
    var precioCosteRacion = precioCosteReceta / numRaciones;
    var coste =
      precioCosteRacion * inputRacionesValue * inputProductamountValue;
    costPrice.value = coste.toFixed(2);
    salePrice.value = (coste * 1.5).toFixed(2);
  } else {
    costPrice.value = 0;
    salePrice.value = 0;
  }
}

function activarRecalculoCosteVenta(
  listaRecetas,
  listaRecetaIngrediente,
  listaIngredients
) {
  var inputRaciones = document.getElementById("numRaciones");
  var inputProductamount = document.getElementById("productamount");
  inputRaciones.addEventListener("input", function () {
    recalcularCosteYVenta(
      listaRecetas,
      listaRecetaIngrediente,
      listaIngredients
    );
  });
  inputProductamount.addEventListener("input", function () {
    recalcularCosteYVenta(
      listaRecetas,
      listaRecetaIngrediente,
      listaIngredients
    );
  });
}
