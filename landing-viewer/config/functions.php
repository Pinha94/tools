<?php 

    if (!empty($_POST) && isset($_POST['addnew'])) {
        addNewCountry();
    }

    // Agrega un nuevo pais con la info enviada por POST
    function addNewCountry() {
        // Obtener los datos del formulario
        $newNameCountry = $_POST['pais'];
        $newDigitsCant = $_POST['digitsCant'];
        $newPrefix = $_POST['prefix'];

        $data = array(
            "nombre_pais" => $newNameCountry,
            "cantidad_digitos" => $newDigitsCant,
            "prefijo"     => $newPrefix
        );
        
        $conn = connectDB();
        addOrUpdateElement($conn, 'paises', $data, 'nombre_pais', $newNameCountry);
        closeConnection($conn);
    }
?>