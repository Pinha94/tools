<?php 
    $paises = getJSON('paises.json');

    if (!empty($_POST) && isset($_POST['addnew'])) {
        // Obtener los datos del formulario
        $newNameCountry = $_POST['pais'];
        $nuevoDigitsCant = $_POST['digitsCant'];
        $newPrefix = $_POST['prefix'];

        // Obtener las claves del array $paises
        $claves_paises = array_keys($paises);
        // Obtener el último ID
        $ultima_clave = end($claves_paises);
        $ultimo_id = $paises[$ultima_clave]['id'];
        // Nuevo ID
        $nuevo_id = $ultimo_id + 1;
    
        // Crear un nuevo array para el nuevo país
        $newCountry = array(
            "id" => $nuevo_id,
            "cantidad_digitos" => $nuevoDigitsCant,
            "prefijo" => $newPrefix
        );
    
        // Agregar el nuevo país al array de paises
        $paises[$newNameCountry] = $newCountry;
    
        // Convertir el array actualizado a formato JSON
        $json_actualizado = json_encode($paises, JSON_PRETTY_PRINT);
    
        // Escribir los datos actualizados en el archivo JSON
        $rutaJSON = $_SERVER['DOCUMENT_ROOT'] . 'tools/landing-viewer/config/database/paises.json';
        file_put_contents($rutaJSON, $json_actualizado);
    }
?>
<div class="seccion-columns">
    <section id="configForm" class="seccion">
        <form id="configForm" action="" method="post">
            <h4>Configuraciones</h4>
            <fieldset>
                <div class="field-content">
                    <label for="hash">Hash</label>
                    <input class="field" value="<?php echo $hash ?? null ?>"
                        type="text" name="hash" id="hash" placeholder="Hash" required>
                </div>
                <div class="field-content">
                    <label for="ambiente">Ambiente</label>
                    <select class="select" name="ambiente" id="ambiente">
                        <option value="dev" <?php echo ($ambiente == 'dev') ? 'selected' : ''; ?>>DEV</option>
                        <option value="qa" <?php echo ($ambiente == 'qa') ? 'selected' : ''; ?>>QA</option>
                        <option value="qav2" <?php echo ($ambiente == 'qav2') ? 'selected' : ''; ?>>QA V2</option>
                        <option value="1" <?php echo ($ambiente == '1') ? 'selected' : ''; ?>>PROD</option>
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
                        name="digitsCant" id="digitsCant" placeholder="Cantidad de dígitos" required>
                </div>
                <div class="field-content">
                    <label for="prefix">Prefijo</label>
                    <input value="<?php echo $prefix ?? null ?>" class="field" type="number"
                        name="prefix" id="prefix" placeholder="Prefijo" required>
                </div>
                <div class="field-content">
                    <label for="custom">Personalizado</label>
                    <input value="<?php echo $custom ?? '159/?' ?>" class="field" type="text"
                        name="custom" id="custom" placeholder="Parametros personalizados" required>
                </div>
            </fieldset>
            <button id="sendUrl" type="submit" class="button">Enviar</button>
        </form>
    </section>
    <aside id="historial" class="seccion">
        <div class="title-content">
            <h5>Recientes</h5>
            <button id="clearHistoryBtn" class="button icon-button red" title="Vaciar historial">
                <i class="fa fa-trash-can"></i>
            </button>
        </div>
        <ul id="historialContent" class="content">
        </ul>
    </aside>
</div>
<section id="popup" class="hide">
    <div class="content">
        <h4>Agrega un nuevo país</h4>
        <form action="" id="addCountry" method="POST" class="field-content">
            <input type="hidden" name="addnew" value="true">
            <input class="field" type="text" name="pais" id="nuevoPais" placeholder="País">
            <input class="field" type="number" name="digitsCant" id="newDigitsCant" placeholder="Cantidad de dígitos">
            <input class="field" type="number" name="prefix" id="newPrefix" placeholder="Prefijo">
            <button type="submit" id="addButton" class="button">Agregar</button>
            <button type="button" id="cancelButton" class="button red">Cancelar</button>
        </form>
    </div>
</section>

<?php
    
?>


<script>
    const selectPais = document.getElementById('pais');
    const inputDigitsCant = document.getElementById('digitsCant');
    const inputPrefix = document.getElementById('prefix');

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

    // =======================

    function getHistorial() {
        const historialData = localStorage.getItem('historial');
        if (historialData) {
            return JSON.parse(historialData);
        } else {
            return [];
        }
    }

    // Obtener historial actual
    let historial = getHistorial();

    // Convertir el array $_POST de PHP a JSON
    const postData = <?php echo (!empty($_POST) && !isset($_POST['addnew'])) ? json_encode($_POST) : 'null'; ?>;

    // Verificar si postData está definido y no es nulo
    if (postData !== undefined && postData !== null) {
        // Verificar si la combinación de datos ya existe en el historial
        const existeEnHistorial = historial.some(item => JSON.stringify(item) === JSON.stringify(postData));

        // Si la combinación de datos no existe en el historial, agregarla
        if (!existeEnHistorial) {
            // Agregar el nuevo elemento al historial
            historial = Array.isArray(historial) ? historial : []; // Asegurarse de que historial sea un array
            historial.push(postData);

            // Verificar si el historial tiene más de 10 elementos
            if (historial.length > 10) {
                // Eliminar el elemento más antiguo
                historial.shift();
            }

            // Almacenar los datos actualizados en la memoria local
            localStorage.setItem('historial', JSON.stringify(historial));
        }
    } else {
        // console.error('El objeto postData no está definido o es nulo.');
    }

</script>
