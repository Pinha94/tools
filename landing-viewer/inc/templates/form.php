<?php 
    $paises = getJSON('paises.json');
?>
<section id="configForm" class="seccion">
    <h4>Configuraciones</h4>
    <form action="" method="post">
        <fieldset>
            <input value="<?php echo $_POST['hash'] ?? null ?>"
                type="text" name="hash" id="hash" placeholder="Hash">
                <select name="ambiente" id="ambiente">
                    <option value="dev" <?php echo ($_POST['ambiente'] == 'dev') ? 'selected' : ''; ?>>DEV</option>
                    <option value="qa" <?php echo ($_POST['ambiente'] == 'qa') ? 'selected' : ''; ?>>QA</option>
                    <option value="prod" <?php echo ($_POST['ambiente'] == 'prod') ? 'selected' : ''; ?>>PROD</option>
                </select>
            <select name="pais" id="pais">
                <option value="0" selected disabled>Selecciona un país</option>
                <?php foreach ($paises as $pais => $data) : ?>
                <option value="<?php echo $pais; ?>" <?php echo ($_POST['pais'] == $pais) ? 'selected' : ''; ?>><?php echo $pais; ?></option>
                <?php endforeach; ?>
                <option value="new">Nuevo país</option>
            </select>
            <input value="<?php echo $_POST['digitsCant'] ?? null ?>" type="number" 
                name="digitsCant" id="digitsCant" placeholder="Cantidad de dígitos">
            <input value="<?php echo $_POST['prefix'] ?? null ?>" type="number" 
                name="prefix" id="prefix" placeholder="Prefijo">
            <input value="<?php echo $_POST['custom'] ?? '159/?' ?>" type="text" 
                name="custom" id="custom" placeholder="Parametros personalizados">
        </fieldset>
        <button type="submit" class="button">Enviar</button>
    </form>
</section>


<script>
    // Obtener elementos del DOM
    const selectPais = document.getElementById('pais');
    const inputDigitsCant = document.getElementById('digitsCant');
    const inputPrefix = document.getElementById('prefix');

    // Evento change para el select de país
    selectPais.addEventListener('change', function() {
        // Obtener el país seleccionado
        const selectedOption = selectPais.options[selectPais.selectedIndex];
        const pais = selectedOption.value;
        if (pais !== 'new') {
            const datosPais = <?php echo json_encode($paises); ?>[pais];
            // Asignar los valores correspondientes a los inputs
            inputDigitsCant.value = datosPais.cantidad_digitos;
            inputPrefix.value = datosPais.prefijo;
            changeParmas(inputDigitsCant, true);
            changeParmas(inputPrefix, true);
        } else {
            changeParmas(inputDigitsCant, false);
            changeParmas(inputPrefix, false);
        }
    });
    function changeParmas(element, value) {
        element.readOnly = value
        // Si value es true agrega la clase disabled else la remueve
        value ? element.classList.add('disabled') : element.classList.remove('disabled');
    }
</script>
