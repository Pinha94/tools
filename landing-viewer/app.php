<?php 
    include 'config/database.php';
    include 'config/functions.php';

    // Iniciar sesión si no está iniciada
    if (session_status() === 1) {
        session_start();
    }

    function autenticar() {
        if ($_SESSION['user'] !== 'AdminViewer' && $_SESSION['pass'] !== 'Opratel23') {
            header('location: login.php');
        }
    }
?>