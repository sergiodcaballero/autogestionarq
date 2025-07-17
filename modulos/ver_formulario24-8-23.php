<?php
if (isset($_GET['p']) && isset($_GET['id'])) {
    $pagina = $_GET['p'];
    $id = escapar($_GET['id']);
    try {
        $r = $mysqli->query("SELECT t.*, f.nombre as nombre_formulario FROM tramites t inner join formularios f on t.for_id = f.id WHERE t.id = '$id' ");
        if ($r->num_rows == 0) {
            throw new Exception('Tramite erroneo.');
        }
        $tramite = $r->fetch_assoc();
        //echo var_dump($tramite);
        echo "<br>";
        echo $tramite['nombre_formulario'];
        echo "<br>";
        echo $tramite['fecha_realizado'];

        $r = $mysqli->query("SELECT td.*, fc.nombre as nombre_campo FROM tramites_detalle td inner join formularios_campos fc on fc.id = td.fc_id where td.tra_id = '$id' ");
        $detalles = $r->fetch_all(MYSQLI_ASSOC);
        foreach ($detalles as $det) {
            echo "<br>";
            if ($det['tipo_dato'] == 'file') {
                echo $det['nombre_campo'] . ': ' . $det['valor'];
                echo "<br>";
                echo '<a href="archivos_md5/'.$det['valor'].'" target="_blank">Ver Archivos</a>';
            } else {
                echo $det['nombre_campo'] . ': ' . $det['valor'];
            }
        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
