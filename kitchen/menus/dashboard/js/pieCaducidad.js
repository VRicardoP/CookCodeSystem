import { obtenerDatosProductosEcommerce } from './getDatos.js';



function mostrarLoader(mostrar) {
    const loader = document.getElementById('loaderPie');
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

// Función para inicializar la gráfica de caducidad
export function inicializarGraficaCaducidad() {
    let chart = null; // Declarar chart aquí para que esté disponible en todo el scope

   // Mostrar el loader antes de empezar a cargar los datos
   mostrarLoader(true);


    // Obtener los productos del ecommerce
    obtenerDatosProductosEcommerce()
        .then(productos => {
            console.log("Dentro de grafica pie:", productos);

            // Filtrar productos por categoría
            let lotesIngredientes = filtrarProductosPorCategoria(productos, ["Lotes de ingredientes", "Lotes de elaborados"]); // Obtener los lotes de ingredientes y elaborados
            let ingredientes = filtrarProductosPorCategoria(productos, ["Ingredientes", "Elaborado"]); // Obtener los ingredientes y elaborados

            // Asignar cost_price de los ingredientes/elaborados a los lotes correspondientes por nombre
            ingredientes.forEach(ingrediente => {
                const nombreIngrediente = ingrediente.name.trim().toLowerCase(); // Normalizamos el nombre del ingrediente/elaborado
                const costPrice = ingrediente.cost_price;

                console.log(`Procesando ingrediente/elaborado: ${ingrediente.name}, normalizado: ${nombreIngrediente}`);

                let loteEncontrado = false; // Variable para verificar si se encontró el lote

                lotesIngredientes.forEach(lote => {
                    const nombreLote = lote.name.trim().toLowerCase(); // Normalizamos el nombre del lote también

                    // Si el nombre del lote coincide con el del ingrediente, asignamos el cost_price
                    if (nombreLote === nombreIngrediente && costPrice !== undefined) {
                        lote.cost_price = costPrice;  // Asignamos el cost_price al lote correspondiente
                        console.log(`Asignado cost_price ${costPrice} a lote de ingrediente/elaborado ${lote.name}`);
                        loteEncontrado = true; // Marcamos que encontramos un lote correspondiente
                    }
                });

                // Si no se encontró un lote para este ingrediente, mostramos un mensaje de advertencia
                if (!loteEncontrado) {
                    console.warn(`No se encontró un lote para el ingrediente/elaborado: ${ingrediente.name}`);
                }
            });

            // Aquí puedes hacer cualquier otra cosa con los lotes de ingredientes/elaborados actualizados
            console.log("Lotes Ingredientes/Elaborados Actualizados:", lotesIngredientes);

            // Filtrar productos por las categorías específicas
            const categoriasFiltrarLotes = ["Lotes de ingredientes", "Lotes de elaborados"];
            const lotesFiltrados = filtrarProductosPorCategoria(productos, categoriasFiltrarLotes);
            console.log("Lotes de pie:", lotesFiltrados);

            // Obtener el contexto del canvas para dibujar la gráfica
            const ctx = document.getElementById('productosCaducidadChart').getContext('2d');

            // Obtener el estado de los productos (caducados, a 7 días y más de 7 días)
            const estadoProductos = obtenerProductosPorEstado(lotesIngredientes);
            var cantidadCaducados = 0;
            var cantidadASieteDias = 0;
            var cantidadBien = 0;

            estadoProductos.caducados.forEach(element => {
                cantidadCaducados += element.stock_quantity;
            });

            estadoProductos.aSieteDias.forEach(element => {
                cantidadASieteDias += element.stock_quantity;
            });

            estadoProductos.bien.forEach(element => {
                cantidadBien += element.stock_quantity;
            });

            console.log("Estado productos:", estadoProductos);



// Ocultar el loader antes de renderizar la gráfica
mostrarLoader(false);





            // Crear la gráfica de tipo pie
            chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Expired', '7 Days to Expire', 'More than 7 Days to Expire'],
                    datasets: [{
                        label: 'Product Status',
                        data: [
                            cantidadCaducados,
                            cantidadASieteDias,
                            cantidadBien
                        ],
                        backgroundColor: ['#cc3339', '#FFCC00', '#006400']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    },
                    // Dentro del onClick, donde muestras los productos seleccionados en el modal
                    onClick: function (evt) {
                        const elements = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
                        if (elements.length > 0) {
                            const index = elements[0]._index; // Obtener el índice del segmento clicado

                            let productosSeleccionados;
                            let color;
                            if (index === 0) {
                                productosSeleccionados = estadoProductos.caducados;
                                color = '#cc3339';
                            } else if (index === 1) {
                                productosSeleccionados = estadoProductos.aSieteDias;
                                color = '#FFCC00';
                            } else if (index === 2) {
                                productosSeleccionados = estadoProductos.bien;
                                color = '#006400';
                            } else {
                                productosSeleccionados = [];
                            }

                            // Mostrar el modal
                            $('#pieModal').modal('show');

                           

                            // Crear tabla en lugar de lista
                            let contenido = `
            <table class="table table-striped" style="color: black;">
                <thead>
                    <tr>
                        <th>Batch</th>
                        <th>Name</th>
                        <th>Expiration Date</th>
                        <th>Amount</th>
                        <th>Cost</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody >
        `;

                            let precioTotal = 0;
                            let costeTotal = 0;

                            // Agregar las filas de productos
                            productosSeleccionados.forEach(producto => {
                                let fechaCaducidad = new Date(producto.fecha_caducidad);

                                // Formatear la fecha en 'DD/MM/YYYY'
                                let fechaFormateada = fechaCaducidad.toLocaleDateString('es-ES', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                });


                                contenido += `
                <tr>
                    <td>${producto.sku}</td>
                    <td>${producto.name}</td>
                    
                    <td>${fechaFormateada}</td>
                    <td>${producto.stock_quantity}</td>
                     <td>${(producto.cost_price * producto.stock_quantity).toFixed(2)}</td>
                    <td>${(producto.price * producto.stock_quantity).toFixed(2)}</td>
                </tr>
            `;
                                precioTotal += producto.price * producto.stock_quantity;
                                costeTotal += producto.cost_price * producto.stock_quantity;
                            });

                            // Cerrar la tabla y agregar el total
                            contenido += `
                </tbody>
            </table>
            <hr>
             <p>Total cost of products: ${costeTotal.toFixed(2)}</p>
            <p>Product opportunity price: ${precioTotal.toFixed(2)}</p>
        `;
                            // Cambiar el color de fondo del contenedor
                            $('#headerPie').css('background-color', color);
                            // Insertar el contenido en el contenedor del modal
                            $('#containerPie').html(contenido);
                        }
                    }

                }
            });
        })
        .catch(error => {
            console.error('Error al procesar los datos de productos:', error);
        });

    return chart;
}

// Función para obtener productos por estado
function obtenerProductosPorEstado(productos) {
    const hoy = new Date();

    const caducados = [];
    const aSieteDias = [];
    const bien = [];

    productos.forEach(producto => {
        const fechaCaducidad = new Date(producto.fecha_caducidad);
        const diferenciaDias = Math.ceil((fechaCaducidad - hoy) / (1000 * 60 * 60 * 24));

        if (diferenciaDias < 0) {
            caducados.push(producto);
        } else if (diferenciaDias <= 7) {
            aSieteDias.push(producto);
        } else {
            bien.push(producto);
        }
    });

    return {
        caducados: caducados,
        aSieteDias: aSieteDias,
        bien: bien
    };
}
