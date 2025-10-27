<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>TPV Restaurante</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


</head>

<body>

  <header style="color: white;">
    <div>
      <a href="mesas.html" id="backButton" style="text-decoration: none; font-weight: bold; color: white;">
        <i class="fas fa-arrow-circle-left" style="margin-right: 8px;"></i>
        Mesas
      </a>
    </div>

    <div><strong>Mesa: <span id="nombreMesa">Sin nombre</span></strong></div>
    <div class="estado" style="position: relative;">
      <i class="fas fa-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #aaa;"></i>
      <input type="text" id="buscadorProductos" placeholder="Buscar producto..." style="padding: 5px 10px 5px 30px; border-radius: 4px; border: 1px solid #ccc;">
    </div>


    <div><?= date('d/m/Y') ?></div>
  </header>

  <div class="container">
    <div class="panel-izquierdo">

      <!-- CABECERA DE PRODUCTOS -->
      <div class="cabecera-productos">
        <div class="col cantidad">Uds.</div>
        <div class="col descripcion">Producto</div>
        <div class="col precio">Precio</div>
        <div class="col total">Total</div>
      </div>

      <!-- LISTA DE PRODUCTOS -->
      <div class="productos" id="panelProductos">
        <!-- Aquí se insertarán dinámicamente las líneas de productos en formato tabla -->
      </div>

      <div id="totalContainer">
        <div>Total: <span id="totalPedido">0.00€</span></div>
        <div>IVA (<span id="ivaPorcentaje">21</span>%): <span id="ivaCalculado">0.00€</span></div>
        <div>Total + IVA: <span id="totalPedidoConIVA">0.00€</span></div>
      </div>


      <div class="teclado">
        <button>7</button><button>8</button><button>9</button>
        <button style="background-color: red; color: white;" id="delButton">DEL</button>

        <button>4</button><button>5</button><button>6</button>
        <button style="background-color: #2C73BB; color: white;">DTO</button>

        <button>1</button><button>2</button><button>3</button>
        <button style="background-color: #2C73BB; color: white;">CAN</button>

        <button>0</button><button>.</button><button id="clrButton">CLR</button>
        <button style="background-color: #2C73BB; color: white;">PREC</button>
      </div>


    </div>

    <div class="panel-categorias">
      <div class="categorias">
        <button class="categoria-estatica" data-categoria-id="todo">Todo</button>
        <button class="categoria-estatica" data-categoria-id="1">Menus</button>
        <button class="categoria-estatica" data-categoria-id="2">Platos combinados</button>
      </div>
      <div class="categorias-dinamicas"></div>
    </div>

    <div class="panel-derecho">
      <div class="productos-grid" id="productosGrid"></div>
    </div>
  </div>

  <footer>
    <button id="cobrarButton">Cobrar</button>
    <button id="imprimirTicket">Imprimir</button>
    <button id="btnImpuestos">Impuestos</button>

  </footer>

  <div id="modalConfirmacion" class="modal">
    <div id="modalFondo" class="modal-content">
      <h2>Resumen de la comanda</h2>
      <div id="ticketContenido" class="ticket">
        <div class="ticket-header">
          RESTAURANTE EL SABOR<br>
          C/ Buen Gusto, 123 - Valencia<br>
          CIF: X12345678<br>
          <span id="ticketFecha"></span>
        </div>
        <div id="ticketLineas"></div>
        <div class="ticket-total">
          TOTAL: <strong id="ticketTotal">0.00€</strong>
        </div>
        <div class="ticket-footer">
          ¡Gracias por su visita!<br>
          Factura simplificada<br>
          No válida como factura
        </div>
      </div>
      <div class="ticket-buttons">
        <button id="descargarPDF">Descargar PDF</button>
        <button id="confirmarCobro">Aceptar</button>
        <button id="cancelarCobro">Cancelar</button>
      </div>
    </div>
  </div>



  <div id="modalImpuestos" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div style="background:white; padding:20px; border-radius:8px; width:300px; text-align:center;">
      <h2>Configuración de IVA</h2>
      <label for="ivaInput">IVA (%)</label>
      <input type="number" id="ivaInput" value="21" min="0" max="100" style="width:80px; margin:10px;" />
      <div style="margin-top: 15px;">
        <button id="guardarIVA">Guardar</button>
        <button id="cerrarModalIVA">Cancelar</button>
      </div>
    </div>
  </div>


  <script type="module" src="./../public/js/utils.js"></script>

  <script>
    const valorIVA = 21; // IVA por defecto


    const grid = document.getElementById('productosGrid');
    const errorHeader = document.getElementById('errorHeader');
    const contenedorCategorias = document.querySelector('.categorias-dinamicas');
    const panelProductos = document.getElementById('panelProductos');
    const delButton = document.getElementById('delButton');
    const cobrarButton = document.getElementById('cobrarButton');
    const modalConfirmacion = document.getElementById('modalConfirmacion');
    const confirmarCobro = document.getElementById('confirmarCobro');
    const cancelarCobro = document.getElementById('cancelarCobro');
    let productos = [];
    let productoSeleccionado = null;

    function obtenerParametro(nombre) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(nombre);
    }

    const mesaId = obtenerParametro('mesa_id'); // Obtener el mesa_id de la URL
    // Si hay un mesa_id, hacemos una solicitud para obtener los detalles de la mesa
    if (mesaId) {
      fetch('database/mesas.php?mesa_id=' + mesaId)
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            console.error('Error al obtener la mesa:', data.error);
            document.getElementById('nombreMesa').textContent = 'Mesa no encontrada';
          } else {
            // Si la mesa se encuentra, actualizamos el nombre
            document.getElementById('nombreMesa').textContent = data.nombre;
          }
        })
        .catch(error => {
          console.error('Error en la solicitud:', error);
          document.getElementById('nombreMesa').textContent = 'Error al cargar el nombre de la mesa';
        });
    } else {
      // Si no hay mesa_id, mostramos un mensaje predeterminado
      document.getElementById('nombreMesa').textContent = 'Sin nombre';
    }

    // Cargar productos desde la base de datos
    fetch('database/platos.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          errorHeader.textContent = "ERROR DATABASE CONNECTION";
        } else {
          productos = data;
          mostrarProductos(productos);
          cargarComandasDesdeBD(); // Cargar las comandas al cargar la página
        }
      })
      .catch(error => {
        console.error('Error al obtener los productos:', error);
        errorHeader.textContent = "ERROR DATABASE CONNECTION";
      });

    fetch('database/categorias.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          errorHeader.textContent = "ERROR CATEGORIAS";
        } else {
          contenedorCategorias.innerHTML = '';
          data.forEach(categoria => {
            const btn = document.createElement('button');
            btn.textContent = categoria.Nombre;
            btn.style.backgroundColor = categoria.Color;
            btn.dataset.categoriaId = categoria.Categoria_id;
            btn.addEventListener('click', () => {
              const categoriaId = btn.dataset.categoriaId;
              const filtrados = productos.filter(p => p.Categoria_id == categoriaId);
              mostrarProductos(categoriaId === 'todo' ? productos : filtrados);
            });
            contenedorCategorias.appendChild(btn);
          });
        }
      })
      .catch(error => {
        console.error('Error al obtener las categorías:', error);
        errorHeader.textContent = "ERROR CATEGORIAS";
      });
    //let restaurant_id = localStorage.getItem('restaurant_id');
    //let datos= fetchStockOfRestaurant(restaurant_id);
    //console.log('fecth:'+datos);

    document.querySelectorAll('.categoria-estatica').forEach(button => {
      button.addEventListener('click', (e) => {
        const categoriaId = e.target.dataset.categoriaId;
        const filtrados = productos.filter(p => p.Categoria_id == categoriaId);
        mostrarProductos(categoriaId === 'todo' ? productos : filtrados);
      });
    });

    function mostrarProductos(lista) {
      grid.innerHTML = '';
      if (lista.length === 0) {
        grid.innerHTML = '<p>No hay productos disponibles</p>';
      } else {
        lista.forEach(producto => {

          console.log("imagen--------------" + producto.imagen);
          // Corregimos la ruta de la imagen
          const imagenCorregida = producto.imagen.replace(
            /(\.\/|\.\.\/)+img_dishes\//,
            '/kitchen/menus/plating/img_dishes/'
          );
          const nameImage = producto.imagen.split('/').pop();

          console.log("nameImagen--------------" + nameImage);

          const btn = document.createElement('button');
          btn.className = 'producto-btn';

          //******************** ASIGNAR ID A LOS BOTONES DE LOS PRODUCTOS******************/
          btn.id = `btn-${producto.id}`;
          //******************** **********************************************************/
          btn.innerHTML = `
        <img src="http://localhost:8080/kitchen/menus/plating/img_dishes/${nameImage}" alt="${producto.nombre}" class="producto-img" />
        <span>${producto.nombre} - ${producto.Precio}€</span>
      `;

          btn.addEventListener('click', () => {
            agregarProductoAlPanel(producto);
            descontarStockAlAgregarProducto(btn, producto);

          });
          grid.appendChild(btn);
        });
      }
    }


   async function descontarStockAlAgregarProducto(btn, producto) {

     
      let spanStock = btn.querySelector(`#stock-${producto.id}`)
      let stock = parseInt(spanStock.dataset.stock);
      stock = Math.max(stock - 1, 0);
      spanStock.dataset.stock = stock;
      spanStock.textContent = `stock: ${stock}`;


  if (stock <= 0) {
      
        btn.className ="btn-sin-stock";
     
    } else {
       btn.className ="producto-btn";
      
      
    }

    let productoUsado = {
      id: producto.id,
      cantidad: stock

    }


 //****************  Enviar POST para descontar stock********************************/
     fetch('database/descontar_stock.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    producto: productoUsado
  })
})
.then(async response => {
  const text = await response.text(); // 👈 ver el contenido original
  console.log("Respuesta cruda del servidor:", text); // 🔍 importante para depurar

  try {
    const data = JSON.parse(text); // intenta parsear
    if (!data.success) {
      console.error("Error al descontar stock:", data.error);
      alert("Error al descontar stock");
    }
  } catch (e) {
    console.error("No se pudo parsear JSON:", e, text);
  }
})
.catch(error => {
  console.error("Error en la petición de descuento de stock:", error);
});




    };

    async function cargarStock() {
      const pathApiStock = './../api/getStock.php';
      try {
        const response = await fetch(pathApiStock);
        const data = await response.json();
        console.log(`Stock cargado ${JSON.stringify(data)}`);
      } catch (error) {
        console.log(`Error al cargar el stock ${error}`);

      }


    }



    function agregarProductoAlPanel(producto) {
      console.log('Producto seleccionado');
      const existente = panelProductos.querySelector(`[data-producto-id="${producto.id}"]`);

      if (existente) {
        const cantidadEl = existente.querySelector('.cantidad');
        const totalEl = existente.querySelector('.total');
        const cantidad = parseInt(cantidadEl.textContent) + 1;
        const total = (cantidad * parseFloat(producto.Precio)).toFixed(2);
        cantidadEl.textContent = cantidad;
        totalEl.textContent = `${total}€`;

        actualizarComandaEnBD(producto.id, cantidad);

        if (productoSeleccionado !== existente) {
          if (productoSeleccionado) productoSeleccionado.classList.remove('seleccionado');
          existente.classList.add('seleccionado');
          productoSeleccionado = existente;
        }
      } else {
        const div = document.createElement('div');
        div.className = 'linea-producto';
        div.dataset.productoId = producto.id;

        div.innerHTML = `
      <div class="col cantidad">1</div>
      <div class="col descripcion">
        <strong>${producto.nombre}</strong>
      </div>
      <div class="col precio">${parseFloat(producto.Precio).toFixed(2)}€</div>
      <div class="col total">${parseFloat(producto.Precio).toFixed(2)}€</div>
    `;

        div.addEventListener('click', () => {
          if (productoSeleccionado && productoSeleccionado !== div) {
            productoSeleccionado.classList.remove('seleccionado');
          }
          if (productoSeleccionado === div) {
            div.classList.remove('seleccionado');
            productoSeleccionado = null;
          } else {
            div.classList.add('seleccionado');
            productoSeleccionado = div;
          }
        });

        panelProductos.appendChild(div);
        if (productoSeleccionado) productoSeleccionado.classList.remove('seleccionado');
        div.classList.add('seleccionado');
        productoSeleccionado = div;

        actualizarComandaEnBD(producto.id, 1);
      }

      actualizarTotal();
    }




    function actualizarTotal() {
      const lineas = panelProductos.querySelectorAll('.linea-producto');
      let total = 0;
      lineas.forEach(linea => {
        const totalTexto = linea.querySelector('.total').textContent.replace('€', '');
        total += parseFloat(totalTexto);
      });

      // Actualizar el total sin IVA
      document.getElementById('totalPedido').textContent = total.toFixed(2) + '€';

      // Calcular IVA
      const ivaCalculado = total * valorIVA / 100;
      document.getElementById('ivaCalculado').textContent = ivaCalculado.toFixed(2) + '€';

      // Total + IVA
      const totalConIVA = total + ivaCalculado;

 let spanTotal=document.getElementById('totalPedidoConIVA')

     document.getElementById('totalPedidoConIVA').textContent = totalConIVA.toFixed(2) + '€';
      spanTotal.dataset.total = totalConIVA.toFixed(2);
      // Actualizar el texto del porcentaje de IVA mostrado
      document.getElementById('ivaPorcentaje').textContent = valorIVA.toFixed(2);
    }




    function actualizarComandaEnBD(productoId, cantidad) {
      const mesaId = obtenerParametro('mesa_id'); // Este valor debe ser el ID numérico de la mesa

      // Verificar si los parámetros son correctos
      if (!mesaId) {
        console.error("Mesa ID no encontrado");
        return;
      }
      if (!productoId || !cantidad) {
        console.error("Datos incompletos: productoId y cantidad son requeridos.");
        return;
      }

      // Crear el cuerpo de la solicitud
      const bodyData = {
        mesa_id: mesaId, // ID de la mesa
        producto_id: productoId,
        cantidad: cantidad
      };

      console.log("Datos enviados:", bodyData); // Verifica los datos en la consola

      fetch('database/comandas.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(bodyData) // Enviar los datos correctamente
        })
        .then(response => response.json())
        .then(data => {
          console.log('Comanda actualizada:', data);
          if (data.error) {
            console.error('Error al actualizar la comanda:', data.error);
          }
        })
        .catch(error => {
          console.error('Error al actualizar la comanda:', error);
        });
    }


    // Cargar comandas de la base de datos
    function cargarComandasDesdeBD() {
      fetch(`database/comandas.php?mesa_id=${mesaId}`) // CORREGIDO
        .then(response => response.json())
        .then(data => {
          console.log(data); // Verifica qué datos se están recibiendo
          if (Array.isArray(data)) {
            data.forEach(comanda => {
              const producto = productos.find(p => p.id == comanda.producto_id);
              if (producto) {
                const existente = panelProductos.querySelector(`[data-producto-id="${producto.id}"]`);
                if (!existente) {
                  // Crear el contenedor 'div' con la estructura de columnas
                  const div = document.createElement('div');
                  div.className = 'linea-producto';
                  div.dataset.productoId = producto.id;

                  div.innerHTML = `
                <div class="col cantidad">${comanda.cantidad}</div>
                <div class="col descripcion">
                  <strong>${producto.nombre}</strong>
                </div>
                <div class="col precio">${parseFloat(producto.Precio).toFixed(2)}€</div>
                <div class="col total">${(comanda.cantidad * parseFloat(producto.Precio)).toFixed(2)}€</div>
              `;

                  // Añadir el evento de selección/deselección
                  div.addEventListener('click', () => {
                    if (productoSeleccionado && productoSeleccionado !== div) {
                      productoSeleccionado.classList.remove('seleccionado');
                    }
                    if (productoSeleccionado === div) {
                      div.classList.remove('seleccionado');
                      productoSeleccionado = null;
                    } else {
                      div.classList.add('seleccionado');
                      productoSeleccionado = div;
                    }
                  });

                  // Agregar el nuevo producto al panel de productos
                  panelProductos.appendChild(div);
                }
              }
            });

            actualizarTotal();
          } else {
            console.error("La respuesta no es un array válido", data);
          }
        })
        .catch(error => {
          console.error('Error al cargar las comandas:', error);
          errorHeader.textContent = "ERROR AL CARGAR COMANDAS";
        });
    }



    function eliminarComandaDeBD(productoId) {
      fetch('database/comandas.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            mesa_id: mesaId, // CORREGIDO
            producto_id: productoId,
            accion: 'eliminar'
          })
        })
        .then(response => response.json())
        .then(data => {
          console.log('Comanda eliminada:', data);
        })
        .catch(error => {
          console.error('Error al eliminar la comanda:', error);
        });
    }




    // Eliminar producto del panel
    delButton.addEventListener('click', () => {
      if (productoSeleccionado) {
        const productoId = productoSeleccionado.dataset.productoId;

        // Elimina del DOM
        productoSeleccionado.remove();
        productoSeleccionado = null;
        actualizarTotal();

        // Elimina de la base de datos
        eliminarComandaDeBD(productoId);
      }
    });


    cobrarButton.addEventListener('click', () => {
      const ticketLineas = document.getElementById('ticketLineas');
      const ticketTotal = document.getElementById('ticketTotal');
      const ticketFecha = document.getElementById('ticketFecha');

      // Fecha actual
      const fecha = new Date();
      ticketFecha.textContent = fecha.toLocaleString();

      ticketLineas.innerHTML = '';
      let total = 0;

      // Añadir líneas de productos
      document.querySelectorAll('.linea-producto').forEach(linea => {
        const cantidad = linea.querySelector('.cantidad').textContent;
        const nombre = linea.querySelector('.descripcion').textContent.trim();
        const precioUnitario = parseFloat(linea.querySelector('.precio').textContent.replace('€', '').replace(',', '.'));
        const totalLinea = parseFloat(linea.querySelector('.total').textContent.replace('€', '').replace(',', '.'));

        const ticketLine = document.createElement('div');
        ticketLine.className = 'ticket-line';
        ticketLine.textContent = `${cantidad} x ${nombre}  ${precioUnitario.toFixed(2)}€  ${totalLinea.toFixed(2)}€`;

        ticketLineas.appendChild(ticketLine);
        total += totalLinea;
      });

      // Crear contenedor para el resumen de totales separado
      const resumenContainer = document.createElement('div');
      resumenContainer.style.marginTop = '15px';
      resumenContainer.style.borderTop = '1px solid #000';
      resumenContainer.style.paddingTop = '10px';

      // Total sin IVA
      const totalDiv = document.createElement('div');
      totalDiv.textContent = `TOTAL: ${total.toFixed(2)}€`;
      totalDiv.style.fontWeight = 'bold';
      resumenContainer.appendChild(totalDiv);

      // IVA
      const ivaCalculado = total * valorIVA / 100;
      const ivaDiv = document.createElement('div');
      ivaDiv.textContent = `IVA (${valorIVA.toFixed(2)}%): ${ivaCalculado.toFixed(2)}€`;
      resumenContainer.appendChild(ivaDiv);

      // Total + IVA
      const totalConIVADiv = document.createElement('div');
      totalConIVADiv.textContent = `TOTAL + IVA: ${(total + ivaCalculado).toFixed(2)}€`;
      totalConIVADiv.style.fontWeight = 'bold';
      resumenContainer.appendChild(totalConIVADiv);

      ticketLineas.appendChild(resumenContainer);

      // Actualizamos solo el total con IVA en el footer del modal
      ticketTotal.textContent = (total + ivaCalculado).toFixed(2) + '€';

      modalConfirmacion.style.display = 'block';
    });



    function actualizarContenidoTicket() {
      const ticketLineas = document.getElementById('ticketLineas');
      const ticketTotal = document.getElementById('ticketTotal');
      const ticketFecha = document.getElementById('ticketFecha');

      // Fecha actual
      const fecha = new Date();
      ticketFecha.textContent = fecha.toLocaleString();

      ticketLineas.innerHTML = '';
      let total = 0;

      // Añadir líneas de productos
      document.querySelectorAll('.linea-producto').forEach(linea => {
        const cantidad = linea.querySelector('.cantidad').textContent;
        const nombre = linea.querySelector('.descripcion').textContent.trim();
        const precioUnitario = parseFloat(linea.querySelector('.precio').textContent.replace('€', '').replace(',', '.'));
        const totalLinea = parseFloat(linea.querySelector('.total').textContent.replace('€', '').replace(',', '.'));

        const ticketLine = document.createElement('div');
        ticketLine.className = 'ticket-line';
        ticketLine.textContent = `${cantidad} x ${nombre}  ${precioUnitario.toFixed(2)}€  ${totalLinea.toFixed(2)}€`;

        ticketLineas.appendChild(ticketLine);
        total += totalLinea;
      });

      // Crear bloque resumen separado
      const resumenContainer = document.createElement('div');
      resumenContainer.style.marginTop = '15px';
      resumenContainer.style.borderTop = '1px solid #000';
      resumenContainer.style.paddingTop = '10px';

      // Total sin IVA
      const totalDiv = document.createElement('div');
      totalDiv.textContent = `TOTAL: ${total.toFixed(2)}€`;
      totalDiv.style.fontWeight = 'bold';
      resumenContainer.appendChild(totalDiv);

      // Calcular IVA e importe total con IVA
      const ivaCalculado = total * valorIVA / 100;
      const ivaDiv = document.createElement('div');
      ivaDiv.textContent = `IVA (${valorIVA.toFixed(2)}%): ${ivaCalculado.toFixed(2)}€`;
      resumenContainer.appendChild(ivaDiv);

      const totalConIVA = total + ivaCalculado;
      const totalConIVADiv = document.createElement('div');
      totalConIVADiv.textContent = `TOTAL + IVA: ${totalConIVA.toFixed(2)}€`;
      totalConIVADiv.style.fontWeight = 'bold';
      resumenContainer.appendChild(totalConIVADiv);

      ticketLineas.appendChild(resumenContainer);

      // Actualizar total + IVA en el footer del ticket
      ticketTotal.textContent = totalConIVA.toFixed(2) + '€';
      ticketTotal.dataset.total = totalConIVA.toFixed(2);
    }




    confirmarCobro.addEventListener('click', () => {

         // Preparar datos para enviar al backend
      const productosUsados = [];
      document.querySelectorAll('.linea-producto').forEach(linea => {
        const idProducto = parseInt(linea.dataset.productoId);
        const cantidad = parseInt(linea.querySelector('.cantidad').textContent);

        productosUsados.push({
          id: idProducto,
          cantidad: cantidad,
         
        });
      });


 const ticketTotal = document.getElementById('totalPedidoConIVA');
  const total = parseFloat(ticketTotal.dataset.total);

 

  const comanda = {
    mesa_id: mesaId,
    total: total,
    productos: productosUsados
  };

      guardarHistorialComanda(comanda);
      // Elimina las comandas de la base de datos
      fetch('database/comandas.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            mesa_id: mesaId, // CORREGIDO
            accion: 'finalizar'
          })
        })
        .then(response => response.json())
        .then(data => {
          console.log('Cobro confirmado y comandas eliminadas:', data);
        })
        .catch(error => {
          console.error('Error al finalizar la comanda:', error);
        });


   

      // Limpia la interfaz
      panelProductos.innerHTML = '';
      actualizarTotal();
      modalConfirmacion.style.display = 'none';
    });


    // Cancelar cobro
    cancelarCobro.addEventListener('click', () => {
      modalConfirmacion.style.display = 'none';
    });

    document.getElementById('descargarPDF').addEventListener('click', () => {
      const contenido = document.getElementById('ticketContenido');
      const opciones = {
        margin: 0.5,
        filename: 'ticket.pdf',
        image: {
          type: 'jpeg',
          quality: 0.98
        },
        html2canvas: {
          scale: 2
        },
        jsPDF: {
          unit: 'in',
          format: 'letter',
          orientation: 'portrait'
        }
      };
      html2pdf().set(opciones).from(contenido).save();
    });

    document.getElementById('imprimirTicket').addEventListener('click', () => {
      // Actualiza el contenido del ticket con los productos actuales
      console.log('Imprimiendo ticket...');
      actualizarContenidoTicket();

      const element = document.getElementById('ticketContenido');
      const options = {
        margin: 0,
        filename: `ticket_mesa_${mesaId}.pdf`,
        image: {
          type: 'jpeg',
          quality: 0.98
        },
        html2canvas: {
          scale: 2
        },
        jsPDF: {
          unit: 'mm',
          format: 'a5',
          orientation: 'portrait'
        }
      };

      html2pdf().set(options).from(element).save();
    });










    //BOTON CLEAR 
    const clrButton = document.getElementById('clrButton');

    clrButton.addEventListener('click', () => {










      // Elimina las comandas de la base de datos
      fetch('database/comandas.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            mesa_id: mesaId,
            accion: 'finalizar'
          })
        })
        .then(response => response.json())
        .then(data => {
          console.log('Comanda borrada con CLR:', data);
        })
        .catch(error => {
          console.error('Error al borrar la comanda con CLR:', error);
        });


        




      // Limpia la interfaz
      panelProductos.innerHTML = '';
      actualizarTotal();
      productoSeleccionado = null;
    });


    // Variables globales para controlar la entrada numérica compuesta
    let inputTemporal = '';
    let tiempoUltimoInput = 0;
    const TIEMPO_MAXIMO = 1000; // 5 segundos

    // Función para manejar los inputs numéricos (teclado virtual o físico)
    function manejarInputNumerico(digito) {
      if (!productoSeleccionado) return;

      const ahora = Date.now();

      // Si han pasado más de 5 segundos desde la última tecla, reinicia
      if (ahora - tiempoUltimoInput > TIEMPO_MAXIMO) {
        inputTemporal = '';
      }

      inputTemporal += digito;
      tiempoUltimoInput = ahora;

      const nuevaCantidad = parseInt(inputTemporal);
      if (isNaN(nuevaCantidad)) return;

      const cantidadEl = productoSeleccionado.querySelector('.cantidad');
      const precioEl = productoSeleccionado.querySelector('.precio');
      const totalEl = productoSeleccionado.querySelector('.total');

      if (cantidadEl && precioEl && totalEl) {
        const precioUnitario = parseFloat(precioEl.textContent.replace('€', ''));
        cantidadEl.textContent = nuevaCantidad;
        totalEl.textContent = (nuevaCantidad * precioUnitario).toFixed(2) + '€';

        const productoId = productoSeleccionado.dataset.productoId;
        actualizarComandaEnBD(productoId, nuevaCantidad);
        actualizarTotal();
      }
    }

    // Escuchar clics en los botones numéricos del teclado táctil
    document.querySelectorAll('.teclado button').forEach(btn => {
      const valor = btn.textContent.trim();

      // Solo números válidos (0-9)
      if (!isNaN(valor) && valor !== '') {
        btn.addEventListener('click', () => {
          manejarInputNumerico(valor);
        });
      }
    });

    // Función para filtrar productos según el input
    const buscadorInput = document.getElementById('buscadorProductos');

    buscadorInput.addEventListener('input', (e) => {
      const texto = e.target.value.toLowerCase();
      const filtrados = productos.filter(p => p.nombre.toLowerCase().includes(texto));
      mostrarProductos(filtrados);
    });




    document.getElementById('btnImpuestos').addEventListener('click', () => {
      document.getElementById('ivaInput').value = valorIVA;
      document.getElementById('modalImpuestos').style.display = 'flex';
    });

    document.getElementById('guardarIVA').addEventListener('click', () => {
      const nuevoIVA = parseFloat(document.getElementById('ivaInput').value);
      if (!isNaN(nuevoIVA) && nuevoIVA >= 0 && nuevoIVA <= 100) {
        valorIVA = nuevoIVA;
        actualizarTotal();
      }
      document.getElementById('modalImpuestos').style.display = 'none';
    });

    document.getElementById('cerrarModalIVA').addEventListener('click', () => {
      document.getElementById('modalImpuestos').style.display = 'none';
    });




    const apiGuardarComandas = './database/historial_comandas.php';


    async function guardarHistorialComanda(comanda) {
      console.log("Dentro de historial comanda************" + JSON.stringify(comanda));


      try {
        const response = await fetch(apiGuardarComandas, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
         body: JSON.stringify({ comanda: comanda })
        });

        if (!response.ok) {
          throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();
        console.log(data);

      } catch (error) {
        console.error('Error en la petición:', error);
      }

    }
  </script>
  <script type="module" src="./gestionStock.js"></script>

  <style>
    /* Estilos para la ventana modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      text-align: center;
    }

    .modal button {
      margin: 10px;
      padding: 10px;
      cursor: pointer;
    }
  </style>

</body>

</html>