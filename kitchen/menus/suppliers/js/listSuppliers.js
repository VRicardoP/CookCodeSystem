document.addEventListener("DOMContentLoaded", function () {
  // Selecciona todas las tablas dentro de cards
  const cards = document.querySelectorAll(".card");
  cards.forEach((card) => {
    const table = card.querySelector(".table");
    if (!table) return;
    const tbody = table.querySelector("tbody");
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const pageSize = 10;
    let currentPage = 0;
    const totalPages = Math.ceil(rows.length / pageSize);

    // Buscar el header de la card
    const cardHeader = card.querySelector(".card-header");
    if (!cardHeader) return;

    // Ajustar el card-header para que el título y la paginación estén alineados horizontalmente
    cardHeader.style.display = "flex";
    cardHeader.style.justifyContent = "space-between";
    cardHeader.style.alignItems = "center";

    // Crear contenedor de paginación
    const paginationDiv = document.createElement("div");
    paginationDiv.style.display = "flex";
    paginationDiv.style.justifyContent = "flex-end";
    paginationDiv.style.alignItems = "center";
    paginationDiv.style.gap = "10px";
    paginationDiv.style.margin = "0";

    const prevBtn = document.createElement("button");
    prevBtn.innerHTML = "⟨";
    prevBtn.className = "btn btn-sm btn-light";
    prevBtn.style.color = "#007934";
    prevBtn.disabled = true;

    const nextBtn = document.createElement("button");
    nextBtn.innerHTML = "⟩";
    nextBtn.className = "btn btn-sm btn-light";
    nextBtn.style.color = "#007934";
    nextBtn.disabled = totalPages <= 1;

    const pageInfo = document.createElement("span");
    pageInfo.textContent = `1 / ${totalPages}`;
    pageInfo.style.fontWeight = "bold";
    pageInfo.style.color = "#fff";

    paginationDiv.appendChild(prevBtn);
    paginationDiv.appendChild(pageInfo);
    paginationDiv.appendChild(nextBtn);

    // Insertar la paginación al final del card-header
    cardHeader.appendChild(paginationDiv);

    function renderPage(page) {
      rows.forEach((row) => (row.style.display = "none"));
      const start = page * pageSize;
      const end = Math.min(start + pageSize, rows.length);
      for (let i = start; i < end; i++) {
        rows[i].style.display = "";
      }
      pageInfo.textContent = `${page + 1} / ${totalPages}`;
      prevBtn.disabled = page === 0;
      nextBtn.disabled = page >= totalPages - 1;
    }

    prevBtn.addEventListener("click", function () {
      if (currentPage > 0) {
        currentPage--;
        renderPage(currentPage);
      }
    });
    nextBtn.addEventListener("click", function () {
      if (currentPage < totalPages - 1) {
        currentPage++;
        renderPage(currentPage);
      }
    });

    renderPage(currentPage);
  });
});
