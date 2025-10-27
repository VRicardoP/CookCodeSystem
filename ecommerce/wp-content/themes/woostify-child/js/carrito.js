(function () {
  let contenedores = document.querySelectorAll(".animated-meta");


  let cart=document.querySelectorAll(".shopping-cart")[0];

  cart.addEventListener('click', function(){
    setTimeout(esconde, 100);
  })

  function esconde(){
    let button = document.querySelectorAll(".button.wc-forward")[1];
    console.log(button);
          if (button) {  
            button.classList.add("esconder")
          }
  }
  contenedores.forEach((contenedor) => {
    if (!contenedor.querySelector("a").textContent.includes("Read more")) {
      let id = contenedor.querySelector("a").getAttribute("data-product_id");
      let res = document.createElement("span");
      res.innerHTML =
        "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='25' height='25'><path d='M5 12h14'stroke='#ffffff' stroke-width='4'/></svg>";
      res.classList.add("subtract");
      res.addEventListener("click", restar);

      let input = document.createElement("input");
      input.classList.add("input-button");
      input.setAttribute("type", "number");
      input.setAttribute("min", 0);
      input.setAttribute("value", 0);
      input.setAttribute("step", 1);
      input.setAttribute("data-product", id);

      let sum = document.createElement("span");
      sum.innerHTML =
        "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='25' height='25'> <path d='M12 5v14m-7-7h14' stroke='#ffffff' stroke-width='4'/> </svg>";
      sum.classList.add("add");
      sum.addEventListener("click", sumar);

      let quantity = document.createElement("div");
      quantity.classList.add("quantity-button");

      quantity.appendChild(res);
      quantity.appendChild(input);
      quantity.appendChild(sum);

      contenedor.appendChild(quantity);
    } else {
      contenedor.querySelector("a").style.display = "none";
      //contenedor.style.width = "59%";

      let quantity = document.createElement("div");
      quantity.style.width = "100%";
      quantity.textContent = "Out of stock";
      quantity.classList.add("quantity-button-disabled");

      contenedor.appendChild(quantity);
    }
  });

  function sumar(event) {
    let eventoInput = new Event("input");
    if (event.target.nodeName == "SPAN") {
      let max = parseInt(event.target.previousSibling.getAttribute("max")) ?? 0;
      let value =
        parseInt(event.target.previousSibling.getAttribute("value")) + 1;
      if (value <= max) {
        event.target.previousSibling.setAttribute(
          "come",
          event.target.previousSibling.getAttribute("value")
        );
        event.target.previousSibling.setAttribute("value", value);
        event.target.previousSibling.value = value;
        event.target.previousSibling.dispatchEvent(eventoInput);
      }
    } else if (event.target.nodeName == "svg") {
      let max =
        parseInt(event.target.parentNode.previousSibling.getAttribute("max")) ??
        0;
      let value =
        parseInt(
          event.target.parentNode.previousSibling.getAttribute("value")
        ) + 1;
      if (value <= max) {
        event.target.parentNode.previousSibling.setAttribute(
          "come",
          event.target.parentNode.previousSibling.getAttribute("value")
        );
        event.target.parentNode.previousSibling.setAttribute("value", value);
        event.target.parentNode.previousSibling.value = value;
        event.target.parentNode.previousSibling.dispatchEvent(eventoInput);
      }
    } else {
      let max =
        parseInt(
          event.target.parentNode.parentNode.previousSibling.getAttribute("max")
        ) ?? 0;
      let value =
        parseInt(
          event.target.parentNode.parentNode.previousSibling.getAttribute(
            "value"
          )
        ) + 1;
      if (value <= max) {
        event.target.parentNode.parentNode.previousSibling.setAttribute(
          "come",
          event.target.parentNode.parentNode.previousSibling.getAttribute(
            "value"
          )
        );
        event.target.parentNode.parentNode.previousSibling.setAttribute(
          "value",
          value
        );
        event.target.parentNode.parentNode.previousSibling.value = value;
        event.target.parentNode.parentNode.previousSibling.dispatchEvent(
          eventoInput
        );
      }
    }
  }

  function restar(event) {
    let eventoInput = new Event("input");
    if (event.target.nodeName == "SPAN") {
      let value = parseInt(event.target.nextSibling.getAttribute("value")) - 1;
      if (value >= 0) {
        event.target.nextSibling.setAttribute(
          "come",
          event.target.nextSibling.getAttribute("value")
        );
        event.target.nextSibling.setAttribute("value", value);
        event.target.nextSibling.value = value;
        event.target.nextSibling.dispatchEvent(eventoInput);
      }
    } else if (event.target.nodeName == "svg") {
      let value =
        parseInt(event.target.parentNode.nextSibling.getAttribute("value")) - 1;
      if (value >= 0) {
        event.target.parentNode.nextSibling.setAttribute(
          "come",
          event.target.parentNode.nextSibling.getAttribute("value")
        );
        event.target.parentNode.nextSibling.setAttribute("value", value);
        event.target.parentNode.nextSibling.value = value;
        event.target.parentNode.nextSibling.dispatchEvent(eventoInput);
      }
    } else {
      let value =
        parseInt(
          event.target.parentNode.parentNode.nextSibling.getAttribute("value")
        ) - 1;
      if (value >= 0) {
        event.target.parentNode.parentNode.nextSibling.setAttribute(
          "come",
          event.target.parentNode.parentNode.nextSibling.getAttribute("value")
        );
        event.target.parentNode.parentNode.nextSibling.setAttribute(
          "value",
          value
        );
        event.target.parentNode.parentNode.nextSibling.value = value;
        event.target.parentNode.parentNode.nextSibling.dispatchEvent(
          eventoInput
        );
      }
    }
  }

  document.querySelectorAll(".input-button").forEach((element) => {
    element.addEventListener("input", function () {
      let value = parseInt(element.value);
      let max = parseInt(element.getAttribute("max")) || 0;
      let previousValue = parseInt(element.getAttribute("come")) || 0;
      let id = element.getAttribute("data-product");
        if(isNaN(value)){
            element.value = 0;
            value = 0;
        }
        if(value > max){
            element.value = max;
            value = max;
        }
        

      if ( value < 0) {
        element.value = previousValue;
      } else {
        element.value = value;
        element.setAttribute("value", value);
      }

      // Si el valor de input es mayor a 0 y venía de estar en 0, se añade al carrito
      if (value > 0 && previousValue === 0) {
        element.parentNode.previousSibling.setAttribute("data-quantity", value);
        
        element.parentNode.previousSibling.click();
        setTimeout(esconde, 800);
      } else if (value === 0 && previousValue > 0) {
        // Si el valor de input es 0 y venía de estar en un número, se elimina del carrito
        document
          .querySelector('a.remove[data-product_id="' + id + '"]')
          .click();
      } else {
        // Sino, se modifica la cantidad en el carrito
        let inpu = document.querySelector(
          'a.remove[data-product_id="' + id + '"]'
        ).nextElementSibling.nextElementSibling.firstChild.nextElementSibling
          .firstChild.nextElementSibling.nextElementSibling;
        inpu.setAttribute("value", value + 1);
        inpu.previousElementSibling.click();
      }
    });
    carritoMax(element);
  });

  //Comprueba cual es el maximo a añadir al carrito
  function carritoMax(input) {
    try {
      let id = input.getAttribute("data-product").toString();
      input.setAttribute("come", 0);
      
      // Función para obtener el producto por ID; si cantidad es true, solo devuelve la cantidad
      function getProducto(filtro, cantidad = false) {
        const url ="http://localhost:8080/ecommerce/wp-recursos/VerProducto.php";
        
        
          const formData = new FormData();
          formData.append("filtro", filtro);
          formData.append("cantidad", cantidad);
          return fetch(url, {
            method: "POST",
            body: formData,
          }).then((response) => {
            if (!response.ok) {
              throw new Error("Error en la solicitud");
            }
            return response.json();
          });
        
      }

      getProducto(id, true)
        .then((producto) => {
          input.setAttribute("max", producto.data);
        })
        .catch((error) => {
          console.error("Error1:", error);
          // Manejar el error de alguna manera, si es necesario
        });
    } catch (error) {
      console.error("Error2:", error);
      // Manejar el error de alguna manera, si es necesario
    }
    carrito();
  }

  //cambia los input que estan en el carrito por sus numeros
  function carrito() {
    document.querySelectorAll("li.mini_cart_item").forEach((element) => {
      let value = element.querySelector("input").getAttribute("value");
      let id = element.querySelector("a").getAttribute("data-product_id");

      let input = document.querySelector('input[data-product="' + id + '"]');
      if (input != null) {
        input.setAttribute("value", value);
        input.setAttribute("come", value);
      }
    });
  }
})();
