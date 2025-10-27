const INGREDIENTES_TBODY = document.getElementById("ingredientes-tbody")
window.addEventListener("load", mostrarIngredientes)
let ingredientes

async function mostrarIngredientes() {
    ingredientes = await fetchIngredientes()

    ingredientes.forEach( ingrediente => {
        const row = document.createElement("tr")
        row.innerHTML += `<td>${ingrediente.producto}</td>`
        row.innerHTML += `<td>${ingrediente.unidad}</td>`
        row.innerHTML += `<td>${ingrediente.precio}</td>`
        row.innerHTML += `<td>${(ingrediente.merma) * 100}%</td>`
        INGREDIENTES_TBODY.appendChild(row)
    });
}   

async function fetchIngredientes() {
    try {
        const response = await fetch("./../../../controllers/datosIngredients.php");
        if (!response.ok) {
            throw new Error('Error al recibir ingredientes');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
        return null;
    }
}