import {  obtenerIngredientes } from './getData.js';


// Modificar la función agregarFilaDinamica para llamar a calcularCosteTotalIngredientes cuando se agrega una fila
 export function agregarFilaDinamica(tableBody, nombresIngredientes, i) {
    const newRow = document.createElement("tr");
    let filaCompletada = false; // Variable para rastrear el estado de la fila

    const cells = ["ingredient", "amount", "merma", "totalMerma", "totalBruto", "costeFinal"];

    cells.forEach(type => {
        const cell = document.createElement("td");

        if (type === "amount") {
            const amountContainer = document.createElement("div");
            amountContainer.style.display = "flex";
            amountContainer.style.alignItems = "center";

            const input = document.createElement("input");
            input.name = `${type}_${i}`;
            input.type = "number";
            input.setAttribute("step", "0.01");
            
            input.addEventListener('input', function() {
                actualizarFila(i);

                // Solo se llama a `agregarFilaDinamica` si la fila está completa por primera vez
                if (!filaCompletada && isLastFilledRow(tableBody, i)) {
                    agregarFilaDinamica(tableBody, nombresIngredientes, i + 1);
                    filaCompletada = true; // Marca que la fila se completó
                }
            });

            const unitSpan = document.createElement("span");
            unitSpan.className = `unit_${i}`;
            unitSpan.style.marginLeft = "5px";
            unitSpan.textContent = "";

            amountContainer.appendChild(input);
            amountContainer.appendChild(unitSpan);
            cell.appendChild(amountContainer);
            newRow.appendChild(cell);
        } else {
            const input = document.createElement("input");
            input.name = `${type}_${i}`;
            input.setAttribute('step', '0.01');
            
            if (["merma", "totalMerma", "totalBruto", "costeFinal"].includes(type)) {
                input.setAttribute('readonly', true);
            }

            if (type === "ingredient") {
                input.setAttribute("list", "ingredientesDatalist");
                input.addEventListener('input', function() {
                    actualizarFila(i);

                    const option = document.querySelector(`#ingredientesDatalist option[value="${input.value}"]`);
                    const unitSpan = document.querySelector(`.unit_${i}`);
                    if (option && unitSpan) {
                        unitSpan.textContent = option.getAttribute("data-unit") || "";
                    }

                    if (!filaCompletada && isLastFilledRow(tableBody, i)) {
                        agregarFilaDinamica(tableBody, nombresIngredientes, i + 1);
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

function isLastFilledRow(tableBody, currentIndex) {
    const ingredientInput = tableBody.querySelector(`input[name="ingredient_${currentIndex}"]`);
    const amountInput = tableBody.querySelector(`input[name="amount_${currentIndex}"]`);
    
    // Solo revisa si ingrediente y cantidad están completos
    return (
        ingredientInput && ingredientInput.value.trim() !== "" &&
        amountInput && amountInput.value.trim() !== ""
    );
}


// Función para calcular el coste total de todos los ingredientes
function calcularCosteTotalIngredientes() {
    // Obtener todos los valores de coste final de los ingredientes
    const sumaCosteTotal = Array.from(document.querySelectorAll('input[name^="costeFinal_"]'))
        .reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);

    // Mostrar el total en el div con el ID "coste-total-ingredientes"
    document.getElementById("coste-total-ingredientes").textContent = 'Total Cost of Ingredients: ' + sumaCosteTotal.toFixed(2) + '€';

   
}

// Modificar la función actualizarFila para llamar a calcularCosteTotalIngredientes
function actualizarFila(rowIndex) {
    const ingredientInput = document.querySelector(`input[name="ingredient_${rowIndex}"]`);
    const amountInput = document.querySelector(`input[name="amount_${rowIndex}"]`);
    const mermaInput = document.querySelector(`input[name="merma_${rowIndex}"]`);
    const totalMermaInput = document.querySelector(`input[name="totalMerma_${rowIndex}"]`);
    const totalBrutoInput = document.querySelector(`input[name="totalBruto_${rowIndex}"]`);
    const costeFinalInput = document.querySelector(`input[name="costeFinal_${rowIndex}"]`);

    const ingredientName = ingredientInput.value;
    const amount = parseFloat(amountInput.value);

    if (ingredientName && !isNaN(amount) && amount > 0) {
        const option = document.querySelector(`#ingredientesDatalist option[value="${ingredientName}"]`);
        if (!option) {
            alert(`${ingredientName} no es un ingrediente válido.`);
            return;
        }

        // Extraemos el coste y la merma del ingrediente
        const cost = parseFloat(option.getAttribute("data-cost"));
        const mermaPercentage = parseFloat(option.getAttribute("data-merma"));

        // Calculamos los valores
        const totalMerma = amount * (mermaPercentage / 100);
        const totalBruto = amount + totalMerma;
        const costeFinal = totalBruto * cost;

        // Actualizamos el span de unidad
        const unitSpan = document.querySelector(`.unit_${rowIndex}`);
        unitSpan.textContent = option.getAttribute("data-unit") || "";

        // Asignamos valores calculados a los campos correspondientes
        mermaInput.value = `${mermaPercentage.toFixed(2)}%`;
        totalMermaInput.value = totalMerma.toFixed(3) + unitSpan.textContent;
        totalBrutoInput.value = totalBruto.toFixed(2) + unitSpan.textContent;
        costeFinalInput.value = costeFinal.toFixed(2);

        // Llamar a calcularCosteTotalIngredientes para actualizar el total
        calcularCosteTotalIngredientes();
    } else {
        // Limpiamos los valores si falta el ingrediente o la cantidad
        mermaInput.value = "";
        totalMermaInput.value = "";
        totalBrutoInput.value = "";
        costeFinalInput.value = "";
        
        // También llamamos a calcularCosteTotalIngredientes para actualizar el total
        calcularCosteTotalIngredientes();
    }
}

 function calcularCosteTotal() {
    const sumaCosteTotal = Array.from(document.querySelectorAll('input[name^="costeFinal_"]'))
        .reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
    
    document.getElementById("costo-total-receta").textContent = 'The total cost of the recipe is: ' + sumaCosteTotal.toFixed(2) + '€';
    const rations = parseFloat(document.getElementById("rationsRecipe").value);
    document.getElementById("coste-por-racion").textContent = `The cost per ration of the recipe is: ${(sumaCosteTotal / rations).toFixed(2)}€`;
}




export function mostrarTablaIng() {
    const form = document.getElementById("recipeForm");
    form.style.display = "block";

    const tableBody = document.querySelector("#newRecipeTable tbody");
    tableBody.innerHTML = ""; // Limpiar cualquier contenido anterior

    // Obtener los ingredientes desde la base de datos
    obtenerIngredientes().then(function (ingredientes) {
        if (!document.getElementById("ingredientesDatalist")) {
            const ingredientesDatalist = document.createElement("datalist");
            ingredientesDatalist.id = "ingredientesDatalist";
            form.appendChild(ingredientesDatalist);

            ingredientes.forEach(ingrediente => {
                const option = document.createElement("option");
                option.value = ingrediente.producto;
                option.setAttribute("id", ingrediente.id);
                option.setAttribute("data-cost", ingrediente.precio); // Cambiado a "precio"
                option.setAttribute("data-merma", (ingrediente.merma * 100).toFixed(2)); // Convertir a porcentaje
                option.setAttribute("data-unit", ingrediente.unidad);
                ingredientesDatalist.appendChild(option);
            });
        }

        agregarFilaDinamica(tableBody, ingredientes, 0);
    }).catch(console.error);

  
}
