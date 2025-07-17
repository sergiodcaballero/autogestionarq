<ul class="nav nav-list bs-docs-sidenav">
    <li class="active">
        <a href="principal.php"><i class="icon-home"></i><i class="icon-chevron-right"></i> Inicio</a>
    </li>
    <li><a onClick="conf_salida();" href="#"><i class="icon-remove "></i> Salir</a></li>
    <li class="nav-head"><a style=" color:#999;"><strong>ESTADO DE DEUDA</strong></a> </li>
    <li><a href="deuda_total.php" class="orden"><i class="icon-chevron-right"></i>Total</a></li>
    <li><a href="deuda_detallada.php"><i class="icon-chevron-right"></i>Detallada</a></li>
    <li class="nav-head">
        <a style=" color:#999;">
            <strong>CERTIFICADOS</strong>
        </a>
    </li>
    <li>
        <a href="certificado_libre_deuda.php" class="orden">
            <i class="icon-chevron-right"></i>Matr&iacute;cula / Libre Deuda
        </a>
    </li>
    <li><a href="calculo_tasas.php" class="orden">
            <i class="icon-chevron-right"></i>C&aacute;lculo de Tasas
        </a>
    </li>

    <li class="nav-head">
        <a style=" color:#999;">
            <strong>
                PAGO DE CUOTAS
            </strong>
        </a>
    </li>
    <?php if (HABILITAR_PAGOS_MACRO) { ?>
        <li>
            <a class="orden" href="deuda_detallada_nueva.php">
                <i class="icon-chevron-right">
                </i>
                Realizar nuevo pago
            </a>
        </li>
        <li>
            <a class="orden" href="home.php?p=pagos_realizados">
                <i class="icon-chevron-right">
                </i>
                Pagos Realizados
            </a>
        </li>
    <?php } ?>
    <li class="nav-head"><a style=" color:#999;"><strong>SIMULADOR DE CALCULO DE HONORARIOS</strong></a> </li>
    <li>
        <a href="http://www.arquitectosmisiones.org.ar/calculo/calculo.php" class="orden">
            <i class="icon-chevron-right"></i>Simulador de C&aacute;lculo de Honorarios
        </a>
    </li>
    <li class="nav-head"><a style=" color:#999;"><strong>MIS DATOS</strong></a> </li>
    <li>
        <a href="http://www.arquitectosmisiones.org.ar/autogestion/home.php?p=formulario&id=2" class="orden">
            <i class="icon-chevron-right"></i>Cargar Título</a>
    </li>
    <li>
        <a href="actualizar_datos.php" class="orden"><i class="icon-chevron-right"></i>Actualizar Datos</a>
    </li>
    <li><a href="modificar_pass.php"><i class="icon-chevron-right"></i>Modificar Contrase&ntilde;a</a></li>
</ul>