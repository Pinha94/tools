<?php include 'header.php'; 

    $errors = null;
    $result = '';

    if (!empty($_POST)) {
        if ($_POST['user'] === 'AdminViewer' && $_POST['pass'] === 'Opratel23') {
            $_SESSION['user'] =  $_POST['user'];
            $_SESSION['pass'] =  $_POST['pass'];
            header('location: index.php');
        } else {
            unset($_SESSION['user']);
            unset($_SESSION['pass']);
            $errors = 'Usuario o contraseña incorrectos';
        }
        
    }
?>

<main id="main" class="container login-page">
    <section class="content seccion">
        <h2>Inicia sesión</h2>
        <form action="" method="post" id="loginForm">
            <div class="field-content"><input class="field" type="text" name="user" id="user" placeholder="Usuario"></div>
            <div class="field-content"><input class="field" type="password" name="pass" id="pass" placeholder="Contraseña"></div>
            <div class="error-msg text-center"><span class="small"><?php echo $errors !== null ? $errors : '' ?></span></div>
            <button type="submit" class="button">Continuar</button>
        </form>
    </section>
</main>

<?php include 'footer.php'; ?>
