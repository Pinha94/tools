<?php
function getJSON($nombre_archivo) {
    // Lee el contenido del archivo JSON
    $json_data = file_get_contents(__DIR__ . '/database/' . $nombre_archivo);

    // Decodifica el JSON en un array asociativo de PHP
    $datos = json_decode($json_data, true);

    // Verifica si la decodificación fue exitosa
    if ($datos === null) {
        die("Error al decodificar el JSON");
    }

    return $datos;
}
?>
