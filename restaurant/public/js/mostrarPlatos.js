document.addEventListener("DOMContentLoaded", () => {
  renderTable(platos);
});

function renderTable(platos) {
  const lista = document.getElementById("lista");
  lista.innerHTML = "";
  const table = document.createElement("table");
  table.style.cssText = "border-collapse:collapse;width:100%";

  renderRows(platos, table);

  lista.appendChild(table);
}

function renderRows(items, table) {
  const cols = 3;
  let row;
  items.forEach((item, i) => {
    if (i % cols === 0) {
      row = document.createElement("tr");
      table.appendChild(row);
    }
    const cell = document.createElement("td");
    cell.appendChild(createCard(item));
    row.appendChild(cell);
  });

  // Añadir celdas vacías si hace falta
  const rem = items.length % cols;
  if (rem) {
    for (let i = 0; i < cols - rem; i++) {
      const td = document.createElement("td");
      td.style.cssText = "padding:10px;width:33.33%";
      row.appendChild(td);
    }
  }
}

function createCard(item) {
  const card = document.createElement("div");
  card.className = "card";

  const img = document.createElement("img");
  img.src = `./../../kitchen/img/ingredients/.${item.imagen}`;
  img.style.cssText = "height:160px;border-radius:10px";

  const p = document.createElement("p");
  p.textContent = item.nombre;
  p.style.cssText = "margin-top:5px;font-size:20px";

  card.append(img, p);
  return card;
}
