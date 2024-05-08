<?php include 'header.php'; ?>

<?php
    unset($_SESSION['user']);
    unset($_SESSION['pass']);
    
    // session_abort();
    if (!isset($_SESSION['user'])) {
        header('location: login.php');
    } else {
        echo 'No se pudo cerrar sesión';
    }
?>

<?php include 'footer.php'; ?>