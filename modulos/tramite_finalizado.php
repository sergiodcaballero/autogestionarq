<?php

if (isset($_GET) && $_GET['id'] != '') {

    $tra_id = escapar($_GET['id']);

    $c = "SELECT t.*, f.nombre as nombre_formulario FROM tramites t INNER JOIN formularios f ON t.for_id = f.id WHERE t.id = '$tra_id' ";

    $r = $mysqli->query($c);

    if ($r->num_rows > 0) {

        $tramite = $r->fetch_assoc();

        $r = $mysqli->query("SELECT td.*, fc.nombre as nombre_campo

                            FROM tramites_detalle td 

                            LEFT JOIN formularios_campos fc on fc.id = td.fc_id 

                            WHERE td.tipo_dato = 'file' AND td.tra_id = '{$tramite['id']}' ");

                $archivos_asociados = array();

                while ($row = $r->fetch_assoc()) {
                    $archivos_asociados[] = $row;
                }

?>

        <div class="row">

            <div class="col-12 text-center titulo-formulario">

                <h3>Trámite Finalizado</h3>

            </div>

            <div class="col-12">

                <table class="table table-sm table-hovered">

                    <thead>

                        <th>Nro. Tramite</th>

                        <th>Nombre</th>

                        <th>Opciones</th>

                    </thead>

                    <tbody>

                        <tr>

                            <td><?php echo $tramite['id'] ?></td>

                            <td><?php echo $tramite['nombre_formulario'] ?></td>

                            <td>

                                <?php

                                if ($tramite['archivo'] != '') {

                                ?>

                                    <a href="archivos_md5/<?php echo $tramite['archivo'] ?>" target="_blank" class="badge bg-info text-light">Ver archivo</a>

                                    <button onclick="$('#cabecera-md5-<?php echo $tramite['id'] ?>').toggle('fast')" class="badge bg-info text-light">Ver Firma MD5</button>

                                <?php

                                } else {

                                    echo "Sin archivo.";

                                }

                                ?>

                            </td>

                        </tr>

                        <tr id="cabecera-md5-<?php echo $tramite['id'] ?>" class="md5">

                            <td colspan="3"><strong>MD5:</strong> <?php echo $tramite['archivo_md5'] ?></td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <?php

            if (count($archivos_asociados) > 0) {

            ?>

                <div class="col-12 text-center titulo-formulario">

                    <h3>Archivos Adjuntos</h3>

                </div>

                <div class="col-12">

                    <table class="table table-sm table-hovered">

                        <thead>

                            <th>Nro. Tramite</th>

                            <th>Nombre</th>

                            <th>Opciones</th>

                        </thead>

                        <tbody>

                            <?php

                            while ($archivo = $r->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?php echo $archivo['id'] ?></td>
                                        <td><?php echo $archivo['nombre_campo'] ?></td>
                                        <td>
                                            <a href="archivos_md5/<?php echo $archivo['valor'] ?>" target="_blank" class="badge bg-info text-light">Ver archivo</a>
                                            <button onclick="$('#md5-<?php echo $archivo['id'] ?>').toggle('fast')" class="badge bg-info text-light">Ver Firma MD5</button>
                                        </td>
                                    </tr>
                                    <tr id="md5-<?php echo $archivo['id'] ?>" class="md5">
                                        <td colspan="3"><strong>MD5:</strong> <?php echo $archivo['md5'] ?></td>
                                    </tr>
                                <?php
                                }
                                ?>

                        </tbody>

                    </table>

                </div>

            <?php

            }

            ?>

        </div>

        <script>

            $(document).ready(() => {

                $('.md5').hide();

            })

        </script>

<?php

    }

}

