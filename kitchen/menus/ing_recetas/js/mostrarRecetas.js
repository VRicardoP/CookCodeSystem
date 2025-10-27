// Para la ventana "Recetas" en recipesC.php
const INPUT_NOMBRE_RECETA = document.getElementById("recetas-recipeName")
const P_INSTRUCCIONES = document.getElementById("receta-instrucciones")
const DATA_LIST = document.getElementById("listaRecetas")
const TABLE_BODY = document.getElementById("receta-tbody")
let recetas = []

window.addEventListener("load", mostrarRecetas)
INPUT_NOMBRE_RECETA.addEventListener("change", cargarReceta)

async function fetchRecetas() {
    try {
        const response = await fetch("./../../../controllers/getRecetas.php");
        if (!response.ok) {
            throw new Error('Error al recibir recetas');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
        return null;
    }
}

async function mostrarRecetas(ev) {
    recetas = await fetchRecetas();
    if (recetas) {
        rellenarDatalist(recetas)
    } else {
        console.log('No se pudieron obtener las recetas');
    }
}

function rellenarDatalist(recetas) {
    recetas.forEach(recetas => {
        let option = document.createElement("option")
        option.value = recetas.receta
        DATA_LIST.appendChild(option)
    });
}

async function cargarReceta(ev) {
    const DATOS_RECETA = recetas.find((element) => element.receta == ev.target.value)
    console.log(DATOS_RECETA)
    P_INSTRUCCIONES.innerText = DATOS_RECETA.instrucciones
    // Tengo el id, ahora hay que hacer el fetch la query y tal para que me de los ingredientes de esa id

    const recetaId = DATOS_RECETA.id; 
    const url = `getRecipeIngredients.php?receta_id=${recetaId}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
            } else {
                console.log('Success:', data);
                rellenarTabla(data)
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });

}

function rellenarTabla(ingredientes) {
    TABLE_BODY.innerHTML = ""
    ingredientes.forEach( ingrediente => {
        let unidad = ingrediente.tipo_cantidad
        let totalMerma = Number((ingrediente.cantidad * ingrediente.merma).toFixed(2))
        let totalBruto = totalMerma + ingrediente.cantidad
        let costeFinal = Number((totalBruto * ingrediente.costPrice).toFixed(2))

        if (ingrediente.merma === 0) {
            totalMerma = 0
            totalBruto = ingrediente.cantidad
            costeFinal = totalBruto * ingrediente.costPrice
        }

        if (unidad === 0) {
            unidad = "Kg"
        }

        const row = document.createElement("tr")
        row.innerHTML += `<td>${ingrediente.fName}</td>`
        row.innerHTML += `<td>${ingrediente.cantidad}</td>`
        row.innerHTML += `<td>${unidad}</td>`
        row.innerHTML += `<td>${(ingrediente.merma) * 100}%</td>`
        row.innerHTML += `<td>${totalMerma}</td>`
        row.innerHTML += `<td>${totalBruto}</td>`
        row.innerHTML += `<td>${costeFinal}</td>`
        TABLE_BODY.appendChild(row)
    });
}



