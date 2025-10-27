import { BASE_URL } from './../../../config.js';

// Función para agrupar los datos por nombre y sumar las cantidades y el coste
function agruparYSumarPorNombre(datos) {
    const agrupado = {};

    datos.forEach(item => {
        const nombre = item.name;
        const cantidad = parseFloat(item.cantidad);
        const costeUnitario = parseFloat(item.coste); // Coste por unidad
        const costeTotal = cantidad * costeUnitario;  // Coste total para esta entrada

        if (agrupado[nombre]) {
            // Si ya existe el producto, acumular la cantidad y el coste total
            agrupado[nombre].cantidad += cantidad;
            agrupado[nombre].coste += costeTotal;
        } else {
            // Si es la primera vez que aparece el producto, inicializar el objeto
            agrupado[nombre] = {
                cantidad: cantidad,
                coste: costeTotal
            };
        }
    });

    // Convertir el objeto agrupado a un formato de array para la gráfica
    return Object.keys(agrupado).map(nombre => ({
        name: nombre,
        cantidad: agrupado[nombre].cantidad,
        coste: agrupado[nombre].coste.toFixed(2) // Redondear el coste a dos decimales
    }));
}


export function obtenerDatosAutoconsumo() {
    const url = BASE_URL+'/kitchen/controllers/obtenerAutoconsumo.php';

    return fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos de autoconsumo recibidos:', data.data);

            // Agrupar los datos por nombre y sumar las cantidades
         //  const datosAgrupados = agruparYSumarPorNombre(data.data);

            return data.data;
        })
        .catch(error => {
            console.error('Error al obtener los datos de autoconsumo:', error);
            throw error;
        });
}


// Función para obtener y mostrar datos desde el archivo PHP

export function obtenerDatosProductosEcommerce() {
    const url = BASE_URL+'/ecommerce/apiwoo/obtenerProductos.php';

    const requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'obtener_datos' })
    };

    return fetch(url, requestOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {

            console.log('Datos recibidos:', data.data);

            const productos = data.data;

            return productos;
        })
        .catch(error => {
            console.error('Error al obtener los datos de productos:', error);
            throw error;
        });
}

