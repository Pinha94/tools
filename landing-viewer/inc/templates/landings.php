<?php 

if (!empty($_POST)) :
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
    $baseUrl .= ($ambiente == '') ? '' : $ambiente . '.';
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
        <div class="viewer">
            <div class="preview-content">
                <iframe
                    class="preview"
                    src="<?php echo $fullUrl ?>"
                    frameborder="0">
                </iframe>
            </div>
            <label for="checked<?php echo $i; ?>" class="check-content">
                <input class="checkbox" type="checkbox" name="checked" id="checked<?php echo $i; ?>">
                <span>Revisado</span>
            </label>
            <a href="<?php echo $fullUrl ?>" target="_blank" class="button">ver <?php echo $name; ?></a>
        </div>
        <?php 
            endforeach; 
            endif;
        ?>
    </div>
</section>
<?php endif; ?>
