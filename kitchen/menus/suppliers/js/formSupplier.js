document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("formSupplier")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      crearProveedor();
    });
});

function crearProveedor() {
  let nombre = document.getElementById("nombre").value;
  let numero = document.getElementById("numero").value;
  let correo = document.getElementById("correo").value;
  let direccion = document.getElementById("direccion").value;

  let proveedor = {
    nombre: nombre,
    numero: numero,
    correo: correo,
    direccion: direccion,
  };

  Swal.fire({
    title: "Creating Supplier...",
    text: "Please, wait a second...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading(); // Muestra el círculo de carga
    },
  });

  fetch("./../../controllers/crearProveedor.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(proveedor),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      Swal.close();

      if (data.success) {
        Swal.fire({
          icon: "success",
          title: "¡Supplier Created!",
          text: "The supplier has been created successfully.",
          showConfirmButton: false,
          timer: 2500,
        });

        document.getElementById("formSupplier").reset();
      } else {
        Swal.fire({
          icon: "error",
          title: "Error to create supplier",
          text: data.message || "An unexpected error occurred.",
          showConfirmButton: true,
        });
      }
    })
    .catch((error) => {
      Swal.close();

      Swal.fire({
        icon: "error",
        title: "Request Error",
        text: "There was a problem contacting the server. Please try again later.",
        showConfirmButton: true,
      });

      console.error("Error en fetch:", error);
    });
}
