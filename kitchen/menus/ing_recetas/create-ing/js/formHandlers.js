import { UNIT_CONFIG } from './constants.js';
import { saveIngredient, checkIngredientNameExists} from './apiService.js';


document.addEventListener("DOMContentLoaded", function() {
    // Elementos del DOM
    const form = document.getElementById('ingredientForm');
    const addValueBtn = document.getElementById("addValue");
    const clearValueBtn = document.getElementById("clearValues");
    const allValuesInput = document.getElementById("allValues");
    const inputValue = document.getElementById("attributeValue");
    const unitValue = document.getElementById("unitNewIng");
    const valuesContainer = document.getElementById("valuesContainer");
    
    // Estado
    let attributeValues = [];
    const valuesList = document.getElementById("valuesList");
  
    valuesContainer.appendChild(valuesList);

    // Event Listeners
    
    unitValue.addEventListener("change", handleUnitChange);
    addValueBtn.addEventListener("click", handleAddValue);
    clearValueBtn.addEventListener("click", clearValuesEcommerce);

    form.addEventListener("submit", saveIngredient);
    
    
    const imageInput = document.getElementById('imagenIng');
    if (imageInput) {
        imageInput.addEventListener('change', previewImageIng);
    }

    const toggleButton = document.getElementById('toggleAttributesIng');
    if (toggleButton) {
        toggleButton.addEventListener('click', toggleAttributes);
    }

    const cancelButton = document.getElementById('cancelIngredient');
    if (cancelButton) {
        cancelButton.addEventListener('click', resetForm);
    }

    const inputName = document.getElementById('nameIngredientNewIng');
    if (inputName) {
        inputName.addEventListener('input', handleNameCheck);
    }



    // Funciones

    function clearValuesEcommerce(){
        attributeValues = [];
        allValuesInput.value = "";
        inputValue.value = "";
        valuesList.innerHTML = "";
 
    }



    function handleUnitChange() {
        clearValuesEcommerce();

        const config = UNIT_CONFIG[unitValue.value];
        if (!config) return;

        Object.entries(config.labels).forEach(([key, text]) => {
            const element = document.getElementById(`span-${key}`);
            if (element) element.textContent = text;
        });
    }

    function handleAddValue() {
        const value = parseFloat(inputValue.value.trim());
        if (!isNaN(value) && value > 0 && !attributeValues.includes(value)) {
            attributeValues.push(value);
            inputValue.value = "";
            updateValuesDisplay();
            createValueBox(value + " " + unitValue.value);
        } else {
            alert("Please enter:\n• A valid number greater than 0\n• That isn't already in your list");
        }
    }

    function updateValuesDisplay() {
        allValuesInput.value = attributeValues.join(", ");
    }

    function createValueBox(value) {
        const box = document.createElement("div");
        box.textContent = value;
        box.className = "value-box";

        valuesList.appendChild(box);
    }
});

export function previewImageIng(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('imagenChIng');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}


function toggleAttributes() {
    const content = document.getElementById('formContentIngDesp');
    const arrowIcon = document.getElementById('arrowIconIng');

    // Alternar usando Bootstrap
    $(content).collapse('toggle');

    // Alternar icono
    arrowIcon.classList.toggle('bi-chevron-down');
    arrowIcon.classList.toggle('bi-chevron-up');
}

export function resetForm() {
    const form = document.getElementById('ingredientForm');
    form.reset();
    
    document.getElementById('imagenChIng').src = './../../../svg/image.svg';
    document.getElementById('pCh').textContent = "Upload image";
    document.getElementById("allValues").value = "";
    document.getElementById("valuesList").innerHTML = "";
}

async function handleNameCheck() {
    const feedbackElement = document.getElementById('nameIngredientFeedback');
    
    try {
        feedbackElement.textContent = 'Verificando...';
        feedbackElement.style.color = 'gray';
        
        const result = await checkIngredientNameExists(this.value);
        
        feedbackElement.textContent = result.exists
            ? '⚠ This ingredient already exists'
            : '✓ Name available';
        feedbackElement.style.color = result.exists ? 'red' : 'green';
        
    } catch (error) {
        feedbackElement.textContent = `⚠ ${error.message}`;
        feedbackElement.style.color = 'orange';
        
        // Opcional: Mostrar notificación adicional para errores graves
        if (error.status >= 500) {
            showErrorNotification('Error del servidor', error.message);
           
           
          console.log(object);
          
           
        }
    }
}

