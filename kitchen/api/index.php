<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>API</title>
    <style>
      body{
        text-align: center;
      }

      button {
        background-color: #4CAF50;
        border-radius: 25px;
        color: white;
        border: none;
        padding: 8px 30px;
        font-size: 16px;
        cursor: pointer;
      }

      button:hover {
        background-color: #45a049;
        padding: 12px 35px;
      }
    </style>
  </head>
  <body>
    <h1>API</h1>

    <div style="display: flex; flex-direction: column; gap: 10px; width: 350px; margin: 0 auto;">
      <button onclick="window.location.href='./pedidos/index.php'">
        GET Pedidos
      </button>
      <button onclick="window.location.href='./productos/index.php'">
        GET Productos
      </button>
      <button onclick="window.location.href='./preelaborados/index.php'">
        GET Preelaborados
      </button>
      <button onclick="window.location.href='./recetas/index.php'">
        GET Recetas
      </button>
      <button onclick="window.location.href='./proveedores/index.php'">
        GET Proveedores
      </button>
      <button onclick="window.location.href='./receta_ingrediente/index.php'">
        GET Receta Ingredientes
      </button>
      <button onclick="window.location.href='./receta_ingrediente/index.php'">
        GET Receta Preelaborados
      </button>
      <button onclick="window.location.href='./ing_etiquetas/index.php'">
        GET Etiquetas Ingredientes
      </button>
      <button onclick="window.location.href='./pre_etiquetas/index.php'">
        GET Etiquetas Preelaborados
      </button>
      <button onclick="window.location.href='./elab_etiquetas/index.php'">
        GET Etiquetas Elaborados (Recetas)
      </button>
      <button onclick="window.location.href='./ing_stock/index.php'">
        GET Stock Ingredientes
      </button>
      <button onclick="window.location.href='./elab_stock/index.php'">
        GET Stock Elaborados (Elab/Pre)
      </button>
    </div>

    <!-- <button onclick="getUser()">Get User</button>
    <button onclick="getAllUsers()">Get All Users</button> -->

    <script>
      function getUser() {
        fetch("user.php?id=16")
          .then((response) => {
            if (!response.ok) {
              throw new Error("Network response was not ok");
            }
            return response.json();
          })
          .then((data) => {
            console.log("User:", data);
            alert("User data:\n" + JSON.stringify(data, null, 2));
          })
          .catch((error) => {
            console.error(
              "There was a problem with the fetch operation:",
              error
            );
            alert("Error: " + error.message);
          });
      }

      function getAllUsers() {
        fetch("user.php?all")
          .then((response) => {
            if (!response.ok) {
              throw new Error("Network response was not ok");
            }
            return response.json();
          })
          .then((data) => {
            console.log("All Users:", data);
            alert("All users data:\n" + JSON.stringify(data, null, 2));
          })
          .catch((error) => {
            console.error(
              "There was a problem with the fetch operation:",
              error
            );
            alert("Error: " + error.message);
          });
      }
    </script>
  </body>
</html>