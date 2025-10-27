
// First Idea with HardCoding, is working but i am trying to refactor it in another branch
const kilo = 1000
var rendimientoVerduras = [
    { nombre: "Seleccionar", merma: "" },
    { nombre: "CALABACÍN", merma: 11 },
    { nombre: "CEBOLLA", merma: 17 },
    { nombre: "AJO", merma: 17 },
    { nombre: "APIO", merma: 15 },
    { nombre: "PATATA", merma: 15 },
    { nombre: "LIMONES", merma: 36 },
    { nombre: "TOMATE CANARIO", merma: 10 },
    { nombre: "NABO", merma: 10 },
    { nombre: "PUERRO", merma: 25 },
    { nombre: "ZANAHORIA", merma: 30 }
];



function calc(num) {
    var pricePerKilos = parseFloat(document.getElementById(num + 1).value);
    var netweight = parseFloat(document.getElementById(num + 2).value);
    var producto = document.getElementById(num).value;
    var shrinking = document.getElementById(num + 3);

    var result = rendimientoVerduras.find(({ nombre }) => nombre === producto) || { merma: 0 };
    document.getElementById(num + 4).value = result.merma + "%";
    var mermaDecimal = result.merma / 100;
    var pesoBruto = netweight / (1 - mermaDecimal);
    pesoBruto = Math.round(pesoBruto);
    shrinking.value = pesoBruto;

    var precioUnit = (pesoBruto / 1000) * pricePerKilos;
    var precioUnitRedondeado = parseFloat(precioUnit.toFixed(2));
    document.getElementById(num + 5).value = precioUnitRedondeado;
}




function result() {
    var total = 0;
    var numCalculations = 15; // Número total de cálculos
    var idNameProduct = 0;
    for (var i = 2; i <= numCalculations; i++) {
        idNameProduct = i * 6 + 1;
        if (document.getElementById(idNameProduct).value != ("Seleccionar" || null)) {
            calc(idNameProduct);
        }

        total += parseFloat(document.getElementById(idNameProduct + 5).value) || 0;
    }

    document.getElementById('result').innerText = total.toFixed(2);
}



function clearValues() {
    document.querySelector("form").value = '';
    document.getElementById('result').innerHTML = '';
}




// Llena las opciones del menú desplegable de ingredientes
function llenarMenuDesplegableIngredientes() {

    var menuDesplegable = document.getElementById('7');

    rendimientoVerduras.forEach(function (ingrediente) {
        var opcion = document.createElement('option');
        console.log(ingrediente.nombre);
        opcion.text = ingrediente.nombre;
        opcion.value = ingrediente.nombre; 
        menuDesplegable.appendChild(opcion);
    });


    for (var i = 13; i <= 91; i += 6) {
        var menuDesplegable = document.getElementById(i.toString());
        if (menuDesplegable) {
            rendimientoVerduras.forEach(function (ingrediente) {
                var opcion = document.createElement('option');
                opcion.text = ingrediente.nombre;
                opcion.value = ingrediente.nombre;
                menuDesplegable.appendChild(opcion);
            });
        }
    }

}





function limpiarCalculos(numId) {

    numId = parseInt(numId);
    document.getElementById(numId + 1).value = "";
    document.getElementById(numId + 2).value = "";
    document.getElementById(numId + 3).value = "";
    document.getElementById(numId + 4).value = "";
    document.getElementById(numId + 5).value = "";


}



function showSelectOptions() {
    // Mostrar las opciones del select
    var select = document.getElementById('13');
    select.size = select.options.length;
}

function hideSelectOptions() {
    // Ocultar las opciones del select
    var select = document.getElementById('13');
    select.size = 1;
}




function toggleSelectOptions(event, numId) {
    var selectContainer = document.getElementById('selectContainer'+numId);
    var isVisible = selectContainer.style.display === 'block';
    
    if (!isVisible) {
        // Mostrar el select y ajustar su tamaño
        selectContainer.style.display = 'block';
        var select = document.getElementById(numId);
        select.size = select.options.length;
        
        // Agregar evento para cerrar el select cuando se hace clic fuera de él
        document.addEventListener('click', function() {
            closeSelectOptions(numId);
        });
    // Detener la propagación del evento para evitar que se cierre inmediatamente
    event.stopPropagation();
}
}




function closeSelectOptions(numId) {
    console.log('selectContainer'+numId);
    var selectContainer = document.getElementById('selectContainer'+numId);
   
    // Ocultar el select y restablecer su tamaño
    selectContainer.style.display = 'none';
    var select = document.getElementById(numId);
    select.size = 1;
    
    // Eliminar el evento para cerrar el select
    document.removeEventListener('click', function() {
        closeSelectOptions(numId);
    });
}



function filterOptions(numId) {
    // Obtener el valor de búsqueda
    var searchText = document.getElementById('searchInput'+numId).value.toLowerCase();
    
    // Obtener el select
    var select = document.getElementById(numId);
    
    // Obtener todas las opciones del select
    var options = select.getElementsByTagName('option');
    
    // Iterar sobre las opciones y mostrar/ocultar según el texto de búsqueda
    for (var i = 0; i < options.length; i++) {
        var option = options[i];
        var optionText = option.textContent.toLowerCase();
        
        // Mostrar la opción si coincide con el texto de búsqueda o si el texto de búsqueda está vacío
        if (optionText.includes(searchText) || searchText === '') {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }


  // Agregar manejador de eventos de clic a cada opción
  option.addEventListener('mouseover', function(event) {
    event.target.style.backgroundColor = 'lightgray'; // Cambiar el color al pasar el cursor
});

option.addEventListener('mouseout', function(event) {
    event.target.style.backgroundColor = ''; // Restablecer el color al salir del cursor
});




         // Agregar manejador de eventos de clic a cada opción
         option.addEventListener('click', function(event) {
            var selectedValue = event.target.value;
            document.getElementById('searchInput'+numId).value = selectedValue;
        });
    }
}



window.onload = function () {

    llenarMenuDesplegableIngredientes();

    // Número total de cálculos
    var numCalculations = 15;

    // Iterar sobre cada cálculo
    for (var i = 1; i <= numCalculations; i++) {
        // Obtener el ID del elemento de entrada
        var inputId = i * 6 + 1;

        // Agregar el listener de cambio al elemento de entrada
        document.getElementById(inputId).addEventListener('change', function (event) {
            var numId = event.target.id; // Obtiene el ID del elemento que desencadenó el evento
            limpiarCalculos(numId); // Llama a la función limpiarCalculos con el ID del elemento
        });
    }


    

}

