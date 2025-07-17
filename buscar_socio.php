<?php
// Configuración de la base de datos
$servidor = "localhost"; // Es buena práctica usar localhost si está en el mismo servidor
$usuario = "arquite1_autog";
$password = "autogestionZ";
$baseDatos = "arquite1_autog";

// Configuración de paginación
$registrosPorPagina = 20;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Configuración de límite de facturas (se mantiene la lógica)
$limiteFacturas = 2;

// Obtener término de búsqueda
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

try {
    // Conexión a la base de datos con PDO
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // --- LÓGICA DE BÚSQUEDA ACTUALIZADA ---
    // Se eliminó la búsqueda por CELULAR y EMAIL
    $sqlBase = "FROM socios s 
                LEFT JOIN (
                    SELECT IDSOCIO, COUNT(*) as total_facturas 
                    FROM factura 
                    WHERE ESTADO = '' 
                    GROUP BY IDSOCIO
                ) f ON s.IDSOCIO = f.IDSOCIO 
                WHERE s.BAJA = 'NO' 
                AND (f.total_facturas IS NULL OR f.total_facturas <= :limite_facturas)";
    
    $parametros = [':limite_facturas' => $limiteFacturas];
    
    // Agregar filtro de búsqueda si existe
    if (!empty($busqueda)) {
        $sqlBase .= " AND (s.MATRICULA LIKE :busqueda OR s.NOMBRE LIKE :busqueda)";
        $parametros[':busqueda'] = '%' . $busqueda . '%';
    }
    
    // Contar total de registros para paginación
    $sqlCount = "SELECT COUNT(*) " . $sqlBase;
    $stmtCount = $conexion->prepare($sqlCount);
    $stmtCount->execute($parametros);
    $totalRegistros = $stmtCount->fetchColumn();
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
    
    // --- CONSULTA PRINCIPAL ACTUALIZADA ---
    // Se eliminó s.CELULAR y s.EMAIL de los campos seleccionados
    $sql = "SELECT s.IDSOCIO, s.MATRICULA, s.NOMBRE " . $sqlBase . " 
            ORDER BY s.NOMBRE ASC 
            LIMIT :inicio, :limite";
    
    $stmt = $conexion->prepare($sql);
    
    foreach ($parametros as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $registrosPorPagina, PDO::PARAM_INT);
    
    $stmt->execute();
    $socios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Error de conexión: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Matriculados - Colegio de Arquitectos de la Provincia de Misiones</title>
    <!-- Se mantiene Font Awesome para los íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style type="text/css">
        /* --- ESTILO VISUAL UNIFICADO (similar a calculo.php) --- */
        body { font-family: Verdana, Geneva, sans-serif; line-height: 1.6; background-color: #f4f4f4; color: #333; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #005a9c; }
        p { margin-top: 0; }
        
        /* Formulario de búsqueda */
        .search-form { display: flex; gap: 10px; margin-bottom: 20px; }
        .search-form input[type="text"] { flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .search-form button, .search-form a {
            background-color: #005a9c; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
        }
        .search-form a { background-color: #c82333; }

        /* Tabla de resultados */
        .table-header { padding: 10px 0; color: #555; font-size: 0.9em; }
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #e9ecef; font-weight: bold; }
        tbody tr:hover { background-color: #f5f5f5; }
        .badge {
            display: inline-block; padding: 0.35em 0.65em; font-size: .75em; font-weight: 700; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: .25rem; background-color: #005a9c;
        }
        .no-results { text-align: center; padding: 40px; color: #777; }

        /* Paginación */
        .pagination { display: flex; justify-content: center; list-style: none; padding: 0; margin-top: 20px; }
        .pagination li a { color: #005a9c; padding: 8px 16px; text-decoration: none; border: 1px solid #ddd; margin: 0 2px; }
        .pagination li.active a { background-color: #005a9c; color: white; border-color: #005a9c; }
        .pagination li.disabled a { color: #ccc; cursor: not-allowed; }
        .pagination li a:hover:not(.disabled) { background-color: #ddd; }

    </style>
</head>
<body>

    <div class="container">
        <p align="center"><img src="imagenes/logo.gif" id="logo_img" width="488" height="101" alt="Logo Colegio de Arquitectos" /></p>
        <h1>Listado de Matriculados Activos</h1>

        <form method="GET" action="" class="search-form">
            <input type="text" 
                   name="busqueda" 
                   value="<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="Buscar por matrícula o nombre...">
            <button type="submit"><i class="fas fa-search"></i> Buscar</button>
            <?php if (!empty($busqueda)): ?>
                <a href="?"><i class="fas fa-times"></i> Limpiar</a>
            <?php endif; ?>
        </form>

        <?php if (isset($error)): ?>
            <div class="no-results"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php else: ?>
            
            <!-- **NUEVO**: Información de registros -->
            <div class="table-header">
                <p>Mostrando <?php echo count($socios); ?> de <?php echo $totalRegistros; ?> registros totales.</p>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-id-card me-1"></i> Matrícula</th>
                            <th><i class="fas fa-user me-1"></i> Nombre y Apellido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($socios) > 0): ?>
                            <?php foreach ($socios as $socio): ?>
                                <tr>
                                    <td><span class="badge"><?php echo htmlspecialchars($socio['MATRICULA'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td><strong><?php echo htmlspecialchars($socio['NOMBRE'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="no-results">
                                    <h4>No se encontraron resultados</h4>
                                    <p>
                                        <?php if (!empty($busqueda)): ?>
                                            Ningún matriculado coincide con "<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"
                                        <?php else: ?>
                                            No hay matriculados para mostrar.
                                        <?php endif; ?>
                                    </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- **PAGINACIÓN MEJORADA** -->
            <?php if ($totalPaginas > 1): ?>
                <nav>
                    <ul class="pagination">
                        <!-- Botón Anterior -->
                        <li class="<?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                            <a href="?pagina=<?php echo $paginaActual - 1; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?>">Anterior</a>
                        </li>

                        <!-- Números de página -->
                        <?php
                        $rango = 2;
                        $inicio_loop = max(1, $paginaActual - $rango);
                        $fin_loop = min($totalPaginas, $paginaActual + $rango);

                        if ($inicio_loop > 1) {
                            echo '<li><a href="?pagina=1' . (!empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : '') . '">1</a></li>';
                            if ($inicio_loop > 2) {
                                echo '<li class="disabled"><a>...</a></li>';
                            }
                        }

                        for ($i = $inicio_loop; $i <= $fin_loop; $i++): ?>
                            <li class="<?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                                <a href="?pagina=<?php echo $i; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor;

                        if ($fin_loop < $totalPaginas) {
                            if ($fin_loop < $totalPaginas - 1) {
                                echo '<li class="disabled"><a>...</a></li>';
                            }
                            echo '<li><a href="?pagina=' . $totalPaginas . (!empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : '') . '">' . $totalPaginas . '</a></li>';
                        }
                        ?>

                        <!-- Botón Siguiente -->
                        <li class="<?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                            <a href="?pagina=<?php echo $paginaActual + 1; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</body>
</html>