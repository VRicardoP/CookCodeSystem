<?php



require_once __DIR__ .'/../../config.php';

// Ruta de recepción del webhook
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Registrar el contenido recibido en un archivo de log para depuración
    file_put_contents('webhook_log.txt', print_r($_POST, true), FILE_APPEND);

    // Extraer la información necesaria del producto
    if (isset($_POST['sku'])) {
        $sku = $_POST['sku'];  // Recibir el SKU

        // URL del archivo crearElaborado.php
       $url = BASE_URL . '/kitchen/elaborations/crearElaborado.php';

        // Datos a enviar al archivo crearElaborado.php
        $data = array('skuExiste' => $sku);

        // Configurar la solicitud POST
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        // **Depuración: Log de la respuesta**
        if ($result === FALSE) {
            error_log("Error al enviar SKU a crearElaborado.php");
        } else {
            error_log("SKU enviado correctamente a crearElaborado.php: " . $result);
        }
    } else {
        error_log("SKU no encontrado en la solicitud");
    }
} else {
    echo "Método no permitido";
}
