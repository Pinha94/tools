<?php 
    $paises = getJSON('paises.json');
?>
<section id="configForm" class="seccion">
    <h4>Configuraciones</h4>
    <form action="" method="post">
        <fieldset>
            <div class="field-content">
                <label for="hash">Hash</label>
                <input class="field" value="<?php echo $hash ?? null ?>"
                    type="text" name="hash" id="hash" placeholder="Hash">
            </div>
            <div class="field-content">
                <label for="ambiente">Ambiente</label>
                <select class="select" name="ambiente" id="ambiente">
                    <option value="dev" <?php echo ($ambiente == 'dev') ? 'selected' : ''; ?>>DEV</option>
                    <option value="qa" <?php echo ($ambiente == 'qa') ? 'selected' : ''; ?>>QA</option>
                    <option value="prod" <?php echo ($ambiente == 'prod') ? 'selected' : ''; ?>>PROD</option>
                </select>
            </div>
            <div class="field-content">
                <label for="pasi">Pais</label>
                <select class="select" name="pais" id="pais">
                    <option value="0" selected disabled>Selecciona un país</option>
                    <?php foreach ($paises as $element => $data) : ?>
                    <option value="<?php echo $element; ?>" <?php echo ($pais == $element) ? 'selected' : ''; ?>><?php echo $element; ?></option>
                    <?php endforeach; ?>
                    <option value="new">Nuevo país</option>
                </select>
            </div>
            <div class="field-content">
                <label for="digitsCant">Cantidad de dígitos</label>
                <input value="<?php echo $digitsCant ?? null ?>" class="field" type="number" 
                    name="digitsCant" id="digitsCant" placeholder="Cantidad de dígitos">
            </div>
            <div class="field-content">
                <label for="prefix">Prefijo</label>
                <input value="<?php echo $prefix ?? null ?>" class="field" type="number" 
                    name="prefix" id="prefix" placeholder="Prefijo">
            </div>
            <div class="field-content">
                <label for="custom">Personalizado</label>
                <input value="<?php echo $custom ?? '159/?' ?>" class="field" type="text" 
                    name="custom" id="custom" placeholder="Parametros personalizados">
            </div>
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
