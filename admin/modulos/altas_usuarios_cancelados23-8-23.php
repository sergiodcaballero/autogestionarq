<?php
@session_start();
if (!isset($_SESSION) || !isset($_SESSION['usuario']) || !isset($_GET['p'])) {
    header('Location: login.php');
    exit();
}

//Acciones sobre el modulo
if (isset($_GET['cancelar']) && $_GET['cancelar'] == 'ok') {
    $id_tramite = escapar($_GET['id']);
    if ($mysqli->query("UPDATE tramites SET estado = -1 WHERE id = '$id_tramite' ")) {
        echo "<script> alert('Trámite cancelado correctamente'); window.location.href='index.php?p=altas_usuarios&t=3' </script>";
    }
}

if (isset($_GET['aceptar']) && $_GET['aceptar'] == 'ok') {
    $id_tramite = escapar($_GET['id']);
    if ($mysqli->query("UPDATE tramites SET estado = 1 WHERE id = '$id_tramite' ")) {
        echo "<script> alert('Trámite cancelado correctamente'); window.location.href='index.php?p=altas_usuarios&t=3' </script>";
    }
}
//fin de acciones sobre el modulo

//consultas para mostrar el modulo
$t = escapar($_GET['t']);

$r = $mysqli->query("SELECT t.* FROM tramites t WHERE t.for_id = '$t' and estado = -1 order by t.id desc");
$tramites = $r->fetch_all(MYSQLI_ASSOC);

?>
<div class="titulo-pagina">
    Usuarios Cancelados
</div>
<table class="tabla c-12">
    <thead>
        <th>#</th>
        <th>Fecha</th>
        <th>Archivo</th>
        <th>Estado</th>
    </thead>
    <tbody>
        <?php
        foreach ($tramites as $tramite) {
            $estado = '';
            if (is_null($tramite['estado'])) {
                $estado = '<span class="badge bg-precaucion">Pendiente</span>';
            }
            if ($tramite['estado'] == -1) {
                $estado = '<span class="badge bg-peligro">Cancelado</span>';
            }
            if ($tramite['estado'] == 1) {
                $estado = '<span class="badge bg-exito">Completo</span>';
            }
            $archivo = '';
            if ($tramite['archivo'] != '') {
                $archivo = $tramite['archivo'];
            }

        ?>
            <tr>
                <td><?php echo $tramite['id'] ?></td>
                <td><?php echo ffecha($tramite['fecha_realizado'], 'd/m/Y H:i:s') ?></td>
                <td>
                    <?php
                    if ($archivo != '') {
                    ?>
                        <a href="../archivos_md5/<?php echo $tramite['archivo'] ?>" target="_blank">Ver Archivo</a>
                    <?php
                    } else {
                        echo "Sin Archivo";
                    }
                    ?>
                </td>
                <td><?php echo $estado ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<script>
</script>