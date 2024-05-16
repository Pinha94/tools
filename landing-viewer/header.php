<?php 
    include 'app.php';    

    $hash = $_POST['hash'] ?? null;
    $pais = $_POST['pais'] ?? null;
    $digitsCant = $_POST['digitsCant'] ?? null;
    $prefix = $_POST['prefix'] ?? null;
    $custom = $_POST['custom'] ?? null;
    $msisdns = null;
    $magic = '';
    if (isset($_POST['ambiente']) && ($_POST['ambiente'] == '1')) {
        $ambiente = null;
    } else {
        $ambiente = $_POST['ambiente'] ?? null;
    }
        

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de landings</title>
    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
    <link rel="stylesheet" href="inc/css/style.css">
    <link rel="icon" href="inc/images/favicon.png" type="image/png">

</head>
<body class="dark-theme">
    <h1 class="title text-center">Visualizador de landings</h1>