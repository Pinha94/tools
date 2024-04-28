<?php 
    include 'app.php';

    $hash = $_POST['hash'] ?? null;
    $ambiente = $_POST['ambiente'] ?? null;
    $pais = $_POST['pais'] ?? null;
    $digitsCant = $_POST['digitsCant'] ?? null;
    $prefix = $_POST['prefix'] ?? null;
    $custom = $_POST['custom'] ?? null;
    $msisdns = null;
    $magic = '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de landings</title>
    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
    <link rel="stylesheet" href="inc/css/style.css">
</head>
<body class="dark-theme">
    <h1 class="title text-center">Visualizador de landings</h1>