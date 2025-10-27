import { BASE_URL } from "./../../../config.js";  


export function agregarProducto(data) {
    // URL del archivo PHP que maneja la solicitud POST
    const url = `${BASE_URL}/ecommerce/apiwoo/crearProducto.php`;

    const requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    };

    // Realizar la solicitud y devolver la promesa
    return fetch(url, requestOptions)
        .then(response => response.text()) // Convertir la respuesta a texto
        .then(data => {
            //console.log(data); // Imprimir la respuesta
        })
        .catch(error => {
            console.error('Error al agregar el producto:', error);
            throw error; // Lanzar el error para que pueda ser manejado fuera de la función
        });
}

