
import { inicializarGraficaEstadoCaducidad } from './estadoCaducidad.js';
import { cargarYMostrarGrafica } from './lineas.js';
import { inicializarGraficaCaducidad } from './pieCaducidad.js';
import { inicializarGraficaAutoconsumo, actualizarGraficaConProductosSeleccionadosAutoconsumo } from './autoConsumo.js';
import { inicializarGraficaCostes, actualizarGraficaConProductosSeleccionados } from './coste.js';
import { inicializarGraficaStock, actualizarGraficaConProductosSeleccionadosStock } from './stock.js';
import { inicializarGraficaBeneficios, actualizarGraficaConProductosSeleccionadosBeneficios } from './beneficios.js';


// Variables globales
let myBarChartCostos;
let myBarChartBeneficios;
let myBarChartVentas;
let myBarChartStock;

let productos = [];


// Función para filtrar datos según el rango de fechas
function filtrarDatosPorFecha(datos, filtro) {
    const { startDate, endDate } = obtenerRangoFechas(filtro);
    if (!startDate) return datos;
    return datos.filter(item => {
        const fecha = new Date(item.fechaCaducidad);
        return fecha >= startDate && fecha <= endDate;
    });
}

// Función para inicializar las gráficas desde los datos
function inicializarGraficas() {
    myBarChartCostos = inicializarGraficaCostes();
    myBarChartBeneficios = inicializarGraficaBeneficios();
    myBarChartStock = inicializarGraficaStock();
    inicializarGraficaAutoconsumo();
    inicializarGraficaCaducidad();
    inicializarGraficaEstadoCaducidad();

}

// Función para obtener el rango de fechas según el filtro
function obtenerRangoFechas(filtro) {
    const hoy = new Date();
    let startDate = new Date();
    let endDate = hoy;

    switch (filtro) {
        case 'semana':
            startDate.setDate(hoy.getDate() - 7);
            break;
        case 'mes':
            startDate.setMonth(hoy.getMonth() - 1);
            break;
        case 'año':
            startDate.setFullYear(hoy.getFullYear() - 1);
            break;
        case 'todos':
            startDate = null;
            break;
    }

    return { startDate, endDate };
}

// Función para obtener los platos seleccionados
function obtenerSeleccion(className) {
    const checkboxes = document.querySelectorAll(`.${className}:checked`);
    return Array.from(checkboxes).map(cb => cb.value);
}

// Función para obtener el filtro de tiempo
function obtenerFiltro(filtroId) {
    return document.getElementById(filtroId).value;
}



var botonModalCoste = document.getElementById("consultarCostosBtn");

// Agregar el event listener para el evento 'click'
botonModalCoste.addEventListener("click", function () {

    actualizarGraficaConProductosSeleccionados();
});


var botonModalBeneficio = document.getElementById("consultarBeneficiosBtn");

// Agregar el event listener para el evento 'click'
botonModalBeneficio.addEventListener("click", function () {

    actualizarGraficaConProductosSeleccionadosBeneficios();
});

var botonModalStock = document.getElementById("consultarStockBtn");

// Agregar el event listener para el evento 'click'
botonModalStock.addEventListener("click", function () {

    actualizarGraficaConProductosSeleccionadosStock();
});

var botonModalAutoconsumo = document.getElementById("consultarAutoconsumoBtn");

// Agregar el event listener para el evento 'click'
botonModalAutoconsumo.addEventListener("click", function () {

    actualizarGraficaConProductosSeleccionadosAutoconsumo();
});


// Inicializar todo al cargar la página
window.onload = function () {
    inicializarGraficas();
    cargarYMostrarGrafica();

};




// Función para actualizar la gráfica de Costos
function actualizarGraficaCostos(datos) {
    const filtro = obtenerFiltro('filtroCostos');
    const datosFiltrados = filtrarDatosPorFecha(datos.costos, filtro);
    const seleccionados = obtenerSeleccion('platoCheckboxCostos');

    const data = seleccionados.map(plato => {
        const item = datosFiltrados.find(d => d.nombre === plato);
        return item ? item.valor : 0;
    });

    myBarChartCostos.data.labels = seleccionados.length ? seleccionados : datosFiltrados.map(d => d.nombre);
    myBarChartCostos.data.datasets[0].data = seleccionados.length ? data : datosFiltrados.map(d => d.valor);
    myBarChartCostos.update();
}

// Función para actualizar la gráfica de Beneficios
function actualizarGraficaBeneficios(datos) {
    const filtro = obtenerFiltro('filtroBeneficios');
    const datosFiltrados = filtrarDatosPorFecha(datos.beneficios, filtro);
    const seleccionados = obtenerSeleccion('platoCheckboxBeneficios');

    const data = seleccionados.map(plato => {
        const item = datosFiltrados.find(d => d.nombre === plato);
        return item ? item.valor : 0;
    });

    myBarChartBeneficios.data.labels = seleccionados.length ? seleccionados : datosFiltrados.map(d => d.nombre);
    myBarChartBeneficios.data.datasets[0].data = seleccionados.length ? data : datosFiltrados.map(d => d.valor);
    myBarChartBeneficios.update();
}

// Función para actualizar la gráfica de Ventas
function actualizarGraficaVentas(datos) {
    const filtro = obtenerFiltro('filtroVentas');
    const datosFiltrados = filtrarDatosPorFecha(datos.ventas, filtro);
    const seleccionados = obtenerSeleccion('platoCheckboxVentas');

    const data = seleccionados.map(plato => {
        const item = datosFiltrados.find(d => d.nombre === plato);
        return item ? item.valor : 0;
    });

    myBarChartVentas.data.labels = seleccionados.length ? seleccionados : datosFiltrados.map(d => d.nombre);
    myBarChartVentas.data.datasets[0].data = seleccionados.length ? data : datosFiltrados.map(d => d.valor);
    myBarChartVentas.update();
}



function filtrarDatosPorEstado(estadoProductos, filtro) {
    let filteredLabels = [];
    let filteredData = [];

    // Filtrar los datos según el filtro seleccionado
    estadoProductos.datasets.forEach(dataset => {
        if (dataset.label === filtro) {
            filteredLabels = estadoProductos.labels;  // Todas las etiquetas (nombres de productos)
            filteredData.push({
                label: dataset.label,
                data: dataset.data,
                backgroundColor: dataset.backgroundColor
            });
        }
    });

    return {
        labels: filteredLabels,
        datasets: filteredData
    };
}

