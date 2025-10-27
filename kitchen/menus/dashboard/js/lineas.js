




const datosEjemplo = [
    { fecha: '2024-08-01', valor: 150, tipo: 'coste' },
    { fecha: '2024-08-05', valor: 200, tipo: 'venta' },
    { fecha: '2024-08-10', valor: 180, tipo: 'coste' },
    { fecha: '2024-08-15', valor: 220, tipo: 'venta' },
    { fecha: '2024-08-22', valor: 170, tipo: 'coste' },
    { fecha: '2024-08-28', valor: 210, tipo: 'venta' },
    { fecha: '2024-09-01', valor: 200, tipo: 'coste' },
    { fecha: '2024-09-05', valor: 230, tipo: 'venta' },
    { fecha: '2024-09-12', valor: 190, tipo: 'coste' },
    { fecha: '2024-09-18', valor: 250, tipo: 'venta' }
];







function inicializarGraficaLineas(costesYVentas) {
    const ctx = document.getElementById('graficaLineas').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: costesYVentas,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Semana'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Valor'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
}






function agruparDatosPorSemanas(datos) {
    const semanas = {};

    datos.forEach(item => {
        const fecha = new Date(item.fecha);
        const semana = getSemana(fecha);
        const valor = parseFloat(item.valor);

        if (!semanas[semana]) {
            semanas[semana] = { coste: 0, venta: 0 };
        }

        if (item.tipo === 'coste') {
            semanas[semana].coste += valor;
        } else if (item.tipo === 'venta') {
            semanas[semana].venta += valor;
        }
    });

    return semanas;
}

function getSemana(fecha) {
    const start = new Date(fecha.getFullYear(), 0, 1);
    const weekNumber = Math.ceil(((fecha - start) / 86400000 + 1) / 7);
    return `${fecha.getFullYear()}-W${weekNumber}`;
}

function prepararDatosGrafica(semanas) {
    const labels = Object.keys(semanas);
    const datosCoste = labels.map(semana => semanas[semana].coste);
    const datosVenta = labels.map(semana => semanas[semana].venta);

    return {
        labels: labels,
        datasets: [
            {
                label: 'Cost(€)',
                data: datosCoste,
                borderColor: '#d12128',
                fill: false
            },
            {
                label: 'Sale(€)',
                data: datosVenta,
                borderColor: '#007934',
                fill: false
            }
        ]
    };
}


export function cargarYMostrarGrafica() {
    const semanas = agruparDatosPorSemanas(datosEjemplo);
    const costesYVentas = prepararDatosGrafica(semanas);

    inicializarGraficaLineas(costesYVentas);
}
