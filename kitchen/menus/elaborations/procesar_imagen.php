<?php 
##Guarda la imagen en local

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_FILES['image'])) {
        $file_name = $_FILES['image']['name']; // Nombre original del archivo
        $file_tmp =$_FILES['image']['tmp_name']; // Ruta temporal del archivo en el servidor
        $file_type=$_FILES['image']['type']; // Tipo MIME del archivo
        $file_name_parts = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($file_name_parts)); // Extensión del archivo

        // Directorio donde se guardarán las imágenes
        $uploads_dir = '../img';

        // Verifica si el tipo de archivo es válido
        $allowed_extensions = array("jpg","jpeg","png", "tiff", "jfif", "svg", "svgz" ,"bmp","ico", "webp", "xbm", "dib", "pjb", "apng", "tif", "avif");
        if(in_array($file_ext,$allowed_extensions)){
            // Mueve el archivo al directorio de destino
            move_uploaded_file($file_tmp, "$uploads_dir/$file_name");
            echo "Imagen guardada con éxito.";
        } else {
            echo "Error: No es un archivo compatible";
        }
    } else {
        echo "Error: No se ha enviado ninguna imagen.";
    }
}
?>
