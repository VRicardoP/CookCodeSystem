
import { BASE_URL } from './../../../../config.js';

const pendingChanges = {}; // Objeto para rastrear cambios pendientes

// Actualiza la cantidad pendiente para un ingrediente
function updatePending(id, change) {
    const pendingElement = document.getElementById(`pending-${id}`);
    const currentPending = parseFloat(pendingElement.textContent);

    const newPending = currentPending + change;
    pendingElement.textContent = newPending.toFixed(1);

    // Actualizar el registro en pendingChanges
    pendingChanges[id] = newPending;
}

// Función de retraso
async function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Fetch con tiempo límite
async function fetchWithTimeout(resource, options = {}, timeout = 10000) {
    const controller = new AbortController();
    const id = setTimeout(() => controller.abort(), timeout);
    try {
        const response = await fetch(resource, {
            ...options,
            signal: controller.signal
        });
        clearTimeout(id);
        return response;
    } catch (error) {
        clearTimeout(id);
        throw new Error('Request timed out or failed');
    }
}

// Actualizar stock en ecommerce


async function CambiaStock(sku, nuevoStock, descuentoStock) {
    console.log("nuevoStock" + nuevoStock)
    // Verificar si nuevoStock es 0 o menor
    if (nuevoStock < 0) {
        // Mostrar mensaje de error por falta de stock
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Stock must be greater than 0.',
            timer: 2500, // 2500 milisegundos = 2.5 segundos
            showConfirmButton: false // No mostrar el botón de confirmación
        });
        setTimeout(function() {
            location.reload(); // Recargar la página después de 2.5 segundos
        }, 500);
        return; // Salir de la función si el stock es inválido
    } else {


       var url = `${BASE_URL}/ecommerce/apiwoo/cambiarStock.php`;

        var data = new URLSearchParams();
        data.append('sku', sku);
        data.append('nuevoStock', nuevoStock);
        data.append('descuentoStock', descuentoStock);

        // Mostrar ventana de carga
        Swal.fire({
            title: 'Updating stock...',
            html: 'Please wait.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if (result.success) {
                // Ocultar ventana de carga
                Swal.close();

                // Mostrar ventana de confirmación que se cierra automáticamente después de 2.5 segundos
                await Swal.fire({
                    icon: 'success',
                    title: 'Stock updated successfully',
                    text: 'The stock has been updated successfully.',
                    timer: 2500, // 2500 milisegundos = 2.5 segundos
                    showConfirmButton: false // No mostrar el botón de confirmación
                });
                setTimeout(function() {
                    location.reload(); // Recargar la página después de 2.5 segundos
                }, 500);
                console.log(result.message);
            } else {
                console.error(result.message);
                // Ocultar ventana de carga
                Swal.close();

                // Mostrar mensaje de error que se cierra automáticamente después de 2 segundos
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Hubo un problema al actualizar el stock.',
                    timer: 2500, // 2500 milisegundos = 2.5 segundos
                    showConfirmButton: false // No mostrar el botón de confirmación
                });
            }
        } catch (error) {
            console.error('Error:', error);
            // Ocultar ventana de carga
            Swal.close();

            // Mostrar mensaje de error que se cierra automáticamente después de 2 segundos
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al conectar con el servidor.',
                timer: 2500, // 2500 milisegundos = 2.5 segundos
                showConfirmButton: false // No mostrar el botón de confirmación
            });
        }

    }

}





$(document).on('click', '#btnAddStock', function(e) {
    e.preventDefault(); // Prevent form submission

    var stockTienda = document.getElementById('stockEcommerce');
    var inputStock = document.getElementById('inputStock');

    if (parseFloat(stockTienda.value) < parseFloat(inputStock.value)) {
        alert('Error: Stock ecommerce is less than the amount to add.');
    } else {
        var ingredientId = $(this).data('id'); // Get the ID from the data attribute
        var addStock = $('#inputStock').val(); // Get the value from the input

        console.log('Ingredient ID: ' + ingredientId);
        console.log('Stock to add: ' + addStock);

        // Perform AJAX request to add stock
        $.ajax({
            url: 'addStock.php',
            type: 'GET',
            data: {
                idIng: ingredientId,
                addStock: addStock
            },
            success: async function(data) {
                var sku = data.sku;
                var stockEcommerce = parseFloat(data.stock_ecommerce);
                var currentStock = parseFloat(data.addStock);

                console.log(stockEcommerce);
                console.log(currentStock);

                var nuevoStock = stockEcommerce - currentStock;

                console.log("SKU: " + sku);

                await CambiaStock(sku, nuevoStock, currentStock);

              
            },
            error: function() {
                // Show error if the request fails
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was a problem updating the stock.',
                });
            }
        });
    }
});

















// Actualizar stock en ecommerce
async function updateEcommerceStock(sku, nuevoStock, descuentoStock) {
   const urlEcommerce = `${BASE_URL}/ecommerce/apiwoo/cambiarStock.php`;


    const dataEcommerce = new URLSearchParams();
    dataEcommerce.append('sku', sku);
    dataEcommerce.append('nuevoStock', nuevoStock);
    dataEcommerce.append('descuentoStock', descuentoStock);

    try {
        // Hacer la solicitud POST para actualizar el stock en ecommerce
        const responseEcommerce = await fetch(urlEcommerce, {
            method: 'POST',
            body: dataEcommerce,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'  // Asegurarse de que la solicitud sea correcta
            }
        });

        // Verificar si la respuesta es exitosa
        if (!responseEcommerce.ok) {
            throw new Error('Error al actualizar el stock en ecommerce: ' + responseEcommerce.statusText);
        }

        // Parsear la respuesta JSON
        const resultEcommerce = await responseEcommerce.json();

        // Comprobar si el resultado indica éxito
        if (resultEcommerce.success) {
            console.log('Ecommerce stock updated:', resultEcommerce.message);
            return resultEcommerce; // Retornar el resultado del ecommerce
        } else {
            throw new Error(resultEcommerce.message || 'Error desconocido al actualizar el stock.');
        }
    } catch (error) {
        console.error('Error updating ecommerce stock:', error);
        throw new Error('Ecommerce stock update failed: ' + error.message);
    }
}


// Actualizar stock en kitchen
async function updateKitchenStock(sku, nuevoStock) {
    const urlKitchen = './updateStockBatch.php';
    const dataKitchen = new URLSearchParams();
    dataKitchen.append('sku', sku);
    dataKitchen.append('nuevoStock', nuevoStock);

    try {
        // Actualizar stock en kitchen
        const responseKitchen = await fetchWithTimeout(urlKitchen, {
            method: 'POST',
            body: dataKitchen
        }, 15000);
        const resultKitchen = await responseKitchen.json();

        if (!resultKitchen.success) {
            throw new Error(resultKitchen.message || 'Error updating kitchen stock.');
        }

        console.log('Kitchen stock updated:', resultKitchen.message);
        return resultKitchen; // Retornar el resultado de kitchen

    } catch (error) {
        console.error('Error updating kitchen stock:', error);
        throw new Error('Kitchen stock update failed');
    }
}

// Confirmar y actualizar stock
async function confirmStockUpdate(sku, nuevoStock, descuentoStock, origen) {
    console.log("nuevoStock: " + nuevoStock);

    // Verificar si el nuevo stock es válido
    if (nuevoStock < 0) {
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Stock must be greater than 0.',
            timer: 2500,
            showConfirmButton: false
        });
        setTimeout(() => location.reload(), 2500);
        return;
    }

    // Mostrar ventana de carga
    Swal.fire({
        title: 'Updating stock...',
        html: 'Please wait.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        // Primero, actualizar stock en ecommerce
        const ecommerceResult = await CambiaStock(sku, nuevoStock, descuentoStock);

        // Si la actualización en ecommerce fue exitosa, entonces actualizar en kitchen
        if (ecommerceResult.success) {
            // Esperar un poco antes de proceder con el siguiente sistema
            await delay(1000);

            const kitchenResult = await updateKitchenStock(sku, nuevoStock);

            // Si la actualización en kitchen fue exitosa
            if (kitchenResult.success) {
                // Mostrar éxito y recargar página
                Swal.close();
                await Swal.fire({
                    icon: 'success',
                    title: 'Stock updated successfully',
                    text: 'The stock has been updated successfully.',
                    timer: 2500,
                    showConfirmButton: false
                });
                setTimeout(() => location.reload(), 2500);
            } else {
                throw new Error('Kitchen stock update failed');
            }
        } else {
            throw new Error('Ecommerce stock update failed');
        }

    } catch (error) {
        console.error('Error:', error);

        // Mostrar error
        Swal.close();
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'There was a problem updating the stock.',
            timer: 3000,
            showConfirmButton: false
        });
    }
}
