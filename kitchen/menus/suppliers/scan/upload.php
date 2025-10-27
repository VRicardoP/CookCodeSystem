<?php
if (isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];

    // Escapar correctamente el nombre del archivo para prevenir problemas de seguridad
    $file_name = basename($file_name);

    // Mover el archivo subido a la carpeta de destino
    $destination = "images/" . $file_name;
    if (move_uploaded_file($file_tmp, $destination)) {
  
        $command = '"C:\\Program Files\\Tesseract-OCR\\tesseract" "C:\\wamp64\\www\\kitchen\\scan\\images\\'. $file_name.'" out';
        
        // Ejecutar el comando y capturar la salida
        $output = shell_exec($command);

        $outputFile = 'out.txt';
        if (file_exists($outputFile)) {
            $outputText = file_get_contents($outputFile);
            echo "" . htmlspecialchars($outputText) . "";
        } else {
            echo "Error: El archivo de salida no se encontró.";
        }
    } else {
        echo "Failed to upload image";
    }
} else {
    echo "No image uploaded";
}
?>
