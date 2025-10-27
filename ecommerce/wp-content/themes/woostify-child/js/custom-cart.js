(function () {
    let contenedores = document.querySelectorAll(".animated-meta");
    let cart = document.querySelector(".shopping-cart");

    // Esconde el botón "Ver carrito" cuando se activa el carrito
    cart.addEventListener('click', function () {
        setTimeout(esconde, 100);
    });

    function esconde() {
        let button = document.querySelectorAll(".button.wc-forward")[1];
        if (button) {
            button.classList.add("esconder");
        }
    }

    contenedores.forEach((contenedor) => {
        if (!contenedor.querySelector("a").textContent.includes("Read more")) {
            let id = contenedor.querySelector("a").getAttribute("data-product_id");
            if (!id) {
                console.error("No se encontró data-product_id en el contenedor:", contenedor);
                return;
            }

            // Crear los botones de incrementar y decrementar
            let res = document.createElement("span");
            res.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='25' height='25'><path d='M5 12h14' stroke='#ffffff' stroke-width='4'/></svg>";
            res.classList.add("subtract");
            res.addEventListener("click", restar);

            let input = document.createElement("input");
            input.classList.add("input-button");
            input.setAttribute("type", "number");
            input.setAttribute("min", "0");
            input.setAttribute("value", "0.00");
            input.setAttribute("step", "0.01");
            input.setAttribute("data-product", id);

            let sum = document.createElement("span");
            sum.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='25' height='25'><path d='M12 5v14m-7-7h14' stroke='#ffffff' stroke-width='4'/></svg>";
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
            let quantity = document.createElement("div");
            quantity.style.width = "100%";
            quantity.textContent = "Out of stock";
            quantity.classList.add("quantity-button-disabled");

            contenedor.appendChild(quantity);
        }
    });

    // Incrementar cantidad
    function sumar(event) {
        event.preventDefault();
        let input = getInputElement(event);

        if (input) {
            let value = parseFloat(input.value) + 0.01;  // Incrementa en 0.01 (decimal)
            input.value = value.toFixed(2);  // Ajusta a dos decimales
            input.dispatchEvent(new Event("input"));
        }
    }

    // Decrementar cantidad
    function restar(event) {
        event.preventDefault();
        let input = getInputElement(event);

        if (input) {
            let value = parseFloat(input.value) - 0.01;  // Decrementa en 0.01 (decimal)
            if (value >= 0) {
                input.value = value.toFixed(2);  // Ajusta a dos decimales
                input.dispatchEvent(new Event("input"));
            }
        }
    }

    // Función auxiliar para obtener el input relacionado con los botones de incrementar/decrementar
    function getInputElement(event) {
        if (event.target.nodeName === "SPAN") {
            return event.target.nextElementSibling;
        } else if (event.target.nodeName === "svg") {
            return event.target.parentNode.nextElementSibling;
        } else {
            return event.target.closest(".quantity-button").querySelector(".input-button");
        }
    }

    // Listener para actualizar o añadir la cantidad en el carrito cuando se cambia el valor del input
    document.querySelectorAll(".input-button").forEach((element) => {
        element.addEventListener("input", function () {
            let value = parseFloat(element.value) || 0;
            let id = element.getAttribute("data-product");

            if (value > 0) {
                // Llamada AJAX para actualizar o añadir al carrito
                updateOrAddToCart(id, value);
            } else {
                // Elimina el producto si la cantidad es 0
                document.querySelector(`a.remove[data-product_id="${id}"]`).click();
            }
        });
    });

    // Función para actualizar o añadir un producto al carrito
    function updateOrAddToCart(product_id, quantity) {
        jQuery.ajax({
            type: 'POST',
            url: '/ecommerce/wp-admin/admin-ajax.php',
            data: {
                action: 'update_or_add_to_cart',
                product_id: product_id,
                quantity: quantity
            },
            success: function (response) {
                if (response.success) {
                    console.log('Producto añadido o cantidad actualizada');
                    // Refresca el carrito para mostrar la actualización
                    jQuery('.cart-content').load(window.location.href + ' .cart-content > *');

                } else {
                    console.error('Error:', response.data);
                }
            },
            error: function (error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    }

})();
