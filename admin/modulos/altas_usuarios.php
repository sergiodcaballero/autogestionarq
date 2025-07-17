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



$r = $mysqli->query("SELECT t.* FROM tramites t WHERE t.for_id = '$t' and isnull(estado) ");



?>

<div class="titulo-pagina">

    <?php echo "Usuarios Pendientes" ?>

</div>

<table class="tabla c-12">

    <thead>

        <th>#</th>

        <th>Fecha</th>

        <th>Nombre</th>

        <th>Archivo</th>

        <th>Estado</th>

        <th>Opciones</th>

    </thead>

    <tbody>

        <?php

            while ($tramite = $r->fetch_assoc()) {

            //OBTENGO DATOS EXTRAS POR FORMULARIO

            $apellido_result = $mysqli->query("SELECT * FROM tramites_detalle WHERE tra_id='{$tramite['id']}' AND fc_id = 1");
            $nombre_result = $mysqli->query("SELECT * FROM tramites_detalle WHERE tra_id='{$tramite['id']}' AND fc_id = 2");
            $correo_result = $mysqli->query("SELECT * FROM tramites_detalle WHERE tra_id='{$tramite['id']}' AND fc_id = 20");

            $apellido_row = $apellido_result->fetch_assoc();
            $nombre_row = $nombre_result->fetch_assoc();
            $correo_row = $correo_result->fetch_assoc();

            $apellido = $apellido_row['valor'];
            $nombre = $nombre_row['valor'];
            $correo = $correo_row['valor'];



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

                <td><?php echo $apellido . ', ' . $nombre ?></td>

                <td>

                    <?php

                    if ($archivo != '') {

                    ?>

                        <a class="boton boton-info" href="../archivos_md5/<?php echo $tramite['archivo'] ?>" target="_blank">Ver Archivo</a>

                    <?php

                    } else {

                        echo "Sin Archivo";

                    }

                    ?>

                </td>

                <td><?php echo $estado ?></td>

                <td>

                    <a onclick="finalizar_tramite(1,'<?php echo $tramite['id'] ?>', 'Desea ACEPTAR el trámite?', '<?php echo $correo ?>')" class="boton boton-aceptar">Aceptar</a>

                    <a onclick="finalizar_tramite(2,'<?php echo $tramite['id'] ?>', 'Desea CANCELAR el trámite?', '<?php echo $correo ?>')" class="boton boton-cancelar">Cancelar</a>

                </td>

            </tr>

        <?php

        }

        ?>

    </tbody>

</table>

<!-- MODAL MENSAJE -->

<div style="display: none; width: 400px;" id="hidden-content">

    <div class="grid">

        <div class="c-12 modal-titulo">

            <label for="" id="modal-titulo">Titulo de la ventana Modal</label>

            <hr>

        </div>

        <div class="c-12 modal-contenido">

            <input type="hidden" id="aceptar" value="NO" />

            <input type="hidden" id="id_tramite" value="0" />

            <input type="hidden" id="texto_confirmar" name="confirmar" value="Está seguro?" />

            <label for="tramite_correo">Correo</label>

            <input id="tramite_correo" type="email" class="f-item" value="" />

            <label for="mensaje">Mensaje a Enviar</label>

            <textarea name="mensaje" id="mensaje" class="f-item" cols="30" rows="10" placeholder="Escriba aqui un mensaje"></textarea>

        </div>

        <div class="c-12 modal-botones">

            <button id="enviar_tramite" class="boton boton-info" onclick="enviar_tramite(aceptar.value, id_tramite.value, tramite_correo.value, mensaje.value, texto_confirmar.value)">Enviar</button>

            <a href="#" class="boton " onclick="cerrar_modal()">Salir</a>

        </div>

    </div>

</div>

<script>

    let modal;



    const enviar_tramite = (tipo, id, correo, mensaje, texto) => {

        console.log(tipo, id, correo, mensaje, texto);

        if (!validar_correo(correo)) {

            alert('Debe ingresar un correo válido.');

            return false;

        }

        if (mensaje == '') {

            alert('Debe ingresar un mensaje.');

            return false;

        }

        if (confirm(texto)) {

            let funcion = '';

            $('#enviar_tramite').attr('disabled', true);

            if (tipo == 'SI') {

                funcion = 'aceptar_usuario';

            } else if (tipo == 'NO') {

                funcion = 'cancelar_usuario';

            }



            var parametros = {

                "id": id,

                "correo": correo,

                "mensaje": mensaje

            };

            $.ajax({

                data: parametros,

                url: 'funciones/enviar_correos.php?f=' + funcion,

                type: 'post',

                beforeSend: function() {

                    console.log('Procesando..');

                },

                success: function(response) {

                    console.log(response);

                    var res = JSON.parse(response);

                    if (res == 1) {

                        alert('Trámite finalizado correctamente');

                        window.location.href = 'index.php?p=altas_usuarios&t=1';

                    } else {

                        alert('Error inesperado, intente nuevamente');

                    }

                    $('#enviar_tramite').attr('disabled', false);

                }

            });

        }

    }



    const finalizar_tramite = (tipo, id, mensaje, correo) => {

        if (tipo == 1) {

            $('#aceptar').val('SI');

            $('#modal-titulo').html('Aceptar Trámite #' + id);

        }

        if (tipo == 2) {

            $('#aceptar').val('NO');

            $('#modal-titulo').html('Cancelar Trámite #' + id);

        }

        $('#texto_confirmar').val(mensaje);

        $('#id_tramite').val(id);

        $('#tramite_correo').val(correo);

        modal = $.fancybox.open({

            src: '#hidden-content',

            type: 'inline',

            opts: {

                modal: false,

                afterShow: function(instance, current) {

                    $('#mensaje').focus();

                    console.info('done!');

                }

            }

        });

    }



    const cerrar_modal = () => {

        modal.close();

    }

</script>