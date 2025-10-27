import { obtenerDatosProductosEcommerce } from './getDatos.js';

let estadoCaducidadChart = null;
let productosActuales = [];  // Aquí guardamos los lotes de productos filtrados para cada estado
const coloresEstado = {
    'caducados': '#FF6384',
    'aSieteDias': '#FFCC00',
    'aQuinceDias': '#FFD700',
    'masDeQuinceDias': '#32CD32'
};



function mostrarLoader(mostrar) {
    const loader = document.getElementById('loaderCaducidad');
    if (loader) {
        loader.style.display = mostrar ? 'block' : 'none';
    }
}









// Función para agrupar los datos por nombre y sumar las cantidades
function agruparYSumarPorNombre(datos, estado) {
    const agrupado = {};

    datos.forEach(item => {

        const nombre = item.name;
        const cantidad = parseFloat(item.stock_quantity);

        if (agrupado[nombre]) {
            agrupado[nombre] += cantidad;
        } else {
            agrupado[nombre] = cantidad;
        }
    });

    return {
        labels: Object.keys(agrupado),
        datasets: [{
            label: 'Stock',
            data: Object.values(agrupado),
            backgroundColor: coloresEstado[estado] || '#CCCCCC'
        }]
    };
}


function formatearFecha(fecha) {
    const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    return new Date(fecha).toLocaleDateString('es-ES', options);
}


// Función para inicializar la gráfica de estado de caducidad
export function inicializarGraficaEstadoCaducidad() {


    mostrarLoader(true);

    obtenerDatosProductosEcommerce()
        .then(productos => {
            const ctx = document.getElementById('estadoCaducidadChart').getContext('2d');

            const categoriasFiltrarLotes = ["Lotes de ingredientes", "Lotes de elaborados"];
            const lotesFiltrados = filtrarProductosPorCategoria(productos, categoriasFiltrarLotes);

            // Obtener los datos iniciales (caducados)
            let estadoProductos = obtenerProductosPorEstadoParaBarras(lotesFiltrados, 'caducados');
            productosActuales = estadoProductos.productos[0];  // Guardamos los lotes actuales

            if (estadoCaducidadChart !== null) {
                estadoCaducidadChart.destroy();
            }

            // Inicializar gráfica
            estadoCaducidadChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: estadoProductos.dataBarra[0].labels.map(label => {
                        const partes = label.split(" - ");
                        // Verificar si el nombre contiene "Lote ING"
                        if (partes.length > 1 && partes[1].includes("Lote ING")) {
                            // Dividir el nombre en partes usando " - " como separador
                            
                            return partes[0]; // Tomar solo la primera parte (nombre base)
                        } else {
                            return label; // Mantener el nombre original si no contiene "Lote ING"
                        }
                    }),
                    datasets: estadoProductos.dataBarra[0].datasets
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: { beginAtZero: true }
                        }]
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    },
                    onClick: function (evt) {
                        const elements = estadoCaducidadChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
                    
                        if (elements.length > 0) {
                            const index = elements[0]._index;  // Asegúrate de usar 'index' en las versiones más recientes de Chart.js
                    
                            // Filtrar lotes según el nombre del producto clicado en la gráfica
                            const productoClicado = estadoCaducidadChart.data.labels[index];




                            const nombreBarra = estadoCaducidadChart.data.labels[index];
                            const nombreBarraFormateado = formatearNombreBarra(nombreBarra);


                            const productosSeleccionados = productosActuales.filter(producto => producto.name === nombreBarraFormateado);
                    
                            // Mostrar modal con los lotes seleccionados
                            $('#statusModal').modal('show');
                    
                            // Crear tabla en lugar de lista
                            let contenido = `
                                <table class="table table-striped" style="color: black;">
                                    <thead>
                                        <tr>
                                            <th>Batch</th>
                                            <th>Name</th>
                                            <th>Expiration Date</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;
                    
                            // Agregar las filas de productos
                            productosSeleccionados.forEach(element => {

                                const fechaCaducidadFormateada = formatearFecha(element.fecha_caducidad);
                                contenido += `
                                    <tr>
                                        <td>${element.sku}</td>
                                        <td>${element.name}</td>
                                        <td>${fechaCaducidadFormateada}</td>
                                        <td>${element.stock_quantity}</td>
                                    </tr>
                                `;
                            });
                    
                            // Cerrar la tabla
                            contenido += `
                                    </tbody>
                                </table>
                            `;

                       //     $('#headerPie').css('background-color', color);
                            // Insertar el contenido en el contenedor del modal
                            $('#containerstatus').html(contenido);
                        }
                    }
                }
            });

            // Manejo de filtros
            document.querySelectorAll('.dropdown-item-status').forEach(item => {
                item.addEventListener('click', (event) => {
                    const filtroSeleccionado = event.target.id.replace('filtro', '');
                    const filtro = mapFiltroIdToFiltro(filtroSeleccionado);
                    const estadoProductos = obtenerProductosPorEstadoParaBarras(lotesFiltrados, filtro);

                    // Actualizar productos actuales al cambiar el filtro (lotes)
                    productosActuales = estadoProductos.productos[0];  // Actualizamos aquí

                    actualizarGraficaEstadoCaducidad(estadoProductos, filtro);
                });
            });
        })
        .catch(error => {
            console.error('Error al procesar los datos de productos:', error);
        }).finally(() => {
            // Ocultar loader después de la carga
            mostrarLoader(false);
        });
}





// Función para formatear el nombre de la barra y hacer coincidir con los nombres de los productos
function formatearNombreBarra(nombre) {
    const partes = nombre.split(" - ");
    // Si el nombre contiene "Lote ING", lo formateamos para que solo tome la parte antes del guion
    if (partes.length > 1 && partes[1].includes("Lote ING")) {
        return partes[0]; // Tomar solo la primera parte (nombre base)
    } else {
        return nombre; // Mantener el nombre original si no contiene "Lote ING"
    }
}





// Función para filtrar productos por categoría
function filtrarProductosPorCategoria(productos, categorias) {
    return productos.filter(producto =>
        producto.category.some(cat => categorias.includes(cat))
    );
}

// Función para obtener productos por estado para la gráfica de barras
function obtenerProductosPorEstadoParaBarras(productos, estado) {
    const hoy = new Date();

    const caducados = [];
    const aSieteDias = [];
    const aQuinceDias = [];
    const masDeQuinceDias = [];
    let datos = { productos: [], dataBarra: [] };

    productos.forEach(producto => {
        const fechaCaducidad = new Date(producto.fecha_caducidad);
        const diferenciaDias = Math.ceil((fechaCaducidad - hoy) / (1000 * 60 * 60 * 24));

        if (diferenciaDias < 0) {
            caducados.push(producto);
        } else if (diferenciaDias <= 7) {
            aSieteDias.push(producto);
        } else if (diferenciaDias <= 15) {
            aQuinceDias.push(producto);
        } else {
            masDeQuinceDias.push(producto);
        }
    });

    switch (estado) {
        case 'caducados':
            datos.productos.push(caducados);
            datos.dataBarra.push(agruparYSumarPorNombre(caducados, 'caducados'));
            return datos;
        case 'aSieteDias':
            datos.productos.push(aSieteDias);
            datos.dataBarra.push(agruparYSumarPorNombre(aSieteDias, 'aSieteDias'));
            return datos;
        case 'aQuinceDias':
            datos.productos.push(aQuinceDias);
            datos.dataBarra.push(agruparYSumarPorNombre(aQuinceDias, 'aQuinceDias'));
            return datos;
        case 'masDeQuinceDias':
            datos.productos.push(masDeQuinceDias);
            datos.dataBarra.push(agruparYSumarPorNombre(masDeQuinceDias, 'masDeQuinceDias'));
            return datos;
        default:
            return datos;
    }
}
// Función para actualizar la gráfica con los nuevos datos
function actualizarGraficaEstadoCaducidad(estadoProductos, estado) {
    if (estadoCaducidadChart !== null) {
        // Actualizamos los productos al cambiar el filtro
        productosActuales = estadoProductos.productos[0];  

        // Aplicamos el formateo de los labels aquí también
        estadoCaducidadChart.data.labels = estadoProductos.dataBarra[0].labels.map(label => {
            const partes = label.split(" - ");
            // Verificar si el nombre contiene "Lote ING"
            if (partes.length > 1 && partes[1].includes("Lote ING")) {
                return partes[0]; // Tomar solo la primera parte (nombre base)
            } else {
                return label; // Mantener el nombre original si no contiene "Lote ING"
            }
        });

        // Actualizamos los datasets de la gráfica
        estadoCaducidadChart.data.datasets = estadoProductos.dataBarra[0].datasets;

        // Aplicamos el color de fondo para la gráfica dependiendo del estado
        estadoCaducidadChart.data.datasets[0].backgroundColor = coloresEstado[estado] || '#CCCCCC';

        // Refrescamos la gráfica para que se reflejen los cambios
        estadoCaducidadChart.update();
    }
}


// Función para mapear IDs de filtros a estados
function mapFiltroIdToFiltro(filtroId) {
    switch (filtroId) {
        case 'Caducados': return 'caducados';
        case 'A7Dias': return 'aSieteDias';
        case 'A15Dias': return 'aQuinceDias';
        case 'Mas15Dias': return 'masDeQuinceDias';
        default: return 'caducados';
    }
}
