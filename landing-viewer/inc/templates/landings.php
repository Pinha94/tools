<?php 

if (!empty($_POST) && !isset($_POST['addnew'])) :
    // Bucle para concatenar el número 1 tantas veces como el valor de $numReps
    for ($i = 0; $i < ($digitsCant - 1); $i++) {
        $magic .= '1';
    }
    $baseMsisdn = $prefix . $magic;

    $msisdns = array(
        array("msisdn" => '-1', "name" => "no he"),
        array("msisdn" => $baseMsisdn . "1", "name" => "request pin"),
        array("msisdn" => $baseMsisdn . "2", "name" => "he"),
        array("msisdn" => $baseMsisdn . "2", "name" => "confirm"),
        array("msisdn" => $baseMsisdn . "3", "name" => "error signup"),
        array("msisdn" => $baseMsisdn . "4", "name" => "active"),
        array("msisdn" => $baseMsisdn . "5", "name" => "doi"),
        array("msisdn" => $baseMsisdn . "1", "name" => "ok", "pin" => "11111"),
        array("msisdn" => $baseMsisdn . "1", "name" => "error", "pin" => "11112"),
        array("msisdn" => $baseMsisdn . "1", "name" => "error Pin", "pin" => "11113"),
        array("msisdn" => $baseMsisdn . "1", "name" => "pin reenviado", "pin" => "11114"),
        array("msisdn" => $baseMsisdn . "1", "name" => "pin expirado", "pin" => "11115"),
    );

    $baseUrl = 'http://';
    switch ($ambiente) {
        case '':
            $baseUrl .= '';
            break;
        case 'qav2':
            $baseUrl .= 'qa.v2.';
            break;
        default:
            $baseUrl .= $ambiente . '.';
            break;
    }
    // $baseUrl .= ($ambiente == '') ? '' : $ambiente . '.';
    $baseUrl .= 'oprastore.com/traffic/landing/';
    $baseUrl .= $hash . '/';
    $baseUrl .= $custom;
    $baseUrl .= 'msisdn=';    
?>
<section id="landings" class="seccion">
    <div class="title-content">
        <h4>Vistas</h4>
        <div class="right">
            <div class="actions">
                <div class="field-content">
                    <select class="select" name="flow" id="flow">
                        <option value="0" selected disabled>Flujo</option>
                        <option value="all">ALL</option>
                        <option value="pin">PIN</option>
                        <option value="wap">WAP</option>
                        <option value="doi">DOI</option>
                    </select>
                </div>
                <button id="uncheckedBtn" class="button icon-button" title="Desmarcar revisados"><i class="fa-regular fa-square"></i></button>
                <button id="reloadBtn" class="button icon-button" title="Recargar vistas"><i class="fa-solid fa-rotate-right"></i></button>
                <button id="copyHash" class="button icon-button" title="Copiar hash" data-tocopy="currentHash"><i class="fa-regular fa-copy"></i></button>
            </div>
            <h4 id="currentHash"><?php echo $hash; ?></h4>
        </div>
    </div>
    <div class="content">
        <?php 
        if ($msisdns !== null) :
            $i = 0;
            foreach ($msisdns as $msisdnData) :
                $i++;
                $msisdn = $msisdnData["msisdn"];
                $name = $msisdnData["name"];
                $pin = $msisdnData["pin"] ?? NULL;
                $class = str_replace(' ', '-', $name);
                // Generar el URL completo para el iframe y el enlace
                switch ($name) {
                    case 'confirm':
                        $fullUrl = $baseUrl . $msisdn . '&forcepin=true&navigate=2ndOptin';
                        break;
                    case 'request pin':
                    case 'no he':
                        $fullUrl = $baseUrl . $msisdn . '&nohe=true';
                        break;
                    default:
                        $fullUrl = $baseUrl . $msisdn . ($pin ? '&pincode=' . $pin : '');
                        break;
                }
        ?>
        <div class="viewer <?php echo $class; ?>">
            <div class="preview-content">
                <iframe
                    class="preview"
                    src="<?php echo $fullUrl ?>"
                    frameborder="0">
                </iframe>
            </div>
            <h5><?php echo $name; ?></h5>
            <div class="actions">
                <button class="button icon-button" title="Marcar como revisado">
                    <label for="checked<?php echo $i; ?>" class="check-content">
                        <input class="checkbox" type="checkbox" name="checked" id="checked<?php echo $i; ?>">
                        <i class="fa fa-check"></i>
                        <i class="fa-regular fa-square"></i>
                    </label>
                </button>
                <a href="<?php echo $fullUrl ?>" 
                    class="button icon-button" 
                    target="_blank" 
                    title="Abrir en una nueva pestaña"
                >
                    <i class="fa fa-eye"></i>
                </a>
                <button 
                    class="button icon-button" 
                    data-action="expand"
                    data-name="<?php echo $name; ?>"
                    data-src="<?php echo $fullUrl; ?>"
                    title="Expandir"
                >
                    <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                </button>
            </div>
        </div>
        <?php 
            endforeach; 
            endif;
        ?>
    </div>
</section>
<section id="bigView" class="popup hide">
    <div class="content">
        <header>
            <h5 id="bigViewName"></h5>
            <button id="closeBigView" class="button icon-button red"><i class="fa-solid fa-xmark"></i></button>
        </header>
        <main class="preview-content">
            <iframe id="expandedView" class="preview" src="" frameborder="0"></iframe>
        </main>
    </div>
</section>
<?php endif; ?>
