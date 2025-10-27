<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'restaurant');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión con la base de datos']);
    exit;
}

// Crear arreglo de los últimos 7 días (YYYY-MM-DD)
$fechas = [];
for ($i = 6; $i >= 0; $i--) {
    $fechas[] = date('Y-m-d', strtotime("-$i days"));
}

// Obtener ventas por día existentes
$sql = "
    SELECT 
        DATE(fecha_creacion) AS fecha,
        SUM(total) AS total_dia
    FROM comandas_historial
    WHERE fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(fecha_creacion)
    ORDER BY fecha ASC
";

$result = $conn->query($sql);

$ventas = [];
while ($row = $result->fetch_assoc()) {
    $ventas[$row['fecha']] = $row['total_dia'];
}

// Rellenar faltantes con 0
$dataFinal = [];
foreach ($fechas as $fecha) {
    $dataFinal[] = [
        'fecha' => $fecha,
        'total_dia' => isset($ventas[$fecha]) ? $ventas[$fecha] : 0
    ];
}

echo json_encode($dataFinal);
