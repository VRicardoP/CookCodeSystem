document.addEventListener("DOMContentLoaded", () => {
  const tipoPlato = document.getElementById("tipo_plato");

  cambioSelect();
  mostrarTabla(ing, recIng, ing);

  function cambioSelect() {
    tipoPlato.addEventListener("change", (e) => {
      if (e.target.value === "ing") mostrarTabla(ing, recIng, ing);
      if (e.target.value === "pre") mostrarTabla(pre, recIng, ing);
      if (e.target.value === "elab") mostrarTabla(elab, recIng, ing);
    });
  }

  function mostrarTabla(arr, recIng, ing) {
    const cont = document.querySelector("#lista");
    cont.innerHTML = "";
    const table = document.createElement("table");
    table.style.cssText = "border-collapse:collapse;width:100%";
    let row;
    arr.forEach((item, i) => {
      if (i % 3 === 0) {
        row = document.createElement("tr");
        table.appendChild(row);
      }
      const cell = document.createElement("td");
      cell.appendChild(crearCard(item, recIng, ing));
      row.appendChild(cell);
    });
    // Añadir celdas vacías si hace falta
    const rem = arr.length % 3;
    if (rem) {
      for (let i = 0; i < 3 - rem; i++) {
        const td = document.createElement("td");
        td.style.cssText = "padding:10px;width:33.33%";
        row.appendChild(td);
      }
    }
    cont.appendChild(table);
  }

  function crearCard(item, recIng, ing) {
    let nombre;
    let imgSrc;
    if (item.fName) {
      nombre = item.fName;
      imgSrc = "./." + item.image;
    } else {
      nombre = item.receta;
      imgSrc = "./." + item.imagen;
    }

    const card = document.createElement("div");
    card.classList.add("card");
    Object.assign(card.style, {
      border: "3px solid #007934",
      borderRadius: "25px",
      padding: "15px",
      textAlign: "center",
      width: "250px",
      marginBottom: "50px",
    });

    const img = document.createElement("img");
    img.src = imgSrc;
    img.style.cssText = "height:160px;border-radius:10px";

    const p = document.createElement("p");
    p.textContent = nombre;
    p.style.cssText = "margin-top:5px;font-size:20px";

    const btn = document.createElement("button");
    btn.textContent = "Asign Restaurant";
    Object.assign(btn.style, {
      marginTop: "5px",
      borderRadius: "10px",
      backgroundColor: "#007934",
      color: "#fff",
      padding: "4px 10px",
    });
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      // Pasamos el array completo `item` junto a tipo y nombre
      mostrarRestaurantesModal(item, tipoPlato.value, nombre, recIng, ing);
    });

    card.append(img, p, btn);
    return card;
  }

  async function fetchJson(url, opts = {}) {
    const res = await fetch(url, {
      credentials: "include",
      headers: { "Content-Type": "application/json" },
      ...opts,
    });
    const text = await res.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch {
      throw new Error("Respuesta inválida: " + text);
    }
    if (!res.ok || data.error) {
      throw new Error(data.message || `Error ${res.status}`);
    }
    return data;
  }

  async function mostrarRestaurantesModal(
    itemArray,
    tipo,
    nombre,
    recIng,
    ing
  ) {
    // Crear modal
    const modal = document.createElement("div");
    modal.classList.add("modal-overlay");
    modal.innerHTML = `
      <div class="modal-content">
        <h3>Assign ${nombre} to restaurants</h3>
        <div id="restaurant-list">Cargando restaurantes…</div>
        <button id="btnGuardar" class="btn-primary rounded">Save</button>
        <button id="btnCerrar" class="btn-danger rounded">Close</button>
      </div>`;
    document.body.appendChild(modal);

    // Obtener todos los restaurantes
    let restaurantes = [];
    try {
      restaurantes = await fetchJson(
        "/restaurant/public/api/getRestaurants.php"
      );
    } catch (e) {
      document.getElementById("restaurant-list").textContent =
        "Error cargando restaurantes";
      console.error(e);
      return;
    }

    // Calcular precio de coste y venta
    if (tipo == "pre" || tipo == "elab") {
      var precioCoste = 0;

      recIng.forEach((x) => {
        if (itemArray.id === x.receta) {
          console.log(
            "Ingrediente id: " + x.ingrediente + ", Cantidad: " + x.cantidad
          );

          ing.forEach((y) => {
            if (y.ID === x.ingrediente) {
              console.log("Ingrediente: " + y.fName);

              let precioMerma = y.merma * x.cantidad;

              precioCoste += x.cantidad * y.costPrice + precioMerma;
              precioVenta = (precioCoste * 1.5).toFixed(2);
            }
          });

          console.log("Precio total coste: " + precioCoste.toFixed(2));
          console.log("Precio total venta: " + precioVenta);
          itemArray.precio = precioVenta;
          console.log("--------------------------Item:"+ itemArray.precio);
        }
      });
    }

    // Renderizar checkboxes
    const listDiv = modal.querySelector("#restaurant-list");
    listDiv.innerHTML = "";
    restaurantes.forEach((r) => {
      const cb = document.createElement("input");
      cb.type = "checkbox";
      cb.id = `r-${r.restaurante_id}`;
      cb.value = r.restaurante_id;

      const label = document.createElement("label");
      label.htmlFor = cb.id;
      label.innerText = r["Dirección"];

      const row = document.createElement("div");
      row.append(cb, label);
      listDiv.appendChild(row);
    });

    // Cerrar modal
    modal
      .querySelector("#btnCerrar")
      .addEventListener("click", () => document.body.removeChild(modal));

    // Guardar asignaciones
    modal.querySelector("#btnGuardar").addEventListener("click", async () => {
      const checks = Array.from(
        listDiv.querySelectorAll("input[type=checkbox]")
      );
      const selectedIds = checks
        .filter((cb) => cb.checked)
        .map((cb) => parseInt(cb.value));


      
      // POST al endpoint correspondiente
      const endpoint = "/restaurant/public/api/platos.php";
  console.log("Item array-------------: "+itemArray);
      // Payload: enviamos el array completo `itemArray` y los restaurantes
      const payload = {
        items: itemArray,
        restaurantes: selectedIds,
      };

      try {
        await fetchJson(endpoint, {
          method: "POST",
          body: JSON.stringify(payload),
        });
        alert("Dish assigned successfully");
        document.body.removeChild(modal);
      } catch (e) {
        alert("Error guardando: " + e.message);
      }
    });
  }
});
