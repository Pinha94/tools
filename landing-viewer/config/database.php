<?php
    function connectDB() {
        $servername = 'localhost';
        $database = 'u579930509_landing_viewer';
        $username = 'u579930509_us_landingview';
        $password = 'Speu35TkWpNhVu5';
        
        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $database);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        return $conn;
    }

    function getAll($conn, $tabla) {
        // Consulta SQL para obtener todos los datos de la tabla especificada
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

        return $datos;
    }

    function addOrUpdateElement($conn, $tabla, $datos, $nombreColumna, $valorColumna) {
        // Verificar si ya existe un registro con el mismo valor en la columna especificada
        $sql = "SELECT * FROM $tabla WHERE $nombreColumna = '$valorColumna'";
        $resultado = $conn->query($sql);
    
        // Si ya existe un registro con el mismo valor en la columna especificada, editar ese registro
        if ($resultado->num_rows > 0) {
            $id = $resultado->fetch_assoc()['id']; // Suponiendo que el campo ID se llame 'id'
            return editElement($conn, $tabla, $id, $datos);
        } else {
            // Si no existe un registro con el mismo valor en la columna especificada, agregar uno nuevo
            // Construir la consulta SQL para insertar un nuevo elemento
            $campos = implode(", ", array_keys($datos));
            $valores = "'" . implode("', '", array_values($datos)) . "'";
            $sql = "INSERT INTO $tabla ($campos) VALUES ($valores)";
    
            // Ejecutar la consulta
            if ($conn->query($sql) === TRUE) {
                return "Nuevo elemento agregado correctamente.";
            } else {
                return "Error al agregar elemento: " . $conn->error;
            }
        }
    }
    
    
    function editElement($conn, $tabla, $id, $datos) {
        // Construir la parte de la consulta SQL para actualizar un elemento
        $update_values = '';
        foreach ($datos as $campo => $valor) {
            $update_values .= "$campo = '$valor', ";
        }
        // Eliminar la coma extra al final
        $update_values = rtrim($update_values, ", ");
        // Construir la consulta completa
        $sql = "UPDATE $tabla SET $update_values WHERE id = $id";
    
        // Ejecutar la consulta
        if ($conn->query($sql) === TRUE) {
            return "Elemento actualizado correctamente.";
        } else {
            return "Error al actualizar elemento: " . $conn->error;
        }
    }

    function deleteElement($conn, $tabla, $condicion) {
        // Construir la consulta SQL para eliminar un elemento
        $sql = "DELETE FROM $tabla WHERE $condicion";

        // Ejecutar la consulta
        if ($conn->query($sql) === TRUE) {
            return "Elemento eliminado correctamente.";
        } else {
            return "Error al eliminar elemento: " . $conn->error;
        }
    }

    function closeConnection($conn) {
        // Cerrar la conexión
        $conn->close();
    }
?>
