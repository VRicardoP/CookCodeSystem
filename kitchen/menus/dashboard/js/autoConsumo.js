import { obtenerDatosAutoconsumo } from './getDatos.js';

let chart;
let productos = [];





function mostrarLoader(mostrar) {
    const loader = document.getElementById('loaderAutoconsumo');
    if (loader) {
        loader.style.display = mostrar ? 'block' : 'none';
    }
}



// Función para obtener datos de forma asíncrona
async function obtenerDatosCostes() {
    try {

        mostrarLoader(true);
        productos = await obtenerDatosAutoconsumo(); // Asignar a la variable global
        console.log("Dentro de obtenerDatos Autoconsumo:", productos); // Verifica los datos aquí
        return productos;
    } catch (error) {
        console.error('Error al procesar los datos de productos:', error);
        return [];
    }finally {
        mostrarLoader(false);
    }
}
export async function inicializarGraficaAutoconsumo() {
    // Solo obtener datos si productos está vacío
    if (productos.length === 0) {
        await obtenerDatosCostes();
    }

    const ctx = document.getElementById('myBarChartAutoconsumo').getContext('2d');

    // Filtrar productos con cantidad mayor que 0
    const productosFiltrados = productos.filter(item => parseFloat(item.cantidad) > 0);

    const labels = productosFiltrados.map(item => item.name); // Etiquetas con los nombres de los productos
    const cantidades = productosFiltrados.map(item => parseFloat(item.cantidad)); // Cantidades consumidas
    const precios = productosFiltrados.map(item => parseFloat(item.coste) || 0); // Precios por unidad

    // Calcular el coste total de los productos filtrados
    const totalCoste = productosFiltrados.reduce((sum, item) => sum + (parseFloat(item.coste) || 0), 0).toFixed(2);

    // Si la gráfica ya existe, la destruimos para evitar problemas al actualizar
    if (chart) {
        chart.destroy();
    }

    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Stock',
                backgroundColor: "#0c5ebf",
                borderColor: "#c32f35",
                data: cantidades
            }],
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
        }
    });

    // Actualizar el total en el contenedor
    const totalContainer = document.getElementById("totalmyBarChartAutoconsumo");
    if (totalContainer) {
        totalContainer.textContent = "Cost: " + totalCoste + "€";
        totalContainer.style.color = 'black';
        totalContainer.style.fontSize = '24px';
        totalContainer.style.fontWeight = 'bold';
    }

    productosEnModalAutoconsumo(productosFiltrados); // Enviar solo los productos con cantidad > 0
    return chart;
}

// Función para mostrar los productos en el modal
function productosEnModalAutoconsumo(productos) {
    const divModal = document.getElementById("checkboxContainerAutoconsumo");

    try {
        // Limpiar contenido anterior del modal
        divModal.innerHTML = "";

        // Verifica si productos es un array
        if (Array.isArray(productos)) {
            productos.forEach(producto => {
                const label = document.createElement('label');
                label.textContent = producto.name;

                const input = document.createElement('input');
                input.value = producto.name; // Usar el nombre en lugar del ID
                input.type = "checkbox";
                input.className = "platoCheckboxAutoconsumo";

                label.appendChild(input);
                divModal.appendChild(label);
                console.log("Producto en modal de Autoconsumo: " + producto.name);
            });
        } else {
            console.error("Se esperaba un array, pero se recibió:", productos);
        }
    } catch (error) {
        console.error("Error al obtener productos:", error);
    }
}

// Función para actualizar la gráfica con los productos seleccionados
export async function actualizarGraficaConProductosSeleccionadosAutoconsumo() {
    // Asegúrate de que los productos están cargados
    if (productos.length === 0) {
        await obtenerDatosCostes();
    }

    // Obtener los checkboxes seleccionados dentro del modal
    const checkboxesSeleccionados = document.querySelectorAll('#checkboxContainerAutoconsumo input[type="checkbox"]:checked');

    // Obtener los nombres de los productos seleccionados
    const nombresSeleccionados = Array.from(checkboxesSeleccionados).map(cb => cb.value);

    // Filtrar los productos basados en los checkboxes seleccionados
    const productosSeleccionados = productos.filter(producto => nombresSeleccionados.includes(producto.name));

    // Actualizar la gráfica con los productos seleccionados
    actualizarGrafica(productosSeleccionados);
}

function actualizarGrafica(productosSeleccionados) {
    if (!chart) return; // Si la gráfica no está inicializada, salir

    // Calcular el nuevo coste total con validaciones adicionales
    const cantidades = productosSeleccionados.map(item => parseFloat(item.cantidad) || 0); // Evitar NaN
    const precios = productosSeleccionados.map(item => parseFloat(item.coste) || 0); // Evitar NaN

    // Calcular el coste total
    const totalCoste = precios.reduce((total, precio, index) => total + (precio * cantidades[index]), 0).toFixed(2);

    // Actualizar los datos de la gráfica
    chart.data.labels = productosSeleccionados.map(item => item.name); // Actualizar etiquetas
    chart.data.datasets[0].data = cantidades; // Actualizar datos

    // Actualizar el total en el contenedor
    const totalContainer = document.getElementById("totalmyBarChartAutoconsumo");
    if (totalContainer) {
        totalContainer.textContent = "Cost: " + totalCoste+"€";
        totalContainer.style.color = 'black';
        totalContainer.style.fontSize = '24px';
        totalContainer.style.fontWeight = 'bold';
    }

    // Refrescar la gráfica
    chart.update();
}
