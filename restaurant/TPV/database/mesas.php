<?php
// Incluir el archivo de conexión
include_once('conexion.php');  // Asegúrate de que la ruta es correcta

// Establecer el encabezado de tipo de contenido a JSON
header('Content-Type: application/json');

// Función para limpiar y validar nombre de mesa (opcional)
function limpiarNombreMesa($nombre) {
    return trim(htmlspecialchars($nombre));
}

// Manejar peticiones POST para crear, editar o borrar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? null;

    if ($accion === 'editar') {
        // Editar mesa
        if (empty($_POST['id']) || empty($_POST['nombre'])) {
            echo json_encode(['error' => 'ID y nombre son requeridos para editar']);
            exit;
        }
        $id = intval($_POST['id']);
        $nombre = limpiarNombreMesa($_POST['nombre']);

        if ($nombre === '') {
            echo json_encode(['error' => 'El nombre de la mesa no puede estar vacío']);
            exit;
        }

        try {
            $sql = "UPDATE mesas SET nombre = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $id]);
            echo json_encode(['success' => 'Mesa actualizada correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al actualizar la mesa: ' . $e->getMessage()]);
        }
        exit;
    } elseif ($accion === 'borrar') {
        // Borrar mesa
        if (empty($_POST['id'])) {
            echo json_encode(['error' => 'ID es requerido para borrar']);
            exit;
        }
        $id = intval($_POST['id']);

        try {
            $sql = "DELETE FROM mesas WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            echo json_encode(['success' => 'Mesa borrada correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al borrar la mesa: ' . $e->getMessage()]);
        }
        exit;
    } else {
        // Crear nueva mesa (caso original)
        if (!isset($_POST['nombre'])) {
            echo json_encode(['error' => 'El nombre es requerido para crear una mesa']);
            exit;
        }
        $nombreMesa = limpiarNombreMesa($_POST['nombre']);

        if (empty($nombreMesa)) {
            echo json_encode(['error' => 'El nombre de la mesa no puede estar vacío']);
            exit;
        }

        try {
            // Insertar nueva mesa
            $sql = "INSERT INTO mesas (nombre) VALUES (?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombreMesa]);

            echo json_encode([
                'success' => 'Mesa añadida correctamente',
                'mesa' => ['id' => $pdo->lastInsertId(), 'nombre' => $nombreMesa]
            ]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al añadir la mesa: ' . $e->getMessage()]);
        }
        exit;
    }
}

// GET para obtener una mesa específica o todas las mesas
if (isset($_GET['mesa_id'])) {
    $mesa_id = intval($_GET['mesa_id']);

    try {
        $sql = 'SELECT * FROM mesas WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mesa_id]);

        $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($mesa) {
            echo json_encode($mesa);
        } else {
            echo json_encode(['error' => 'Mesa no encontrada']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener la mesa: ' . $e->getMessage()]);
    }
} else {
    // Devolver todas las mesas
    try {
        $sql = 'SELECT * FROM mesas';
        $stmt = $pdo->query($sql);

        $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($mesas)) {
            echo json_encode(['error' => 'No hay mesas disponibles']);
        } else {
            echo json_encode($mesas);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener las mesas: ' . $e->getMessage()]);
    }
}
?>
