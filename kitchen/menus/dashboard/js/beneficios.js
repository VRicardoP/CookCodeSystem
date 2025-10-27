import { obtenerDatosProductosEcommerce } from './getDatos.js';

let chart;
let productos = []; // Variable global para almacenar productos





function mostrarLoader(mostrar) {
    const loader = document.getElementById('loaderBeneficios');
    if (loader) {
        loader.style.display = mostrar ? 'block' : 'none';
    }
}







function filtrarProductosPorCategoria(productosFiltrar, categorias) {
    const categoriasNormalizadas = categorias.map(c => c.toLowerCase().trim());

    return productosFiltrar.filter(producto =>
        producto.category.some(cat => categoriasNormalizadas.includes(cat.toLowerCase().trim()))
    );
}
function agruparYSumarPorSKU(productosFiltrados) {
    const agrupado = {};

    productosFiltrados.forEach(producto => {
        // Verificamos si el SKU contiene '-L' seguido de números
        if (!/-L\d+$/.test(producto.sku)) {
            console.log(`Ignorando producto no lote: ${producto.sku}`);
            return; // Si no es un lote, lo ignoramos
        }

        console.log("Procesando producto:", producto);

        const skuParts = producto.sku.split('-');
        if (skuParts.length < 3) return; // Nos aseguramos de que tenga el formato adecuado

        const claveAgrupacion = `${skuParts[0]}-${skuParts[1]}`;
        const precio = parseFloat(producto.price) || 0;
        const stock = producto.stock_quantity || 0;
        const precioTotal = precio * stock;

        const nombreLimpio = producto.name.split(" - Lote ING")[0].trim();

        if (agrupado[claveAgrupacion]) {
            agrupado[claveAgrupacion].price += precioTotal;
            agrupado[claveAgrupacion].stock_quantity += stock;
        } else {
            agrupado[claveAgrupacion] = {
                sku: claveAgrupacion,
                name: nombreLimpio,
                price: precioTotal,
                stock_quantity: stock
            };
        }
    });

    console.log("Productos agrupados finales:", Object.values(agrupado));
    return Object.values(agrupado);
}

async function obtenerDatosBeneficios() {
    try {
        mostrarLoader(true);
        productos = await obtenerDatosProductosEcommerce(); // Obtener productos actualizados
        console.log("Productos obtenidos:", productos); // <-- Verifica qué productos se están obteniendo

        const categoriasFiltrar = ["Lotes de ingredientes", "Lotes de elaborados"];
        const productosFiltrados = filtrarProductosPorCategoria(productos, categoriasFiltrar);

        console.log("Productos después de filtrar:", productosFiltrados);
        return productosFiltrados;
    } catch (error) {
        console.error('Error al procesar los datos:', error);
        return [];
    } finally {
        mostrarLoader(false);
    }
}


export async function inicializarGraficaBeneficios() {
    if (productos.length === 0) {
        await obtenerDatosBeneficios();
    }

    const categoriasFiltrar = ["Lotes de ingredientes", "Lotes de elaborados"];
    const productosFiltrados = filtrarProductosPorCategoria(productos, categoriasFiltrar);
    const productosAgrupados = agruparYSumarPorSKU(productosFiltrados);

    const ctx = document.getElementById('myBarChartBeneficios').getContext('2d');
   

    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: productosAgrupados.map(item => item.name),
            datasets: [{
                label: 'Euros(€)',
                backgroundColor: "#007934",
                data: productosAgrupados.map(item => parseFloat(item.price.toFixed(2)))
            }]
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
                    label: function(tooltipItem, data) {
                        let valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        return `€ ${valor.toFixed(2)}`; // Asegura que el tooltip solo muestre 2 decimales
                    }
                }
            }
        }
    });
    







    const total = productosAgrupados.reduce((sum, item) => sum + item.price, 0).toFixed(2);
    const totalContainer = document.getElementById('totalmyBarChartBeneficios');
    if (totalContainer) {
        totalContainer.textContent = `Benefits: ${total}€`;

    }

    productosEnModalBeneficios(productosAgrupados, true);
    actualizarGraficaConProductosSeleccionadosBeneficios();
}

function productosEnModalBeneficios(productos, seleccionarTodos = false) {
    const divModal = document.getElementById("checkboxContainerBeneficios");
    divModal.innerHTML = '';

    productos.forEach(producto => {
        const label = document.createElement('label');
        label.textContent = producto.name;
        const input = document.createElement('input');
        input.value = producto.sku;
        input.type = "checkbox";
        input.className = "platoCheckboxBeneficios";
        input.checked = seleccionarTodos;

        label.appendChild(input);
        divModal.appendChild(label);
    });
}

export function actualizarGraficaConProductosSeleccionadosBeneficios() {
    const checkboxesSeleccionados = document.querySelectorAll('#checkboxContainerBeneficios input[type="checkbox"]:checked');
    const skusSeleccionados = Array.from(checkboxesSeleccionados).map(cb => cb.value);
    const productosSeleccionados = productos.filter(producto => skusSeleccionados.includes(`${producto.sku.split('-')[0]}-${producto.sku.split('-')[1]}`));
    actualizarGrafica(productosSeleccionados);
}

function actualizarGrafica(productosSeleccionados) {
    if (!chart) return;
    const productosAgrupados = agruparYSumarPorSKU(productosSeleccionados);

    chart.data.labels = productosAgrupados.map(item => item.name);
    chart.data.datasets[0].data = productosAgrupados.map(item => item.price);
    const total = productosAgrupados.reduce((sum, item) => sum + item.price, 0);
    
    const totalContainer = document.getElementById('totalmyBarChartBeneficios');
    if (totalContainer) {
        totalContainer.textContent = `Benefits: ${total.toFixed(2)}€`;
    }
    chart.update();
}

document.getElementById('consultarBeneficiosBtn').addEventListener('click', actualizarGraficaConProductosSeleccionadosBeneficios);
