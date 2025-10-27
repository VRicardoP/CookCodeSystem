import { createWindow } from "./createWindow.js";
import { BASE_URL } from "./../../../config.js";



class DishList extends HTMLElement {
  constructor() {
    super();
  }

  async connectedCallback() {
    if (!(await this.render())) {
      return false;
    }
  }

  async fetchDishes() {
    try {
      const response = await fetch("./../../controllers/getDishes.php");
      if (!response.ok) {
        throw new Error("Error fetching dishes data");
      }
      return response.json();
    } catch (e) {
      console.error("Error fetching dishes data", e);
      return [];
    }
  }

  async render() {
    const dishesData = await this.fetchDishes();

    if (!dishesData || dishesData.length === 0) {
      this.innerHTML = "<p>No hay platos disponibles</p>";
      return;
    }

    // Create the container for all dishes
    this.classList.add("dishes-list-container");
    dishesData.forEach((dish) => {
      const dishContainer = this.createDishElement(dish);
      this.appendChild(dishContainer);
    });
  }

  createDishElement(dishData) {
    // Create a container for each dish as a card
    const dishContainer = document.createElement("div");
    dishContainer.className = "dish-container"; // Class for the cards

    let image = document.createElement("img");
    image.src = dishData.imagen; // Ensure 'imagen' exists in the database
    image.alt = dishData.nombre;

    let infoContainer = document.createElement("div");
    infoContainer.className = "dish-container-info";

    let title = document.createElement("h3");
    title.textContent = dishData.nombre; // Field 'nombre' from the database

    let hr = document.createElement("hr");

    let paragraph = document.createElement("p");
    paragraph.textContent = dishData.instrucciones; // Display 'instrucciones'

    let btnRestaurant = document.createElement("button");
    btnRestaurant.className = "btn-primary rounded";
    btnRestaurant.textContent = "Assign to Restaurant"; // Display 'instrucciones'
    btnRestaurant.setAttribute("data-dish-id", dishData.id);

    btnRestaurant.addEventListener("click", function (event) {
      // Evitar que el click en el botón propague el evento al contenedor del plato
      event.stopPropagation();

      // Obtener el ID del plato desde el atributo data-plato-id
      let idPlato = this.getAttribute("data-dish-id");

      // Ejecutar la función con el ID del plato
      mostrarRestaurantes(idPlato, dishData.nombre); // Pasamos el idPlato a la función

      // Aquí puedes añadir cualquier otra lógica adicional
      console.log("Plato ID:", idPlato);
    });

    infoContainer.appendChild(title);
    //   infoContainer.appendChild(hr);
    //   infoContainer.appendChild(paragraph);
    infoContainer.appendChild(btnRestaurant);

    dishContainer.appendChild(image);
    dishContainer.appendChild(infoContainer);

    // Add click event to show dish details
    dishContainer.addEventListener("click", () => {
      this.showDishDetails(dishData);
    });

    return dishContainer;
  }

  showDishDetails(dishData) {
    // Create a pop-up window with dish details
    let popUpContainer = createWindow(dishData.nombre, true);
    let uiWindow = popUpContainer.getElementsByClassName("uiWindow")[0];

    uiWindow.classList.add("dish-window");

    let main = document.createElement("div");
    main.classList.add("dish-window-main");

    // Aquí, incluir los preelaborados e ingredientes directamente
    let ingredientsAndPreelaboradosDiv = document.createElement("div");

    // Sección de Preelaborados
    if (dishData.pre_elaborados && dishData.pre_elaborados.length > 0) {
      let preelaboradosSection = document.createElement("section");
      let preelaboradosTitle = document.createElement("h4");
      preelaboradosTitle.textContent = "Preelaborados";
      preelaboradosSection.appendChild(preelaboradosTitle);

      dishData.pre_elaborados.forEach((preelaborado) => {
        let preelaboradoItem = document.createElement("p");
        preelaboradoItem.textContent = `${preelaborado.nombre} - ${preelaborado.cantidad}`;
        preelaboradosSection.appendChild(preelaboradoItem);
      });

      ingredientsAndPreelaboradosDiv.appendChild(preelaboradosSection);
    }

    // Sección de Ingredientes
    if (dishData.ingredients && dishData.ingredients.length > 0) {
      let ingredientesSection = document.createElement("section");
      let ingredientesTitle = document.createElement("h4");
      ingredientesTitle.textContent = "Ingredientes";
      ingredientesSection.appendChild(ingredientesTitle);

      dishData.ingredients.forEach((ingrediente) => {
        let ingredienteItem = document.createElement("p");
        ingredienteItem.textContent = `${ingrediente.nombre} - ${ingrediente.cantidad} ${ingrediente.unidad}`;
        ingredientesSection.appendChild(ingredienteItem);
      });

      ingredientsAndPreelaboradosDiv.appendChild(ingredientesSection);
    }

    // Agregar sección de ingredientes y preelaborados al contenedor principal
    main.appendChild(ingredientsAndPreelaboradosDiv);

    // Instrucciones del plato
    let section1 = document.createElement("section");
    let instrucciones = document.createElement("p");
    instrucciones.innerHTML = dishData.instrucciones.replace(/\n/g, "<br>"); // Dish instructions
    section1.appendChild(instrucciones);
    main.appendChild(section1);

    // Aside con la imagen y el nombre del plato
    let aside = document.createElement("aside");

    let asideImg = document.createElement("img");
    asideImg.src = dishData.imagen; // Use the 'imagen' field
    aside.appendChild(asideImg);

    let asideDesc = document.createElement("p");
    asideDesc.innerText = dishData.nombre; // Use the 'nombre' field for description
    aside.appendChild(asideDesc);

    // Agregar aside a main
    main.appendChild(aside);

    // Agregar botón para imprimir
    let btnDiv = document.createElement("div");
    btnDiv.classList.add("div-btn");
    aside.appendChild(btnDiv);

    let printBtn = document.createElement("button");
    let printBtnIcon = document.createElement("img");
    printBtnIcon.src = "./../../svg/print.svg"; // Path to the print icon
    printBtn.appendChild(printBtnIcon);
    btnDiv.appendChild(printBtn);
    printBtn.addEventListener("click", () => {
      window.print();
    });

    // Agrega el contenedor principal a la ventana emergente
    uiWindow.appendChild(main);
    document.body.appendChild(popUpContainer);
  }
}
async function mostrarRestaurantes(platoId, plato) {
  // Crear el contenedor del modal
  let modal = document.createElement("div");
  modal.classList.add("modal-overlay"); // Clase CSS para el modal
  modal.innerHTML =
    `
        <div class="modal-content">
            <h3>Assign ` +
    plato +
    ` to restaurants</h3>
           
            <div id="restaurant-list">Cargando restaurantes...</div>  <!-- Aquí se listarán los restaurantes con checkboxes -->
            <button id="btnGuardar" class="btn-primary rounded">Save Assignment</button>
            <button id="btnCerrarModal" class="btn-danger rounded">Close</button>
        </div>
    `;
  document.body.appendChild(modal);

  // Obtener la lista de todos los platos y restaurantes
  let platosRestaurantes = await getPlatosRestaurantes(); // Llama a la función para obtener los platos-restaurantes
  let restaurantes = await getRestaurantes(); // Llama a la función para obtener los restaurantes disponibles

  // Filtrar los datos de platosRestaurantes para obtener solo los que coincidan con el platoId
  const restaurantesAsignados = platosRestaurantes.filter(
    (pr) => pr.id_plato == platoId
  );

  console.log(restaurantes); // Muestra los restaurantes asignados al plato en la consola

  // Verificar si se obtuvieron restaurantes correctamente
  if (restaurantes && restaurantes.length > 0) {
    const restaurantList = document.getElementById("restaurant-list");
    restaurantList.innerHTML = ""; // Limpiar el mensaje de "Cargando"

    // Crear checkboxes para cada restaurante
    restaurantes.forEach((restaurant) => {
      let checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.id = `restaurant-${restaurant.restaurante_id}`;
      checkbox.value = restaurant.restaurante_id;

      // Verificar si este restaurante está asignado con activo = 1
      const asignacion = restaurantesAsignados.find(
        (r) => r.id_restaurante == restaurant.restaurante_id
      );

      // Si el restaurante está asignado y activo, marcar el checkbox
      if (asignacion && asignacion.activo == "1") {
        checkbox.checked = true;
      } else {
        checkbox.checked = false;
      }

      let label = document.createElement("label");
      label.setAttribute("for", `restaurant-${restaurant.restaurante_id}`);
      label.textContent = restaurant.Dirección; // Muestra la dirección del restaurante

      let div = document.createElement("div");
      div.appendChild(checkbox);
      div.appendChild(label);

      restaurantList.appendChild(div);
    });
  } else {
    // Si no hay restaurantes, muestra un mensaje
    document.getElementById("restaurant-list").innerHTML =
      "<p>No se encontraron restaurantes.</p>";
  }

  // Botón para cerrar el modal
  document
    .getElementById("btnCerrarModal")
    .addEventListener("click", function () {
      document.body.removeChild(modal);
    });

  // Botón para guardar la asignación
  document.getElementById("btnGuardar").addEventListener("click", function () {
    let selectedRestaurants = [];
    restaurantes.forEach((restaurant) => {
      let checkbox = document.getElementById(
        `restaurant-${restaurant.restaurante_id}`
      );
      if (checkbox.checked) {
        selectedRestaurants.push(restaurant.restaurante_id);
      }
    });

    // Aquí haces la llamada para asignar el plato a los restaurantes seleccionados
    asignarPlatoARestaurantes(platoId, selectedRestaurants);

    // Cerrar el modal después de guardar
    document.body.removeChild(modal);
  });
}

function getRestaurantes() {
  // Devuelve la promesa para poder usarla con async/await
  return fetch(
    `${BASE_URL}/restaurant/public/api/getRestaurants.php`,
    {
      method: "GET", // Usar GET para obtener datos
      headers: {
        "Content-Type": "application/json",
      },
    }
  )
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al obtener los restaurantes.");
      }
      return response.json(); // Parsear la respuesta como JSON
    })
    .then((data) => {
      // Aquí se devuelve directamente el array de restaurantes devuelto por la API
      console.log(data); // Muestra la lista de restaurantes en la consola
      return data; // Devuelve la lista de restaurantes para que la función pueda ser usada en await
    })
    .catch((error) => {
      console.error("Error al obtener los restaurantes:", error);
      return []; // Retornar un array vacío en caso de error para evitar fallos en la UI
    });
}

function getPlatosRestaurantes() {
  // Devuelve la promesa para poder usarla con async/await
  return fetch(
    `${BASE_URL}/restaurant/public/api/getDishRestaurant.php`,
    {
      method: "GET", // Usar GET para obtener datos
      headers: {
        "Content-Type": "application/json",
      },
    }
  )
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al obtener los restaurantes.");
      }
      return response.json(); // Parsear la respuesta como JSON
    })
    .then((data) => {
      // Aquí se devuelve directamente el array de restaurantes devuelto por la API
      console.log(data); // Muestra la lista de restaurantes en la consola
      return data; // Devuelve la lista de restaurantes para que la función pueda ser usada en await
    })
    .catch((error) => {
      console.error("Error al obtener los restaurantes:", error);
      return []; // Retornar un array vacío en caso de error para evitar fallos en la UI
    });
}

function asignarPlatoARestaurantes(platoId, restaurantesIds) {
  // Aquí harás la lógica para enviar los datos a tu API o backend
  // por ejemplo, podrías hacer un POST a tu servidor con la siguiente estructura:

  const data = {
    platoId: platoId,
    restaurantes: restaurantesIds,
  };
  console.log(data);
  fetch(`${BASE_URL}/restaurant/public/api/addDishRestaurant.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Plato asignado exitosamente a los restaurantes.");
      } else {
        alert("Error al asignar el plato.");
      }
    })
    .catch((error) => {
      console.error("Error al asignar el plato:", error);
    });
}

customElements.define("dish-list", DishList);
