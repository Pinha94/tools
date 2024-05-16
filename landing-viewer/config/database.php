<?php
function conectarDB($tabla) {
    $servername = 'localhost';
    $database = 'u579930509_landing_viewer';
    $username = 'root';
    $password = '';
    
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para obtener todos los datos de la tabla "paises"
    $sql = "SELECT * FROM " . $tabla;
    $resultado = $conn->query($sql);

    // Verificar si hay resultados
    if ($resultado->num_rows > 0) {
        // Inicializar una variable para almacenar los resultados
        $datos = array();
        // Recorrer cada fila de resultados y agregarla a la variable
        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }
    } else {
        // Si no hay resultados, asignar un mensaje indicando que no se encontraron datos
        $datos = array("mensaje" => "No se encontraron datos en la tabla");
    }

    // Cerrar la conexión
    $conn->close();

    // Retornar los datos obtenidos como un array
    return $datos;
}
?>