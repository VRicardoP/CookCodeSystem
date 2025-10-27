document.addEventListener("DOMContentLoaded", () => {
  fetch('./../public/api/getHistorialComandas.php')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById("tablaHistorial");
      data.forEach(ticket => {
        console.log("🎯 Ticket recibido:", ticket);  // Para depurar

        const productosStr = ticket.productos
          .map(p => `${p.nombre_producto} (x${p.cantidad})`)
          .join("<br>");

        const total = parseFloat(ticket.total);
        
        const totalFormateado = !isNaN(total) ? total.toFixed(2) : "0.00";

        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${ticket.ticket_id}</td>
          <td>${ticket.mesa_id}</td>
          <td>${totalFormateado} €</td>
          <td>${ticket.fecha_creacion}</td>
          <td>${productosStr}</td>
        `;

        tbody.appendChild(tr);
      });
    })
    .catch(err => {
      console.error("Error cargando historial:", err);
    });
});
