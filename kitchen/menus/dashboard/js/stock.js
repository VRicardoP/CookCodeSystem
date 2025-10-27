import { obtenerDatosProductosEcommerce } from './getDatos.js';

let chart;
let productos = [];

// Función para mostrar/ocultar el loader
function mostrarLoader(mostrar) {
    const loader = document.getElementById('loaderStock');
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

// Función para obtener datos de forma asíncrona
async function obtenerDatosCostes() {
    try {
        mostrarLoader(true);
        productos = await obtenerDatosProductosEcommerce(); // Asignar a la variable global
        console.log("Datos obtenidos:", productos);
        return productos;
    } catch (error) {
        console.error('Error al procesar los datos de productos:', error);
        return [];
    } finally {
        mostrarLoader(false);
    }
}

// Función para agrupar productos por el prefijo del SKU y sumar el coste
function agruparYSumarPorSku(productos) {
    const productosAgrupados = {};

    productos.forEach(producto => {
        const skuParts = producto.sku.split('-');
        let nuevoNombreSku;
        let costeProducto = parseFloat(producto.cost_price); // Intentar usar cost_price

        // Si cost_price es nulo, usar price como fallback
        if (isNaN(costeProducto)) {
            costeProducto = parseFloat(producto.price); // Si cost_price no está disponible, usa price
        }

        if (skuParts[0] === "ING" && skuParts.length >= 3) {
            var partesName = producto.name.split('-');
            var name = partesName[0] + "(" + parseInt(partesName[3], 10) + producto.type_unit+")";
            nuevoNombreSku = `${skuParts[0]}-${skuParts[2]}`;
        } else {
            nuevoNombreSku = producto.sku;
            var name = producto.name;
        }

        // Verificación de stock_quantity y sólo procesar productos con cantidad positiva
        const cantidad = parseInt(producto.stock_quantity);
        if (!productosAgrupados[nuevoNombreSku]) {
            productosAgrupados[nuevoNombreSku] = {
                nombre: name,
                costeTotal: 0,
                cantidadTotal: 0,
            };
        }

        if (!isNaN(costeProducto) && !isNaN(cantidad) && cantidad > 0) {
            productosAgrupados[nuevoNombreSku].costeTotal += costeProducto * cantidad;
            productosAgrupados[nuevoNombreSku].cantidadTotal += cantidad;
        } else {
            console.error(`Coste o cantidad no válidos para el producto ${producto.name}: cost_price=${producto.cost_price}, stock_quantity=${producto.stock_quantity}`);
        }
    });

    // Eliminar los productos que tengan cantidadTotal igual a 0
    const productosFiltrados = Object.values(productosAgrupados).filter(item => item.cantidadTotal > 0);

    // Depurar los productos filtrados
    console.log("Productos agrupados después de filtrar los de cantidad 0:", productosFiltrados);

    return productosFiltrados;
}

// Función para inicializar la gráfica y calcular el total
export async function inicializarGraficaStock() {
    if (productos.length === 0) {
        await obtenerDatosCostes();
    }

    const categoriasFiltrar = ["Lotes de ingredientes", "Lotes de elaborados"];
    const productosFiltrados = filtrarProductosPorCategoria(productos, categoriasFiltrar);

    const productosAgrupados = agruparYSumarPorSku(productosFiltrados);

    var canvasId = 'myBarChartStockEcommerce';

    console.log("Productos agrupados para la gráfica:", productosAgrupados);

    const ctx = document.getElementById(canvasId).getContext('2d');

    // Inicializamos la gráfica
    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: productosAgrupados.map(item => item.nombre), // Usar el nombre (prefijo del SKU)
            datasets: [{
                label: 'Unidades',
                backgroundColor: "#dde55e",
                hoverBackgroundColor: "#ced557",
                borderColor: "#4e73df",
                data: productosAgrupados.map(item => item.cantidadTotal), // Usar la cantidad total
            }],
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            },
        }
    });

    // Actualizar el total en el contenedor
    const totalContainer = document.getElementById('total' + canvasId);
    if (totalContainer) {
        const total = productosAgrupados.reduce((sum, item) => sum + item.costeTotal, 0);
        totalContainer.textContent = "Costs " + total.toFixed(2) + "€";
        totalContainer.style.color = 'black'; // Cambiar el color del texto a negro
        totalContainer.style.fontSize = '24px'; // Cambiar el tamaño de fuente si lo deseas
        totalContainer.style.fontWeight = 'bold'; // Cambiar el peso de la fuente si lo deseas
    }

    productosEnModalStock(productosFiltrados);

    return chart;
}
// Función para mostrar los productos en el modal sin duplicados
function productosEnModalStock(productos) {
    var divModal = document.getElementById("checkboxContainerStock");

    try {
        const categoriasFiltrar = ["Lotes de ingredientes", "Lotes de elaborados"];
        const productosFiltrados = filtrarProductosPorCategoria(productos, categoriasFiltrar);

        // Usamos un objeto para mantener un registro de productos únicos (por nombre o SKU base)
        const productosUnicos = {};

        productosFiltrados.forEach(producto => {
            // Obtenemos el nombre del producto considerando solo la parte relevante del SKU
            const skuParts = producto.sku.split('-');
            let name = producto.name;

            // Aquí se considera solo la parte del nombre hasta el lote (por ejemplo: "Aceite de oliva")
            if (skuParts[0] === "ING" && skuParts.length >= 3) {
                var partesName = producto.name.split('-');
                name = partesName[0] + "(" + parseInt(partesName[3], 10) + ")";
            }

            // Agrupamos productos con el mismo nombre (ignorando variantes)
            const skuBase = producto.sku.split('-')[0] + '-' + producto.sku.split('-')[1];  // Agrupamos por la base del SKU

            // Si este SKU base no se ha agregado, lo agregamos al objeto productosUnicos
            if (!productosUnicos[skuBase]) {
                productosUnicos[skuBase] = { ...producto, nombre: name };  // Almacenamos el primer producto con ese SKU base
            }

        });

        // Ahora, iteramos sobre los productos únicos y agregamos solo un checkbox por cada uno
        Object.values(productosUnicos).forEach(producto => {
            var label = document.createElement('label');
            label.textContent = producto.nombre;

            var input = document.createElement('input');
            input.value = producto.id;
            input.type = "checkbox";
            input.className = "platoCheckboxStock";
            input.addEventListener('change', actualizarGraficaConProductosSeleccionadosStock); // Actualizar gráfica al cambiar checkbox
            label.appendChild(input);

            divModal.appendChild(label);

            console.log("Producto en modal de stock: " + producto.nombre);
        });

    } catch (error) {
        console.error("Error al obtener productos:", error);
    }
}

// Función para actualizar la gráfica con los productos seleccionados
 export function actualizarGraficaConProductosSeleccionadosStock() {
    console.log("dentro seleccionados stock");

    const categoriasFiltrar = ["Lotes de ingredientes", "Lotes de elaborados"];
    const productosFiltrados = filtrarProductosPorCategoria(productos, categoriasFiltradas);

    // Obtener los checkboxes seleccionados dentro del modal
    const checkboxesSeleccionados = document.querySelectorAll('#checkboxContainerStock input[type="checkbox"]:checked');

    // Obtener los IDs de los productos seleccionados
    const idsSeleccionados = Array.from(checkboxesSeleccionados).map(cb => parseInt(cb.value));

    // Filtrar los productos basados en los checkboxes seleccionados
    const productosSeleccionados = productosFiltrados.filter(producto => idsSeleccionados.includes(producto.id));

    // Actualizar la gráfica con los productos seleccionados
    actualizarGrafica(productosSeleccionados);
}

// Función para actualizar la gráfica con los productos seleccionados
function actualizarGrafica(productosSeleccionados) {
    if (!chart) return; // Si la gráfica no está inicializada, salir

    // Agrupar los productos seleccionados por SKU
    const productosAgrupados = agruparYSumarPorSku(productosSeleccionados);

    // Eliminar las barras actuales de la gráfica
    chart.data.labels = [];  // Eliminar etiquetas
    chart.data.datasets[0].data = [];  // Eliminar datos

    // Agregar las nuevas etiquetas y datos para los productos seleccionados
    productosAgrupados.forEach(item => {
        chart.data.labels.push(item.nombre);  // Etiqueta de la barra
        chart.data.datasets[0].data.push(item.cantidadTotal);  // Datos de las barras
    });

    // Calcular el nuevo total
    const total = productosAgrupados.reduce((sum, item) => sum + item.costeTotal, 0);

    // Actualizar el total en el contenedor
    const totalContainer = document.getElementById('totalmyBarChartStock');
    if (totalContainer) {
        totalContainer.textContent = "Stock " + total.toFixed(2) + '€';
    }

    // Refrescar la gráfica
    chart.update();
}
