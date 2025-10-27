
export function obtenerIngredientes() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: './../../../controllers/datosIngredients.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                resolve(response); // Resuelve la promesa con la respuesta
            },
            error: function (xhr, status, error) {
                console.error(error);
                reject(error); // Rechaza la promesa con el error
            }
        });
    });
}

export function obtenerRecetas() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: './../../../controllers/getRecetas.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                resolve(response); // Resuelve la promesa con la respuesta
            },
            error: function (xhr, status, error) {
                console.error(error);
                reject(error); // Rechaza la promesa con el error
            }
        });
    });
}

export function obtenerPreelaborados() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: './../../../controllers/getPreelaborados.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                resolve(response); // Resuelve la promesa con la respuesta
            },
            error: function (xhr, status, error) {
                console.error(error);
                reject(error); // Rechaza la promesa con el error
            }
        });
    });
}

export function obtenerIngredientesReceta(idReceta) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: './../../../controllers/getRecipeIngredients.php',
            type: 'GET',
            data: { receta_id: idReceta }, // Agrega el parámetro receta_id a la solicitud
            dataType: 'json',
            success: function (response) {
                resolve(response); // Resuelve la promesa con la respuesta
            },
            error: function (xhr, status, error) {
                console.error(error);
                reject(error); // Rechaza la promesa con el error
            }
        });
    });
}
