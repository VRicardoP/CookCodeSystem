import { obtenerDatosProductosEcommerce } from './getDatos.js';

let chart;
let productos = []; // Declarada globalmente


function mostrarLoader(mostrar) {
    const loader = document.getElementById('loaderCostos');
    if (loader) {
        loader.style.display = mostrar ? 'block' : 'none';
    }
}





// Función para filtrar productos por categoría
function filtrarProductosPorCategoria(productos, categorias) {
    return productos.filter(producto =>
        producto.category.some(cat => categorias.includes(cat))
    );
}

// Función para agrupar productos por SKU y calcular su coste total
function agruparYSumarPorSKU(productos) {
    const agrupado = {};

    productos.forEach(producto => {
        const skuParts = producto.sku.split('-');
        if (skuParts.length < 2) return;

        const claveAgrupacion = `${skuParts[0]}-${skuParts[1]}`;
        const cantidadStock = parseFloat(producto.stock_quantity) || 0;
        const costPrice = parseFloat(producto.cost_price) || 0;
        const costeTotal = cantidadStock * costPrice; // Calcular coste total

        // Eliminar " - Lote ING" del nombre
        const nombreLimpio = producto.name.split(" - Lote ING")[0].trim();

        if (agrupado[claveAgrupacion]) {
            agrupado[claveAgrupacion].stock_quantity += cantidadStock;
            agrupado[claveAgrupacion].cost += costeTotal;
        } else {
            agrupado[claveAgrupacion] = {
                sku: claveAgrupacion,
                name: nombreLimpio,
                stock_quantity: cantidadStock,
                cost: costeTotal
            };
        }
    });

    return Object.values(agrupado);
}
async function obtenerDatosCostes() {
    try {
        mostrarLoader(true);
        productos = await obtenerDatosProductosEcommerce();
        return productos;
    } catch (error) {
        console.error('Error al procesar los datos de productos:', error);
        return [];
    } finally {
        mostrarLoader(false);
    }
}


// Función para inicializar la gráfica de costes
export async function inicializarGraficaCostes() {
    if (productos.length === 0) {
        await obtenerDatosCostes();
    }

    console.log("Dentro de gráfica Costes:", productos);

    const categoriasFiltrar = ["Lotes de ingredientes", "Lotes de elaborados"];
    const productosFiltrados = filtrarProductosPorCategoria(productos, categoriasFiltrar);
    const productosAgrupados = agruparYSumarPorSKU(productosFiltrados);

    const ctx = document.getElementById('myBarChartCostos').getContext('2d');

    // Calcular el total de costes con dos decimales
    const total = productosAgrupados.reduce((sum, item) => sum + item.cost, 0).toFixed(2);

    // Inicializar la gráfica
    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: productosAgrupados.map(item => item.name),
            datasets: [{
                label: 'Euros(€)',
                backgroundColor: "#cc3339",
                hoverBackgroundColor: '#c32f35',
                borderColor: "#4e73df",
                data: productosAgrupados.map(item => parseFloat(item.cost.toFixed(2))) // Valores con 2 decimales
            }],
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: { beginAtZero: true }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return `€ ${tooltipItem.yLabel.toFixed(2)}`; // Mostrar con 2 decimales en tooltip
                    }
                }
            }
        }
    });

    // Mostrar el total correctamente con dos decimales
    const totalContainer = document.getElementById('totalmyBarChartCostos');
    if (totalContainer) {
        totalContainer.textContent = `Cost: ${total}€`;
        totalContainer.style.color = 'black';
        totalContainer.style.fontSize = '24px';
        totalContainer.style.fontWeight = 'bold';
    }

    // Mostrar los productos en el modal
    productosEnModalCostes(productosAgrupados, true);
    actualizarGraficaConProductosSeleccionados();
}

// Función para mostrar los productos en el modal
function productosEnModalCostes(productos, seleccionarTodos = false) {
    actualizarCheckboxes(productos, seleccionarTodos);
}

// Función para actualizar la gráfica con productos seleccionados
export function actualizarGraficaConProductosSeleccionados() {
    console.log("Actualizando gráfica de costes...");

    const categoriasFiltrar = ["Lotes de ingredientes", "Lotes de elaborados"];
    const productosFiltrados = filtrarProductosPorCategoria(productos, categoriasFiltrar);
    const productosAgrupados = agruparYSumarPorSKU(productosFiltrados);

    // Obtener los checkboxes seleccionados
    const checkboxesSeleccionados = document.querySelectorAll('#checkboxContainerCostos input[type="checkbox"]:checked');
    const nombresSeleccionados = Array.from(checkboxesSeleccionados).map(cb => cb.value);

    // Filtrar los productos seleccionados
    const productosSeleccionados = productosAgrupados.filter(producto => nombresSeleccionados.includes(producto.name));

    // Actualizar la gráfica
    actualizarGrafica(productosSeleccionados);
}

// Función para actualizar la gráfica
function actualizarGrafica(productosAgrupados) {
    if (!chart) return;

    chart.data.labels = productosAgrupados.map(item => item.name);
    chart.data.datasets[0].data = productosAgrupados.map(item => parseFloat(item.cost.toFixed(2)));
    chart.update();

    // Calcular el total de los costes en la gráfica
    const total = productosAgrupados.reduce((sum, item) => sum + item.cost, 0).toFixed(2);

    // Actualizar el total en el contenedor
    const totalContainer = document.getElementById('totalmyBarChartCostos');
    if (totalContainer) {
        totalContainer.textContent = `Cost: ${total}€`;
        totalContainer.style.color = 'black';
        totalContainer.style.fontSize = '24px';
        totalContainer.style.fontWeight = 'bold';
    }
}

// Función para actualizar los checkboxes en el modal
function actualizarCheckboxes(productosFiltrados, seleccionarTodos = false) {
    const divModal = document.getElementById("checkboxContainerCostos");
    divModal.innerHTML = '';

    productosFiltrados.forEach(producto => {
        const label = document.createElement('label');
        label.textContent = producto.name;

        const input = document.createElement('input');
        input.value = producto.name;
        input.type = "checkbox";
        input.className = "platoCheckboxCostos";
        input.checked = seleccionarTodos;

        input.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('#checkboxContainerCostos input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                if (checkbox.value === producto.name) {
                    checkbox.checked = input.checked;
                }
            });
        });

        label.appendChild(input);
        divModal.appendChild(label);
    });
}
