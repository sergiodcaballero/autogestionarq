<?php
function imprimir_campos($form_id)
{
    global $mysqli;
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
    $campos = $form_campos->fetch_all(MYSQLI_ASSOC);
    foreach ($campos as $value) {
        if ($value['tipo_dato'] != 'form') {
    ?>
            <div class="col-12">
                <label for="<?php echo "for" . $value['id'] ?>"><?php echo $value['nombre']; ?></label>
                <input type="<?php echo $value['tipo_dato'] ?>" name="<?php echo "campo" . $value['id'] ?>" class="<?php echo $value['estilo_html'] ?>" id="<?php echo "for" . $value['id'] ?>" <?php echo (empty($value['min'])) ? '' : 'min="' . $value['min'] . '"'; ?> <?php echo (empty($value['accept'])) ? '' : 'accept="' . $value['accept'] . '"'; ?> <?php echo (empty($value['max'])) ? '' : 'max="' . $value['max'] . '"'; ?> <?php echo ($value['required'] == 1) ? 'required="true"' : ''; ?> />
            </div>
        <?php
        } else {
            imprimir_campos($value['id_formulario']);
        }
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
                <form method="post" action="home.php?p=guardar_datos" enctype="multipart/form-data">
                    <input type="hidden" name="form_id" value="<?php echo $id ?>" />
                    <div class="row">
                        <?php imprimir_campos($id); ?>
                    </div>
                    <button type="submit" class="btn btn-md btn-primary mt-3">Enviar Datos del Formulario</button>
                </form>
            </div>
        </div>
<?php

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
