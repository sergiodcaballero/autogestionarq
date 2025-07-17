<?php

//declaro variable global

$opciones_antes_del_submit = array();



function imprimir_campos($form_id)
{
    global $mysqli;
    global $opciones_antes_del_submit;

    $r = $mysqli->query("SELECT * FROM formularios WHERE id = '$form_id' ");

    if ($r->num_rows == 0) {
        throw new Exception("Formulario erróneo.");
    }

    $form = $r->fetch_assoc();
?>

<div class="col-12 text-center titulo-formulario">
    <h4><?php echo $form['nombre']; ?></h4>
</div>

<?php
$form_campos = $mysqli->query("SELECT * FROM formularios_campos WHERE for_id = '{$form['id']}' ORDER BY orden ");
$campo = $form_campos->fetch_assoc(); // Obtiene un solo campo

while ($campo) {
    if ($campo['tipo_dato'] != 'form') {
        if ($campo['tipo_dato'] == 'option') {
            $opciones = explode("|", $campo['opciones']);
            ?>
            <div class="col-12">
                <label for="<?php echo "for" . $campo['id'] ?>"><?php echo $campo['nombre']; ?></label>
                <select name="<?php echo "campo" . $campo['id'] ?>" class="<?php echo $campo['estilo_html'] ?>"
                        id="<?php echo "for" . $campo['id'] ?>"
                        <?php echo ($campo['required'] == 1) ? 'required="true"' : ''; ?>>
                    <?php
                    foreach ($opciones as $opt) {
                        echo "<option value='" . $opt . "'>" . $opt . "</option>";
                    }
                    ?>
                </select>
            </div>
            <?php
        } else {
            $filtros = explode("|", $campo['filtro_extra']);
            $opciones = explode("|", $campo['opciones']);
            foreach ($filtros as $key => $filtro) {
                if ($filtro == 'fecha_max') {
                    $campo['max'] = date('Y-m-d', strtotime(date('Y-m-d') . " " . $opciones[$key]));
                } elseif ($filtro == 'validar_cuit') {
                    echo ' validaCuit(for' . $campo['id'] . '.value) ';
                    $opciones_antes_del_submit[] = ' validaCuit(for' . $campo['id'] . '.value); ';
                }
            }
            ?>
            <div class="col-12">
                <label for="<?php echo "for" . $campo['id'] ?>"><?php echo $campo['nombre']; ?></label>
                <input type="<?php echo $campo['tipo_dato'] ?>" name="<?php echo "campo" . $campo['id'] ?>"
                       class="<?php echo $campo['estilo_html'] ?>" id="<?php echo "for" . $campo['id'] ?>"
                       <?php echo (empty($campo['min'])) ? '' : 'min="' . $campo['min'] . '"'; ?>
                       <?php echo (empty($campo['accept'])) ? '' : 'accept="' . $campo['accept'] . '"'; ?>
                       <?php echo (empty($campo['max'])) ? '' : 'max="' . $campo['max'] . '"'; ?>
                       <?php echo ($campo['required'] == 1) ? 'required="true"' : ''; ?> />
            </div>
            <?php
        }
    } else {
        imprimir_campos($campo['id_formulario']);
    }

    $campo = $form_campos->fetch_assoc(); // Obtiene el siguiente campo
}
}



if (isset($_GET['p']) && isset($_GET['id'])) {

    $pagina = $_GET['p'];

    $id = escapar($_GET['id']);

    $r = $mysqli->query("SELECT * FROM formularios WHERE id = '$id' ");

    if ($r->num_rows == 0) {

        throw new Exception("Formulario erróneo.");

    }

    $form = $r->fetch_assoc();

    try {

        ?>

        <div class="row">

            <div class="col-12" style="margin-top:-2%;">

                <img src="imagenes/<?php echo $form['logo'] ?>" class="img-rounded">

            </div>

            <div class="col-12">

                <form id="formulario" method="post" action="home.php?p=guardar_datos" enctype="multipart/form-data">

                    <input type="hidden" name="form_id" value="<?php echo $id ?>" />

                    <div class="row">
                        
                        <?php imprimir_campos($id); ?>

                    </div>

                    <button type="submit" class="btn btn-md btn-primary mt-3">Enviar Datos del Formulario</button>

                </form>

            </div>

        </div>

        <script>

            let continuar_formulario = true;



            function logSubmit(event) {

                event.preventDefault();

                console.log(`Form Submitted! Time stamp: ${event.timeStamp}`);

                console.log(document.getElementsByTagName('data-control').value);

                <?php echo implode(" ", $opciones_antes_del_submit) ?>

                if (continuar_formulario) {

                    formulario.submit();

                } else {

                    return false;

                }

            }



            const form = document.getElementById('form');

            formulario.addEventListener('submit', logSubmit);



            function validaCuit(sCUIT) {

                var aMult = '5432765432';

                var aMult = aMult.split('');



                if (sCUIT && sCUIT.length == 11) {

                    aCUIT = sCUIT.split('');

                    var iResult = 0;

                    for (i = 0; i <= 9; i++) {

                        iResult += aCUIT[i] * aMult[i];

                    }

                    iResult = (iResult % 11);

                    iResult = 11 - iResult;



                    if (iResult == 11) iResult = 0;

                    if (iResult == 10) iResult = 9;



                    if (iResult == aCUIT[10]) {

                        continuar_formulario = true;

                        return true;

                    }

                }

                alert('Cuit Inválido');

                continuar_formulario = false;

                return false;

            }

        </script>

<?php



    } catch (Exception $e) {

        echo $e->getMessage();

    }

}

