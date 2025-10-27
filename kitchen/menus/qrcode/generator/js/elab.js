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

function numeroRaciones(listaRecetas) {
  var inputName = document.getElementById("fNameInput");
  var idRecetaSeleccionada = inputName.getAttribute("data-id");
  var numRaciones = 0;

  for (var i = 0; i < listaRecetas.length; i++) {
    if (listaRecetas[i]["id"] == idRecetaSeleccionada) {
      numRaciones = listaRecetas[i]["num_raciones"];

      break;
    }
  }

  return numRaciones;
}

function listaIdCantidadIngrediente(listaRecetaIngrediente) {
  var idsIngredientesReceta = [];
  var inputName = document.getElementById("fNameInput");
  var idRecetaSeleccionada = inputName.getAttribute("data-id");

  for (var i = 0; i < listaRecetaIngrediente.length; i++) {
    if (listaRecetaIngrediente[i]["receta"] == idRecetaSeleccionada) {
      idsIngredientesReceta.push({
        idIngrediente: listaRecetaIngrediente[i]["ingrediente"],
        cantidad: listaRecetaIngrediente[i]["cantidad"],
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

    console.log(numRaciones);
    console.log(inputRacionesValue);

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
  });
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
  var inputName = document.getElementById("fNameInput");

  inputName.addEventListener("change", function () {
    var inputName = document.getElementById("fNameInput");

    var list = document.getElementById("list_recipes");
    var options = list.getElementsByTagName("option");
    var inputValue = inputName.value;

    for (var i = 0; i < options.length; i++) {
      if (options[i].value === inputValue) {
        var selectedId = options[i].getAttribute("data-id");
        inputName.setAttribute("data-id", selectedId);
        console.log("Selected ID:", selectedId);
        break;
      }
    }

    var pathImagen = "";
    var inputName = document.getElementById("fNameInput");
    var idRecetaSeleccionada = inputName.getAttribute("data-id");

    for (var i = 0; i < listaRecetas.length; i++) {
      console.log(" listaRecetas: " + listaRecetas[i]["id"]);
      console.log(" idRecetaSeleccionada: " + idRecetaSeleccionada);

      if (listaRecetas[i]["id"] == selectedId) {
        pathImagen = listaRecetas[i]["imagen"];
        console.log(" pathImagen: " + pathImagen);
        imgReceta.src = "./../." + pathImagen;

        caducidad.value = listaRecetas[i]["caducidad"];
        packaging.value = listaRecetas[i]["packaging"];
        warehouse.value = listaRecetas[i]["warehouse"];
        numRaciones.value = listaRecetas[i]["num_raciones"];
        break;
      }
    }

    //   var numRaciones = numeroRaciones(listaRecetas);

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

    costPrice.value = precioCosteReceta;
  });
}
function camposObligatorio() {
  var camposObligatorios = document.querySelectorAll(".campo-obligatorio");

  var botonEnviar = document.querySelector(".submitBtn");

  // Agregar un evento input a cada campo de entrada
  camposObligatorios.forEach(function (campo) {
    campo.addEventListener("input", function () {
      var todosLlenos = Array.from(camposObligatorios).every(function (campo) {
        return campo.value.trim() !== "";
      });

      botonEnviar.disabled = !todosLlenos;
    });
  });
}

function guardarTag() {
  var idElaborado = document.getElementById("elaborado_id").value;
  var tipoProduct = "Elaborado";

  var input = document.getElementById("fNameInput");
  var idReceta = input.getAttribute("data-id");
  var packaging = document.getElementById("packaging").value;
  var productAmount = document.getElementById("productamount").value;
  var fechaElab = document.getElementById("fechaElab").value;
  var caducidad = document.getElementById("caducidad").value;
  var warehouse = document.getElementById("warehouse").value;
  var costCurrency = document.getElementById("costCurrency").value;
  var costPrice = document.getElementById("costPrice").value;
  var salePrice = document.getElementById("salePrice").value;
  var saleCurrency = document.getElementById("saleCurrency").value;
  var ingredientData = document.getElementById("ingredientData").value;
  var numRaciones = document.getElementById("numRaciones").value;

  let fechaCaducidad = new Date(fechaElab);

  fechaCaducidad.setDate(fechaCaducidad.getDate() + parseInt(caducidad));

  // Convertir la nueva fecha de caducidad a un formato legible
  fechaCaducidad = fechaCaducidad.toISOString().split("T")[0];

  //Guardar elaborado base de datos kitchentag
  let dataToSend = {
    idElaborado: idElaborado,
    tipoProduct: tipoProduct,
    idReceta: idReceta,
    packaging: packaging,
    productAmount: productAmount,
    numRaciones: numRaciones,
    fechaElab: fechaElab,
    caducidad: fechaCaducidad,
    warehouse: warehouse,
    costCurrency: costCurrency,
    costPrice: costPrice,
    salePrice: salePrice,
    saleCurrency: saleCurrency,
    ingredientData: ingredientData,
  };

  $.ajax({
    url: "crearEditarTagElaborado.php",
    type: "POST",
    data: dataToSend,
    success: function (response) {
      console.log(response);
      modalQrGuardado(response["fileName"]);

      document.getElementById("imagenCh").src = "./../../svg/image.svg";
      document.getElementById("fNameInput").value = "";
      document.getElementById("packaging").value = "Bag";
      document.getElementById("productamount").value = "";
      document.getElementById("numRaciones").value = 0;

      var today = new Date();

      // Formatear la fecha como YYYY-MM-DD
      var formattedDate =
        today.getFullYear() +
        "-" +
        ("0" + (today.getMonth() + 1)).slice(-2) +
        "-" +
        ("0" + today.getDate()).slice(-2);

      document.getElementById("fechaElab").value = formattedDate;
      document.getElementById("caducidad").value = "";
      document.getElementById("warehouse").value = "Freezer";
      document.getElementById("costCurrency").value = "Euro";
      document.getElementById("costPrice").value = 0;
      document.getElementById("salePrice").value = 0;
      document.getElementById("saleCurrency").value = "Euro";
      document.querySelector(".submitBtn").disabled = true;
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

document.addEventListener("DOMContentLoaded", function () {
  mostrarDatosReceta(listaRecetas, listaRecetaIngrediente, listaIngredients);
  calcularCosteSegunRaciones(
    listaRecetas,
    listaRecetaIngrediente,
    listaIngredients
  );
  calcularCosteSegunPaquetes(
    listaRecetas,
    listaRecetaIngrediente,
    listaIngredients
  );
  camposObligatorio();
});

document
  .getElementById("formElaborado")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    guardarTag();
  });
