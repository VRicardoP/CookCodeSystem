import { fetchStockOfRestaurant } from "./../public/js/utils";

async function cargarStock() {
  let restaurant_id = localStorage.getItem("restaurant_id");

  try {
    const datos = await fetchStockOfRestaurant(restaurant_id);
    console.log(`Stock original cargado: ${JSON.stringify(datos)}`);

    // 🔁 Agrupar por elaborado_id y sumar stock
    const stockAcumulado = {};

    datos.forEach((item) => {
      const id = item.elaborado_id;

      if (!id) return; // Ignorar si no tiene elaborado_id

      const cantidad = Number(item.cantidad_stock); // 👈 convertir a número

      if (!stockAcumulado[id]) {
        stockAcumulado[id] = 0;
      }

      stockAcumulado[id] += cantidad; // ✅ suma numérica
    });

    console.log("Stock acumulado por elaborado_id:", stockAcumulado);

    let listaProductos = document.querySelectorAll(".producto-btn");

    listaProductos.forEach((producto) => {
      const idProducto = producto.id.replace("btn-", "");

      const stockTotal = stockAcumulado[idProducto] ?? 0;

      let spanStock = document.createElement("span");
      spanStock.id = `stock-${idProducto}`;
      spanStock.textContent = `stock: ${stockTotal}`;
      spanStock.dataset.stock = stockTotal;

      if (stockTotal <= 0) {
        producto.className = "btn-sin-stock";
      }

      producto.appendChild(spanStock);
    });
  } catch (error) {
    console.log(`Error al cargar stock: ${error}`);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  cargarStock();
});
