function mostrarDatosPreelaborados(listaPre, listaPreIng, listaIng) {
  var idPre = document.getElementById("idPre");
  var nombrePre = document.getElementById("nombrePre");
  var imgPre = document.getElementById("imagenPre");
  var cantidadPre = document.getElementById("cantidadPre");
  var racionesPre = document.getElementById("racionesPre");
  var caducidadPre = document.getElementById("caducidadPre");
  var empaquetadoPre = document.getElementById("empaquetadoPre");
  var localizacionPre = document.getElementById("localizacionPre");
  var precioCostPre = document.getElementById("precioCostPre");
  var precioVentaPre = document.getElementById("precioVentaPre");

  function calcularPrecios() {
    // Buscar el preelaborado seleccionado
    const pre = listaPre.find((p) => p["nombre"] === nombrePre.value);
    if (!pre) return;
    // Tomar valores actuales de cantidad y raciones
    const cantidad = parseFloat(cantidadPre.value) || 1;
    const raciones = parseFloat(racionesPre.value) || 1;
    // Calcular Precios
    var precioCoste = 0;
    listaPreIng.forEach((preIng) => {
      if (pre["id"] === preIng["receta_id"]) {
        listaIng.forEach((ing) => {
          if (ing["id"] === preIng["ingrediente_id"]) {
            let precioMerma = ing["merma"] * preIng["cantidad"];
            // Ajustar por cantidad y raciones
            let factor =
              (cantidad * raciones) /
              ((pre["cantidad"] || 1) * (pre["raciones"] || 1));
            precioCoste +=
              (preIng["cantidad"] * ing["precio"] + precioMerma) * factor;
          }
        });
      }
    });
    precioCostPre.value = precioCoste.toFixed(2);
    var precioVenta = precioCoste * 1.5;
    precioVentaPre.value = precioVenta.toFixed(2);
  }

  nombrePre.addEventListener("change", function () {
    listaPre.forEach((pre) => {
      if (pre["nombre"] === nombrePre.value) {
        idPre.value = pre["id"];
        imgPre.src = "../../" + pre["imagen"];
        racionesPre.value = pre["raciones"];
        cantidadPre.value = pre["cantidad"] || 1;
        caducidadPre.value = pre["caducidad"];
        empaquetadoPre.value = pre["empaquetado"];
        localizacionPre.value = pre["localizacion"];
        calcularPrecios();
      }
    });
  });

  racionesPre.addEventListener("input", calcularPrecios);
  cantidadPre.addEventListener("input", calcularPrecios);
}

function activarBoton() {
  var camposObligatoriosPre = document.querySelectorAll(".obligatorioPre");
  var submitPre = document.getElementById("submitPre");

  camposObligatoriosPre.forEach((campo) => {
    campo.addEventListener("input", function () {
      $llenos = true;

      camposObligatoriosPre.forEach((campo) => {
        if (campo.value === "") {
          $llenos = false;
        }
      });

      // Si todos los campos obligatorios están llenos, habilitamos el botón de enviar
      submitPre.disabled = !$llenos;
    });
  });
}

crearEtiqueta();

function crearEtiqueta() {
  var form = document.getElementById("formPre");

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Evitar el envío del formulario

    var idPre = document.getElementById("idPre").value;
    // console.log("ID del preelaborado: " + idPre);
    var tipoProducto = "Pre-Elaborado";
    // console.log("Tipo de producto: " + tipoProducto);
    var nombrePre = document.getElementById("nombrePre").value;
    // console.log("Nombre del preelaborado: " + nombrePre);
    var empaquetadoPre = document.getElementById("empaquetadoPre").value;
    // console.log("Tipo de empaquetado: " + empaquetadoPre);
    var racionesPre = document.getElementById("racionesPre").value;
    // console.log("Raciones: " + racionesPre);
    var cantidadPre = document.getElementById("cantidadPre").value;
    // console.log("Cantidad: " + cantidadPre);
    var fechaElabPre = document.getElementById("fechaElabPre").value;
    // console.log("Fecha de elaboración: " + fechaElabPre);
    var caducidadPre = document.getElementById("caducidadPre").value;
    // console.log("Caducidad días: " + caducidadPre);
    var localizacionPre = document.getElementById("localizacionPre").value;
    // console.log("Localización: " + localizacionPre);
    var precioCostPre = document.getElementById("precioCostPre").value;
    // console.log("Precio de coste: " + precioCostPre);
    var precioVentaPre = document.getElementById("precioVentaPre").value;
    // console.log("Precio de venta: " + precioVentaPre);
    var monedaCost = document.getElementById("monedaCost").value;
    // console.log("Moneda: " + monedaCost);
    var monedaVenta = document.getElementById("monedaVenta").value;
    // console.log("Moneda: " + monedaVenta);

    // Guardar el Preelaborado en un objeto js
    let data = {
      idElaborado: idPre,
      tipoProduct: tipoProducto,
      idReceta: idPre,
      merma: 0,
      packaging: empaquetadoPre,
      numRaciones: racionesPre,
      productAmount: cantidadPre,
      fechaElab: fechaElabPre,
      caducidad: caducidadPre,
      warehouse: localizacionPre,
      costCurrency: monedaCost,
      costPrice: precioCostPre,
      salePrice: precioVentaPre,
      saleCurrency: monedaVenta,
      ingredientData: 0,
    };

    console.log("Data:", data);

    // Petición asincrona a crearElaborado.php para crear el elaborado
    $.ajax({
      url: "./../../../controllers/crearElaborado.php",
      type: "POST",
      data: data,
      dataType: "json",
      beforeSend: function () {
        // Circulo de carga
        Swal.fire({
          title: "Please wait...",
          text: "Saving the elaboration...",
          allowOutsideClick: false, // Impedir que se cierre cuando el usuario hace click fuera
          didOpen: () => {
            Swal.showLoading(); // Muestra el círculo de carga
          },
        });
      },
      // Si se recibe respuesta
      success: async function (response) {
        if (response.error) {
          Swal.close(); // Cerrar el círculo de carga
          Swal.fire({
            icon: "error",
            title: "¡New elaborated failed!",
            text: response.error,
            showConfirmButton: true,
          });
        } else if (response.success) {
          Swal.close();
          Swal.fire({
            icon: "success",
            title: "¡New elaborated created!",
            text: response.success,
            showConfirmButton: false,
            timer: 2500,
          });

          resetForm(); // Resetear el formulario
          setTimeout(function () {
            location.reload(); // Recargar la página después de 2.5 segundos
          }, 2500);
        }

        console.log("Response:", response);
      },
      error: function (xhr, status, error) {
        console.log("Respuesta del servidor:" + xhr.responseText);
        console.log("XHR Status:", xhr.status);

        Swal.close();
        console.error("AJAX Error:", error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "There was an issue with the request. Please try again later",
          showConfirmButton: true,
          timer: 2500,
        });
        console.log("XHR:", xhr);
        console.log("Status:", status);
        console.log("Error:", error);
      },
    });
  });
}

function resetForm() {
  document.getElementById("imagenPre").src = "./../../../svg/image.svg";
  document.getElementById("nombrePre").value = "";
  document.getElementById("cantidadPre").value = 1;
  document.getElementById("racionesPre").value = 0;
  document.getElementById("fechaElabPre").value = "";
  document.getElementById("caducidadPre").value = 0;
  document.getElementById("empaquetadoPre").value = "Bag";
  document.getElementById("localizacionPre").value = "Freezer";
  document.getElementById("precioCostPre").value = 0;
  document.getElementById("monedaCost").value = "Euro";
  document.getElementById("precioVentaPre").value = 0;
  document.getElementById("monedaVenta").value = "Euro";
  document.getElementById("idPre").value = 0;
}
