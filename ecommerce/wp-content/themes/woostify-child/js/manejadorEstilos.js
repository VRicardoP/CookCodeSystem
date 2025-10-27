
function cargarCSS($name) {
    const url = window.location.href; 
    if (url.endsWith('/'+$name+'/')) {
        const head = document.getElementsByTagName('head')[0];
        const link = document.createElement('link');

        console.log("Aplicando " + $name + ".css")

        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '/ecommerce/wp-content/themes/woostify-child/css/'+ $name + '.css'; 

        head.appendChild(link);
    }
}

function incluyeCSS($name){
    const url = window.location.href; 
    if (url.includes('/'+$name+'/')) {
        const head = document.getElementsByTagName('head')[0];
        const link = document.createElement('link');

        //console.log("Aplicando " + $name + ".css")

        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '/ecommerce/wp-content/themes/woostify-child/css/'+ $name + '.css'; 
        console.log('Incluyendo ' + $name+'.css')
        head.appendChild(link);
        const shortDescription = document.querySelector('.woocommerce-product-details__short-description');

    if (shortDescription) {
        //Aplica boton de compra
        let res = document.createElement("span");
        res.innerHTML ="<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='25' height='25'><path d='M5 12h14'stroke='#ffffff' stroke-width='4'/></svg>";
        res.classList.add("subtract");
        
        let input = document.createElement("input");
        input.classList.add("input-button");
        input.setAttribute("type", "number");
        input.setAttribute("min", 0);
        input.setAttribute("value", 0);
        input.setAttribute("step", 1);
        
        let sum = document.createElement("span");
        sum.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='25' height='25'> <path d='M12 5v14m-7-7h14' stroke='#ffffff' stroke-width='4'/> </svg>";
        sum.classList.add("add");
        
        let quantity = document.createElement("div");
        quantity.classList.add("quantity-button");
        
        quantity.appendChild(res);
        quantity.appendChild(input);
        quantity.appendChild(sum);
        
        // Insertar el nuevo div después del elemento con la clase "woocommerce-product-details__short-description"
        shortDescription.parentNode.insertBefore(quantity, shortDescription.nextSibling);
        

    //Aplica desplegable ingredientes
     

// Crear un elemento link
var linkElement = document.createElement('link');
linkElement.rel = 'stylesheet';
linkElement.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';

// Obtener el encabezado del documento
var headElement = document.head || document.getElementsByTagName('head')[0];

// Agregar el elemento link al encabezado del documento
headElement.appendChild(linkElement);

// Función para crear un acordeón
// Obtener el encabezado del documento
var headElement = document.head || document.getElementsByTagName('head')[0];

// Agregar el elemento link al encabezado del documento
headElement.appendChild(linkElement);
  function crearAcordeon(numSecciones) {

    var contentToMove = document.querySelector('.woocommerce-product-details__short-description p');

    // Crear contenedor del acordeón
    var accordionContainer = document.createElement('div');
    accordionContainer.classList.add('accordion');
    accordionContainer.style.maxWidth = '60%';
    //accordionContainer.style.border = '1px solid #ccc';
    accordionContainer.style.backgroundColor = 'rgba(255, 255, 255, 0.5';
    accordionContainer.style.borderRadius = '20px';
    accordionContainer.style.marginBottom = '10px';

    // Crear las secciones del acordeón
      // Crear encabezado de la sección
      var header = document.createElement('div');
      header.classList.add('accordion-header');
      
      header.textContent = 'Description';
      header.style.backgroundColor = '#007934';
      header.style.color= 'white';
      header.style.padding = '10px';
      header.style.cursor = 'pointer';
      header.style.display = "flex";
      header.style.justifyContent = "space-between";
      header.style.alignItems = "center";
      header.style.borderRadius = "15px";
      header.style.paddingLeft = "30px";


    //Añade icono desplegable
    var icon = document.createElement('i');
    icon.classList.add('icon', 'fas', 'fa-chevron-down');
    header.appendChild(icon);
    
      // Crear contenido de la sección
      var content = document.createElement('div');
      content.classList.add('accordion-content');
      content.textContent =  contentToMove.textContent;
      content.style.borderRadius = '20px';
      content.style.background = "white";
      content.style.display = 'none';
      content.style.padding = '10px';

      // Agregar evento de clic al encabezado para mostrar/ocultar el contenido
      header.addEventListener('click', function() {
        var nextElement = this.nextElementSibling;
        nextElement.style.display = nextElement.style.display === 'block' ? 'none' : 'block';
      });

      // Agregar encabezado y contenido al contenedor del acordeón
      accordionContainer.appendChild(header);
      accordionContainer.appendChild(content);

      contentToMove.remove();
    // Agregar el acordeón al cuerpo del documento
    var priceElement = document.querySelector('.price');
    priceElement.parentNode.insertBefore(accordionContainer, priceElement.nextSibling);
 
  }

  // Llamar a la función para crear un acordeón con 3 secciones
  crearAcordeon(3);
}
    }
}

cargarCSS('carrito');

cargarCSS('finalizar-compra')

incluyeCSS('producto')