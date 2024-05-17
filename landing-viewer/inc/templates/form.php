<?php 
    $conn = connectDB();
    $paises = getAll($conn, 'paises');
    closeConnection($conn);
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
                        <?php foreach ($paises as $element) : ?>
                        <option value="<?php echo $element['nombre_pais']; ?>" <?php echo ($element['nombre_pais'] == $pais) ? 'selected' : ''; ?>>
                            <?php echo $element['nombre_pais']; ?>
                        </option>
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

<script>
    var paises = <?php echo json_encode($paises); ?>;
    const selectPais = document.getElementById('pais');
    const inputDigitsCant = document.getElementById('digitsCant');
    const inputPrefix = document.getElementById('prefix');

    // Autocompleta cant digit & prefijo al elegir un pais
    selectPais.addEventListener('change', function() {
        // Obtener el país seleccionado
        const selectedOption = selectPais.options[selectPais.selectedIndex];
        const currentPais = selectedOption.value;
        if (currentPais !== 'new') {
            var datosPais;
            paises.forEach(pais => {
                if (pais.nombre_pais === currentPais) datosPais = pais;
            });
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

    // Obtiene el historial desde el localstorage
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

            // Verificar si el historial tiene más de 10 elementos y elimina el más antiguo
            if (historial.length > 10) {historial.shift();}

            // Almacenar los datos actualizados en la memoria local
            localStorage.setItem('historial', JSON.stringify(historial));
        }
    } else {
        console.error('El objeto postData no está definido o es nulo.');
    }

</script>
