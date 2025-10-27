import { showErrorNotification, showSuccessNotification, showLoadingNotification } from './utils.js';
import { formateoDatosIng, ingrentesDatosForm } from './dataFormatters.js';
import { BASE_URL } from './../../../../config.js';


const API_ENDPOINTS = {
    saveIngKitchen: './../../../controllers/subirIngrediente.php', 
    saveIngEcommerce: `${BASE_URL}/ecommerce/apiwoo/crearProductoPrincipalIng.php`,
    checkIngredientName: `${BASE_URL}/kitchen/controllers/checkNameProduct.php`
};

export async function saveIngredient(ev) {
    ev.preventDefault();

    // Mostrar loading al inicio
    const loadingNotification = showLoadingNotification("Saving ingredient...", "Please wait while we save your data");

    try {
        // 1. Validación inicial
        const inputAllValues = document.getElementById("allValues");
        const mensajeFeedback = document.getElementById('nameIngredientFeedback');
        const clasificationFood = document.getElementById('foodClassificationIng');


        
        if (!inputAllValues.value.trim()) {
            loadingNotification.close();
            showErrorNotification('You must enter at least one sales quantity');
            return;
        }
       
        if (mensajeFeedback.style.color === "red") {
            const mensaje = mensajeFeedback.textContent ;
            showErrorNotification(mensaje);
            return false;
        }

        
        if (clasificationFood.value === "") {
           
            const mensaje = "You must select a classification" ;
            showErrorNotification(mensaje);
            return false;
        }






        // 2. Preparar datos
        const ingrediente = ingrentesDatosForm();
        const formData = new FormData();
        formData.append('ingrediente', JSON.stringify(ingrediente));

        // 3. Validar imagen
        const imagenFile = document.getElementById('imagenIng').files[0];
        if (imagenFile) {
            const allowedExtensions = ["jpg", "jpeg", "png"];
            const fileExtension = imagenFile.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(fileExtension)) {

                loadingNotification.close(); // Cerrar loading si hay error
                showErrorNotification('Only .jpg and .png files are allowed');

                return; // Importante: Retornar explícitamente
            }
            formData.append('imagen', imagenFile);
        }

        // 4. Llamada API (asegurando return)
        const response = await fetch(API_ENDPOINTS.saveIngKitchen, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();
        if (data.error) throw new Error(data.error);

        // Cerrar loading antes de procesar la respuesta
        loadingNotification.close();

        // 5. Procesar respuesta (con return explícito)
        return await procesarRespuesta(data);

    } catch (error) {
        console.error('Error en saveIngredient:', error);
        loadingNotification.close(); // Asegurarse de cerrar el loading en caso de error
        showErrorNotification("Error", error.message || "An error occurred while saving the ingredient.");

        throw error;
    }
}

async function procesarRespuesta(data) {
    try {
        // Mostrar loading para la segunda operación
        const secondLoading = showLoadingNotification("Creating e-commerce product...");

        const producto = formateoDatosIng(data.sku);
        const resultado = await crearProductoPrincipalEcommerce(producto);

        secondLoading.close(); // Cerrar loading cuando termina

        showSuccessNotification("Ingredient Saved!", "The ingredient has been added successfully").then(() => {
            location.reload();
        });

        return resultado;
    } catch (error) {
        secondLoading.close(); // Cerrar loading si hay error
        throw error;
    }
}

export async function crearProductoPrincipalEcommerce(data) {
    try {
        const response = await apiRequest(API_ENDPOINTS.saveIngEcommerce, 'POST', data);
        return response;
    } catch (error) {
        throw error;
    }
}

async function apiRequest(url, method, data, isFormData = false) {
    const options = {
        method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    if (isFormData) {
        options.body = prepareFormData(data);
    } else {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(data);
    }

    const response = await fetch(url, options);

    // Verificación más detallada del estado
    if (!response.ok) {
        let errorData;
        try {
            errorData = await response.json();
        } catch {
            errorData = { message: await response.text() || 'Unknown error' };
        }

        const error = new Error(errorData.message || `HTTP error ${response.status}`);
        error.status = response.status;
        error.data = errorData;
        throw error;
    }

    return response.json();
}

function prepareFormData(data) {
    const formData = new FormData();
    // Lógica para preparar FormData
    return formData;
}


export async function checkIngredientNameExists(name, type = 'ingrediente') {
    // Validación de entrada
    if (typeof name !== 'string' || name.trim() === '') {
        throw new Error('Ingredient name is required');
    }

    try {
        const response = await apiRequest(
            API_ENDPOINTS.checkIngredientName,
            'POST',
            { name: name.trim(), type }
        );

        // Validación de estructura de respuesta
        if (typeof response.exists === 'undefined') {
            throw new Error('La respuesta del servidor no tiene el formato esperado');
        }

        return response;

    } catch (error) {
        console.error('Error en checkIngredientNameExists:', {
            endpoint: API_ENDPOINTS.checkIngredientName,
            name,
            type,
            error: error.message,
            status: error.status,
            errorData: error.data
        });

        // Mensajes más amigables para el usuario
        const userMessage = error.status === 404
            ? 'El servicio de validación no está disponible'
            : error.status === 422
                ? 'Datos de validación incorrectos'
                : 'Error al verificar el nombre';

        throw new Error(userMessage);
    }
}