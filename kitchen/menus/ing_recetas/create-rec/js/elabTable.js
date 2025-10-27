import {
  obtenerRecetas,
  obtenerIngredientesReceta,
  obtenerPreelaborados,
} from "./getData.js";

export function agregarFilaElaboradosDinamica(tableBody, listaRecetas, i) {
  const newRow = document.createElement("tr");
  let filaCompletada = false; // Variable para rastrear si la fila está completa

  const cells = ["nombre", "raciones", "cantidad", "coste"];

  cells.forEach((type) => {
    const cell = document.createElement("td");

    if (type === "cantidad") {
      const input = document.createElement("input");
      input.name = `${type}_elaborado_${i}`;
      input.type = "number";
      input.setAttribute("step", "0.01");

      input.addEventListener("input", function () {
        actualizarFilaElaborado(i);

        // Solo se llama a `agregarFilaElaboradosDinamica` si la fila está completa por primera vez
        if (!filaCompletada && isLastFilledElaboradoRow(tableBody, i)) {
          agregarFilaElaboradosDinamica(tableBody, listaRecetas, i + 1);
          filaCompletada = true; // Marca que la fila está completa
        }
      });

      cell.appendChild(input);
      newRow.appendChild(cell);
    } else if (type === "raciones") {
      // Crear un campo de solo lectura para las raciones
      const racionesInput = document.createElement("input");
      racionesInput.name = `${type}_elaborado_${i}`;
      racionesInput.type = "number";
      racionesInput.readOnly = true; // Solo lectura para raciones

      cell.appendChild(racionesInput);
      newRow.appendChild(cell);
    } else {
      const input = document.createElement("input");
      input.name = `${type}_elaborado_${i}`;
      input.setAttribute("step", "0.01");

      if (type === "coste") {
        input.setAttribute("readonly", true); // Campo de solo lectura
      }

      if (type === "nombre") {
        input.setAttribute("list", "recetasDatalist");
        input.addEventListener("input", function () {
          actualizarFilaElaborado(i);

          const option = document.querySelector(
            `#recetasDatalist option[value="${input.value}"]`
          );
          if (option) {
            const recetaId = option.getAttribute("data-id");
            input.setAttribute("data-id", recetaId);

            // Obtener y asignar el valor de raciones para la receta seleccionada
            const receta = listaRecetas.find(
              (r) => r.id === parseInt(recetaId)
            );
            if (receta) {
              const racionesInput = document.querySelector(
                `input[name="raciones_elaborado_${i}"]`
              );
              racionesInput.value = receta.num_raciones || ""; // Asigna el número de raciones
            }
          } else {
            input.removeAttribute("data-id"); // Quitar data-id si la opción no es válida
            document.querySelector(
              `input[name="raciones_elaborado_${i}"]`
            ).value = ""; // Limpiar raciones
          }

          // Agregar una nueva fila solo si la fila actual está completa
          if (!filaCompletada && isLastFilledElaboradoRow(tableBody, i)) {
            agregarFilaElaboradosDinamica(tableBody, listaRecetas, i + 1);
            filaCompletada = true;
          }
        });
      }

      cell.appendChild(input);
      newRow.appendChild(cell);
    }
  });

  tableBody.appendChild(newRow);
}

// Verificar si es la última fila completa
function isLastFilledElaboradoRow(tableBody, currentIndex) {
  const nombreInput = tableBody.querySelector(
    `input[name="nombre_elaborado_${currentIndex}"]`
  );
  const cantidadInput = tableBody.querySelector(
    `input[name="cantidad_elaborado_${currentIndex}"]`
  );

  return (
    nombreInput &&
    nombreInput.value.trim() !== "" &&
    nombreInput.getAttribute("data-id") && // Verifica si tiene un ID válido
    cantidadInput &&
    cantidadInput.value.trim() !== ""
  );
}

// Función para actualizar los campos de la fila de elaborados
function actualizarFilaElaborado(rowIndex) {
  const nombreInput = document.querySelector(
    `input[name="nombre_elaborado_${rowIndex}"]`
  );
  const cantidadInput = document.querySelector(
    `input[name="cantidad_elaborado_${rowIndex}"]`
  );
  const costeInput = document.querySelector(
    `input[name="coste_elaborado_${rowIndex}"]`
  );

  const recetaId = nombreInput.getAttribute("data-id");
  const cantidad = parseFloat(cantidadInput.value);

  if (recetaId && !isNaN(cantidad) && cantidad > 0) {
    obtenerIngredientesReceta(recetaId)
      .then(function (ingredientes) {
        const costeRecetaBase = ingredientes.reduce((total, ingrediente) => {
          return total + ingrediente.cantidad * ingrediente.costPrice;
        }, 0);

        const costeFinal = costeRecetaBase * cantidad;
        costeInput.value = costeFinal.toFixed(2) + "€";

        // Llama a calcularCosteTotalElaborados para actualizar el coste total
        calcularCosteTotalElaborados();
      })
      .catch(console.error);
  } else {
    // Limpiar el coste si falta el nombre o cantidad
    costeInput.value = "";
    calcularCosteTotalElaborados(); // Llamar también aquí para actualizar el total si el valor se limpia
  }
}

// Función para mostrar la tabla de elaborados
export function mostrarTablaElaborados() {
  const tableBody = document.querySelector("#elaboradosTable tbody");
  tableBody.innerHTML = ""; // Limpiar el contenido anterior

  obtenerPreelaborados()
    .then(function (listaRecetas) {
      if (!document.getElementById("recetasDatalist")) {
        const recetasDatalist = document.createElement("datalist");
        recetasDatalist.id = "recetasDatalist";
        document.body.appendChild(recetasDatalist);

        listaRecetas.forEach((receta) => {
          const option = document.createElement("option");
          option.value = receta.receta;
          option.setAttribute("data-id", receta.id);
          recetasDatalist.appendChild(option);
        });
      }

      // Agregar la primera fila
      agregarFilaElaboradosDinamica(tableBody, listaRecetas, 0);
    })
    .catch(console.error);
}

function calcularCosteTotalElaborados() {
  // Sumar todos los valores de coste en los campos de la tabla de elaborados
  const totalCoste = Array.from(
    document.querySelectorAll('input[name^="coste_elaborado_"]')
  ).reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);

  // Mostrar el total en un elemento HTML (crea uno con el ID "coste-total-elaborados")
  document.getElementById(
    "coste-total-elaborados"
  ).textContent = `Total Cost of Elaborados: ${totalCoste.toFixed(2)}€`;
}
