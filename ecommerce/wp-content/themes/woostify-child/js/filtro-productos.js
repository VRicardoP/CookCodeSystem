document.addEventListener('DOMContentLoaded', function () {
    const categoriaSelect = document.getElementById('categoria');
    const contenedor = document.getElementById('productos-filtrados');
  
    if (!categoriaSelect || !contenedor) return;
  
    categoriaSelect.addEventListener('change', function () {
      const data = new FormData();
      data.append('action', 'filtrar_productos');
      data.append('categoria', this.value);
  
      fetch(filtro_ajax_params.ajax_url, {
        method: 'POST',
        body: data,
      })
        .then(res => res.text())
        .then(html => {
          contenedor.innerHTML = html;
        });
    });
  });
  