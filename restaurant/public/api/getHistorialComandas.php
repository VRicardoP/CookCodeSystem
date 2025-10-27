<?php
session_start();
header('Content-Type: application/json');
/** 
if (empty($_SESSION['isLogged']) || !$_SESSION['isLogged']) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}
*/
$conn = new mysqli('localhost', 'root', '', 'restaurant');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión con la base de datos']);
    exit;
}

// Consulta SQL
$sql = "
    SELECT 
        h.id AS ticket_id,
        h.mesa_id,
        h.fecha_creacion,
        h.total,
        e.elaborado_id AS producto_id,
        e.nombre AS nombre_producto,
        d.cantidad,
        d.precio_unitario
    FROM comandas_historial h
    JOIN comandas_detalle d ON h.id = d.comanda_id
    JOIN elaborado e ON d.producto_id = e.elaborado_id
    ORDER BY h.fecha_creacion DESC, h.id DESC
";

$result = $conn->query($sql);

$tickets = [];

while ($row = $result->fetch_assoc()) {
    $ticketId = $row['ticket_id'];

    if (!isset($tickets[$ticketId])) {
        $tickets[$ticketId] = [
            'ticket_id' => $ticketId,
            'mesa_id' => $row['mesa_id'],
            'fecha_creacion' => $row['fecha_creacion'],
            'total' => $row['total'],
            'productos' => []
        ];
    }

    $tickets[$ticketId]['productos'][] = [
        'producto_id' => $row['producto_id'],
        'nombre_producto' => $row['nombre_producto'],
        'cantidad' => $row['cantidad'],
        'precio_unitario' => $row['precio_unitario']
    ];
}

echo json_encode(array_values($tickets), JSON_UNESCAPED_UNICODE);
$conn->close();
