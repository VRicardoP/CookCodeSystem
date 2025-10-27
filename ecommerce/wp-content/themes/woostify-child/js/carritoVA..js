(function() {
    let contenedores = document.querySelectorAll(".animated-meta");
    
    contenedores.forEach(contenedor => {
        if(!contenedor.querySelector("a").textContent.includes("Read more")){
        let id = contenedor.querySelector('a').getAttribute("data-product_id");
        let res = document.createElement("span");
        res.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='25' height='25'><path d='M5 12h14'stroke='#ffffff' stroke-width='4'/></svg>";
        res.classList.add("subtract")
        res.addEventListener('click', restar);
    
        let input =  document.createElement("input");
        input.classList.add("input-button");
        input.setAttribute("type", "number");
        input.setAttribute("min", 0);
        input.setAttribute("value", 0);
        input.setAttribute("step", 1);
        input.setAttribute("data-product", id);
    
        let sum = document.createElement("span");
        sum.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='25' height='25'> <path d='M12 5v14m-7-7h14' stroke='#ffffff' stroke-width='4'/> </svg>";
        sum.classList.add("add")
        sum.addEventListener('click', sumar);
    
        let quantity = document.createElement("div");
        quantity.classList.add("quantity-button");
    
        quantity.appendChild(res);
        quantity.appendChild(input);
        quantity.appendChild(sum);
    
        contenedor.appendChild(quantity)
    }else{
        contenedor.querySelector("a").style.display = "none";
        contenedor.style.width = "59%";
    
        let quantity = document.createElement("div");
        quantity.style.width = "100%";
        quantity.textContent = "Out of stock";
        quantity.classList.add("quantity-button-disabled");
    
        contenedor.appendChild(quantity)
    }
    });
    
    function sumar(event) {
        let eventoInput = new Event('input');
        if(event.target.nodeName == "SPAN"){
            let max = parseInt(event.target.previousSibling.getAttribute("max")) ?? 0;
            let value = parseInt(event.target.previousSibling.getAttribute("value")) + 1;
            if(value<=max){
                event.target.previousSibling.setAttribute("come", event.target.previousSibling.getAttribute("value"));
                event.target.previousSibling.setAttribute("value", value);
                event.target.previousSibling.value = value;
                event.target.previousSibling.dispatchEvent(eventoInput);
            }
        }else if(event.target.nodeName == "svg"){
            let max = parseInt(event.target.parentNode.previousSibling.getAttribute("max")) ?? 0;
            let value = parseInt(event.target.parentNode.previousSibling.getAttribute("value")) + 1;
            if(value<=max){
                event.target.parentNode.previousSibling.setAttribute("come", event.target.parentNode.previousSibling.getAttribute("value"));
                event.target.parentNode.previousSibling.setAttribute("value", value);
                event.target.parentNode.previousSibling.value = value;
                event.target.parentNode.previousSibling.dispatchEvent(eventoInput);
            }
        }else{
            let max = parseInt(event.target.parentNode.parentNode.previousSibling.getAttribute("max")) ?? 0;
            let value = parseInt(event.target.parentNode.parentNode.previousSibling.getAttribute("value")) + 1;
            if(value<=max){
                event.target.parentNode.parentNode.previousSibling.setAttribute("come", event.target.parentNode.parentNode.previousSibling.getAttribute("value"));
                event.target.parentNode.parentNode.previousSibling.setAttribute("value", value);
                event.target.parentNode.parentNode.previousSibling.value = value;
                event.target.parentNode.parentNode.previousSibling.dispatchEvent(eventoInput);
            }
        }
        
    }
    
    function restar(event){
        let eventoInput = new Event('input');
        if(event.target.nodeName == "SPAN"){
            
            let value = parseInt(event.target.nextSibling.getAttribute("value")) - 1;
            if(value>=0){
                event.target.nextSibling.setAttribute("come", event.target.nextSibling.getAttribute("value"));
                event.target.nextSibling.setAttribute("value", value);
                event.target.nextSibling.value = value;
                event.target.nextSibling.dispatchEvent(eventoInput);
            }
        }else if(event.target.nodeName == "svg"){
            let value = parseInt(event.target.parentNode.nextSibling.getAttribute("value")) - 1;
            if(value>=0){
                event.target.parentNode.nextSibling.setAttribute("come", event.target.parentNode.nextSibling.getAttribute("value"));
                event.target.parentNode.nextSibling.setAttribute("value", value);
                event.target.parentNode.nextSibling.value = value;
                event.target.parentNode.nextSibling.dispatchEvent(eventoInput);
            }
        }else{
            let value = parseInt(event.target.parentNode.parentNode.nextSibling.getAttribute("value")) - 1;
            if(value>=0){
                event.target.parentNode.parentNode.nextSibling.setAttribute("come", event.target.parentNode.parentNode.nextSibling.getAttribute("value"));
                event.target.parentNode.parentNode.nextSibling.setAttribute("value", value);
                event.target.parentNode.parentNode.nextSibling.value = value;
                event.target.parentNode.parentNode.nextSibling.dispatchEvent(eventoInput);
            }
        }
    }
    
    
    document.querySelectorAll(".input-button").forEach(element=>{
        element.addEventListener('input', function() {
            let value = parseInt(element.value);
            let max = parseInt(element.getAttribute('max')) || 0;
            let previousValue = parseInt(element.getAttribute('come')) || 0;
    
            if (isNaN(value) || value < 0 || value > max) {
                element.value = previousValue;
            } else {
                element.value = value;
                element.setAttribute('value', value);
            }
    
            console.log('valor:'+element.value+'; max:'+max+'; come:' + element.getAttribute('come'));
            // Si el valor de input es mayor a 0 y venía de estar en 0, se añade al carrito
            if (value > 0 && previousValue === 0) {
                addToCart(element);
            } else if (value === 0 && previousValue > 0) {
                // Si el valor de input es 0 y venía de estar en un número, se elimina del carrito
               // removeFromCart(element);
            } else {
                // Sino, se modifica la cantidad en el carrito
               // modifyCartQuantity(element);
            }
        });
        carritoMax(element);
    })
    
    //Modifica el carrito
    function updateCart(element){
        var productId = element.getAttribute('data-product');
        var quantity = parseInt(element.value);
        
        const data = new URLSearchParams();
        data.append('action', 'update_cart_item');
        data.append('cart_item_key', productId);
        data.append('quantity', quantity);
        
        fetch('wp-admin/admin-ajax.php', {
            method: 'POST',
            body: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
        .then(response => {
            if (response.ok) {
                return response.text(); // Si la respuesta es exitosa, devolver el texto de la respuesta
            }
            throw new Error('Error al actualizar la cantidad del producto en el carrito'); // Si hay algún error en la respuesta, lanzar un error
        })
        .then(message => {
            console.log(message); // Imprimir el mensaje de éxito o error
        })
        .catch(error => {
            console.error(error); // Capturar y manejar cualquier error que ocurra
        });
    }
    
    //Añade al carrito
    function addToCart(element) {
        var productId = element.getAttribute('data-product');
        var quantity = parseInt(element.value);
    
        const data = {
            action: 'add_to_cart',
            product_id: productId,
            quantity: quantity,
        };
        
    
        fetch('wp-admin/admin-ajax.php', {
            method: 'POST',
            body: new URLSearchParams(data),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
        .then(response => {
            if (response.ok) {
                return response.text(); // Si la respuesta es exitosa, devolver el texto de la respuesta
            }
            throw new Error('Error al añadir el producto al carrito'); // Si hay algún error en la respuesta, lanzar un error
        })
        .then(message => {
            console.log(message); // Imprimir el mensaje de éxito o error
        })
        .catch(error => {
            console.error(error); // Capturar y manejar cualquier error que ocurra
        });
    }
    
    //Comprueba cual es el maximo a añadir al carrito
    function carritoMax(input){
        let id = input.getAttribute('data-product').toString();
        input.setAttribute('come', 0);
    //Funcion para coger productos por id si cantidad es true solo devuelve la cantidad
    function getProducto(filtro, cantidad = false) {
        const url = 'http://localhost:8080/ecommerce/wp-recursos/VerProducto.php';
    
        const formData = new FormData();
        formData.append('filtro', filtro);
        formData.append('cantidad', cantidad);
    
        return fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la solicitud');
            }
            return response.json();
        })
        .then(data => {
                return data;
        })
        .catch(error => {
            console.error('Error:', error);
            return null;
        });
    }
    
    getProducto(id, true).then(producto => {
    
       input.setAttribute('max', producto.data);
    });
    
    }
    
    })();