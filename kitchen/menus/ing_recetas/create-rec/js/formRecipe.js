import { mostrarTablaIng, agregarFilaDinamica } from "./ingTable.js";
import {
  mostrarTablaElaborados,
  agregarFilaElaboradosDinamica,
} from "./elabTable.js";

import { BASE_URL } from './../../../../config.js';


document.addEventListener("DOMContentLoaded", function () {
  mostrarTablaIng();
  mostrarTablaElaborados();

  const btnCosteRecipe = document.getElementById("btnCosteRecipe");
  btnCosteRecipe.addEventListener("click", calcularCosteTotal);
});

function agregarListenersCosteTotal() {
  // Selecciona todos los inputs de cantidad de ingredientes y elaborados
  const cantidadInputs = document.querySelectorAll(
    'input[name^="costeFinal_"], input[name^="coste_elaborado_"]'
  );
  cantidadInputs.forEach((input) => {
    input.addEventListener("input", calcularCosteTotal); // Ejecuta al cambiar
  });
}
document.getElementById("recipeForm").addEventListener("submit", saveNewRecipe);

function getNewRecipeData(ev) {
  const RECIPE_TABLE_ELAB = document.getElementById(
    "newRecipeTable-elab-tbody"
  );
  const TABLE_ROWS_ELAB = RECIPE_TABLE_ELAB.querySelectorAll("tr");
  const RECIPE_TABLE = document.getElementById("newRecipeTable-tbody");
  const RECIPE_TYPE = document.getElementById("tipo_receta").value;
  // console.log("RECETA: " + RECIPE_TYPE);
  const RECIPE_NAME = document.getElementById("nameRecipe").value;
  const RECIPE_NUM_RATIONS = document.getElementById("rationsRecipe").value;
  const RECIPE_EXPIRES = document.getElementById("caducidad").value;
  const RECIPE_PACKAGING = document.getElementById("packaging").value;
  const RECIPE_WAREHOUSE = document.getElementById("warehouse").value;
  const TABLE_ROWS = RECIPE_TABLE.querySelectorAll("tr");
  const DATASET_ELABORADOS = document.getElementById("recetasDatalist");
  const DATASET_INGREDIENTES = document.getElementById("ingredientesDatalist");
  const INSTRUCTIONS_TEXT = document.getElementById(
    "newRecipe-instructions-textarea"
  );
  const DESCRIPCION_CORTA = document.getElementById("descripcionCorta");
  const RECIPE_CATEGORY = document.getElementById("category").value;

  const RECIPE_ELABS = [];
  const RECIPE_INGREDIENTS = [];
  const RECIPE_INSTRUCTIONS = INSTRUCTIONS_TEXT.value;
  const RECIPE_DESCRIPCION_CORTA = DESCRIPCION_CORTA.value;
  let validJson = true;

  // Procesar la tabla de elaborados
  TABLE_ROWS_ELAB.forEach((row) => {
    const ROW_CELLS = row.querySelectorAll("td");
    const COL_AMOUNT = ROW_CELLS[1];
    const AMOUNT_INPUT = COL_AMOUNT.querySelector("input");

    if (AMOUNT_INPUT && AMOUNT_INPUT.value.trim() !== "") {
      const ELAB_DATA = [];

      ROW_CELLS.forEach((cell, index) => {
        const CELL_INPUT =
          cell.querySelector("input") || cell.querySelector("span");

        let cellValue;
        if (CELL_INPUT && CELL_INPUT.tagName === "INPUT") {
          cellValue =
            CELL_INPUT.type === "number"
              ? CELL_INPUT.valueAsNumber
              : CELL_INPUT.value;
        } else if (CELL_INPUT && CELL_INPUT.tagName === "SPAN") {
          cellValue = CELL_INPUT.textContent.trim();
        }

        if (index === 0) {
          const option = DATASET_ELABORADOS.querySelector(
            `option[value="${cellValue}"]`
          );
          if (!option) {
            alert(
              `${cellValue} isn't a valid elaborated in getNewRecipeData(ev)`
            );
            validJson = false;
            return;
          }
          cellValue = option.getAttribute("data-id"); // Obtén el `id` del elaborado
          const ingredientType = option.getAttribute("type"); // "ingrediente" o "elaborado"
          ELAB_DATA.push(ingredientType); // Tipo de elaborado
        }

        ELAB_DATA.push(cellValue);
      });

      const ELAB_JSON = {
        id: ELAB_DATA[1], // ID del elaborado
        type: ELAB_DATA[0], // Tipo de elaborado
        amount: ELAB_DATA[2], // Cantidad
        totalCost: ELAB_DATA[3], // Coste total
      };

      RECIPE_ELABS.push(ELAB_JSON);
    }
  });

  // Procesar la tabla de ingredientes
  TABLE_ROWS.forEach((row) => {
    const ROW_CELLS = row.querySelectorAll("td");
    const COL_AMOUNT = ROW_CELLS[1];
    const AMOUNT_INPUT = COL_AMOUNT.querySelector("input");

    if (AMOUNT_INPUT && AMOUNT_INPUT.value.trim() !== "") {
      const INGREDIENT_DATA = [];

      ROW_CELLS.forEach((cell, index) => {
        const CELL_INPUT =
          cell.querySelector("input") || cell.querySelector("span");

        let cellValue;
        if (CELL_INPUT && CELL_INPUT.tagName === "INPUT") {
          cellValue =
            CELL_INPUT.type === "number"
              ? CELL_INPUT.valueAsNumber
              : CELL_INPUT.value;
        } else if (CELL_INPUT && CELL_INPUT.tagName === "SPAN") {
          cellValue = CELL_INPUT.textContent.trim();
        }

        if (index === 0) {
          const option = DATASET_INGREDIENTES.querySelector(
            `option[value="${cellValue}"]`
          );
          if (!option) {
            alert(
              `${cellValue} isn't a valid ingredient in getNewRecipeData(ev)`
            );
            validJson = false;
            return;
          }
          cellValue = option.getAttribute("id"); // Obtén el `id` del ingrediente
          const ingredientType = option.getAttribute("type"); // "ingrediente" o "elaborado"
          INGREDIENT_DATA.push(ingredientType); // Tipo de ingrediente
        }

        INGREDIENT_DATA.push(cellValue);
      });

      const INGREDIENT_JSON = {
        id: INGREDIENT_DATA[1], // ID del ingrediente
        type: INGREDIENT_DATA[0], // Tipo de ingrediente
        amount: INGREDIENT_DATA[2], // Cantidad
        unit: ROW_CELLS[1].querySelector("span").textContent.trim(), // Unidad de medida
        mermaPercentage: INGREDIENT_DATA[3], // Merma en porcentaje
        mermaTotal: INGREDIENT_DATA[4], // Merma total
        roughTotal: INGREDIENT_DATA[5], // Total bruto
        totalCost: INGREDIENT_DATA[6], // Coste total
      };

      RECIPE_INGREDIENTS.push(INGREDIENT_JSON);
    }
  });

  const RECETA = {
    recipe_type: RECIPE_TYPE,
    recipe_name: RECIPE_NAME,
    recipe_num_rations: RECIPE_NUM_RATIONS,
    recipe_expires: RECIPE_EXPIRES,
    recipe_packaging: RECIPE_PACKAGING,
    recipe_warehouse: RECIPE_WAREHOUSE,
    instrucciones: RECIPE_INSTRUCTIONS,
    recipe_ingredients: RECIPE_INGREDIENTS,
    recipe_elabs: RECIPE_ELABS,
    descripcion_corta: RECIPE_DESCRIPCION_CORTA,
    recipe_category: RECIPE_CATEGORY,
  };

  if (
    (RECETA.recipe_ingredients.length === 0 &&
      RECETA.recipe_elabs.length === 0) ||
    !validJson
  ) {
    return false;
  }

  return RECETA;
}

async function saveNewRecipe(ev) {
  ev.preventDefault();
  let receta = getNewRecipeData();

  if (!receta) {
    alert("Please complete the recipe details properly.");
    return;
  }

  let formData = new FormData();

  try {
    const jsonString = JSON.stringify(receta);
    // Si no hay error, el JSON es válido
    console.log("JSON válido:", jsonString);
  } catch (error) {
    console.error("Error al convertir el objeto a JSON:", error);
    alert("Hubo un error al generar el JSON de la receta.");
  }

  formData.append("receta", JSON.stringify(receta));

  console.log("Receta a enviar:", receta);

  let imagenFile = document.getElementById("imagen").files[0];
  if (imagenFile) {
    const allowedExtensions = ["jpg", "jpeg", "png"];
    const fileExtension = imagenFile.name.split(".").pop().toLowerCase();

    if (!allowedExtensions.includes(fileExtension)) {
      alert("Only .jpg and .png files are allowed");
      return; // Detiene la ejecución de la función si la extensión no es válida
    }

    formData.append("imagen", imagenFile);
  }

  console.log(formData);
  try {
    const response = await fetch("./../../../controllers/subirReceta.php", {
      method: "POST",
      body: formData,
    });

    console.log("Respuesta del servidor:", response);

    const responseText = await response.text(); // Obtener la respuesta como texto

    console.log("Respuesta del servidor:", responseText);

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    // Intentar parsear el JSON de la respuesta
    let data;
    try {
      data = JSON.parse(responseText); // Intentar analizar la respuesta como JSON
    } catch (parseError) {
      console.error("Error al parsear la respuesta como JSON:", parseError);
      alert("La respuesta del servidor no está en un formato válido.");
      return;
    }

    alert("Receta añadida correctamente!");
    document.getElementById("recipeForm").reset();
    document.getElementById("imagenCh").src = "./../../../svg/image.svg";
    document.getElementById("coste-total-elaborados").innerHTML =
      "Total Cost of Elaborados: 0.00€";
    document.getElementById("coste-total-ingredientes").innerHTML =
      "Total Cost of Ingredients: 0.00€";
    document.getElementById("costo-total-receta").textContent = "";
    document.getElementById("coste-por-racion").textContent = "";
    // Eliminar las filas dinámicas en las tablas
    resetElaboradosTable();
    resetIngredientesTable();

    const producto = formateoDatosReceta(data);

    agregarProducto(producto);

    console.log("Respuesta del servidor:", data);
    console.log("Producto a agregar:", producto);
  } catch (error) {
    console.error("Hubo un problema con la solicitud:", error);
    alert("Hubo un problema con la solicitud: " + error.message);
  }
}

function calcularCosteTotal() {
  // Calcular el coste total de los ingredientes
  const sumaCosteIngredientes = Array.from(
    document.querySelectorAll('input[name^="costeFinal_"]')
  ).reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);

  // Calcular el coste total de los elaborados (suponiendo que los campos de coste de elaborados tienen el nombre "costeElaborado_")
  const sumaCosteElaborados = Array.from(
    document.querySelectorAll('input[name^="coste_elaborado_"]')
  ).reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);

  // Sumar los costes de ingredientes y elaborados
  const costeTotalReceta = sumaCosteIngredientes + sumaCosteElaborados;

  // Mostrar el coste total de la receta
  document.getElementById("costo-total-receta").textContent =
    "The total cost of the recipe is: " + costeTotalReceta.toFixed(2) + "€";

  // Obtener el número de raciones
  const rations = parseFloat(document.getElementById("rationsRecipe").value);

  // Calcular el coste por ración, si el número de raciones es válido
  if (rations && rations > 0) {
    const costePorRacion = costeTotalReceta / rations;
    document.getElementById(
      "coste-por-racion"
    ).textContent = `The cost per ration of the recipe is: ${costePorRacion.toFixed(
      2
    )}€`;
  } else {
    document.getElementById("coste-por-racion").textContent =
      "Introduce a valid number of rations";
  }
}

function resetElaboradosTable() {
  const tableBody = document.querySelector("#elaboradosTable tbody");
  tableBody.innerHTML = ""; // Limpiar las filas de la tabla de elaborados
  agregarFilaElaboradosDinamica(tableBody, [], 0); // Reagregar la primera fila vacía (si es necesario)
}

function resetIngredientesTable() {
  const tableBody = document.querySelector("#newRecipeTable tbody");
  tableBody.innerHTML = ""; // Limpiar las filas de la tabla de ingredientes
  agregarFilaDinamica(tableBody, [], 0); // Reagregar la primera fila vacía (si es necesario)
}

function formateoDatosReceta(data) {
  let receta = getNewRecipeData();
  let precios = [];
  let categoriaId = 0;
  let tipoProducto = data.sku.split("-");

  if (tipoProducto[0] == "ELAB") {
    categoriaId = 24;
  } else if (tipoProducto[0] == "PRE") {
    categoriaId = 40;
  }
  console.log("Categoria Id: " + categoriaId);
  const producto = {
    sku: data.sku,
    name: data.name,
    type: "simple",
    description: data.instrucciones,
    short_description: data.descripcion_corta,
    images: [
      {
        src: `${BASE_URL}/kitchen/img/recipes/${data.imagen || "default.jpg"}`,
      },
    ],
    categories: [
      { id: categoriaId }, // Asegúrate de que el ID de la categoría es el correcto
    ],

    meta_data: [
      { key: "cost_price", value: data.cost_price || 0 },

      { key: "localizacion", value: data.warehouse || "Desconocida" },
      { key: "empaquetado", value: data.packaging || "Desconocido" },
      { key: "alergeno", value: data.alergeno_name || "Desconocido" },
      { key: "peso", value: data.peso_unidad || "Desconocido" },
    ],
    manage_stock: false, // Si no manejas stock, deja esto en falso
    regular_price: data.regular_price,
  };

  return producto;
}

function agregarProducto(data) {
  console.log("producto: " + JSON.stringify(data));
  // URL del archivo PHP que maneja la solicitud POST
 const url = `${BASE_URL}/ecommerce/apiwoo/crearProductoPrincipalElab.php`;

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
