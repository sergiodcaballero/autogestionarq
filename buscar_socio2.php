<?php
// Configuración de la base de datos
$servidor = "arquitectosmisiones.org.ar";
$usuario = "arquite1_autog";
$password = "autogestionZ";
$baseDatos = "arquite1_autog";

// Configuración de paginación
$registrosPorPagina = 20;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Configuración de límite de facturas
$limiteFacturas = 3;

// Obtener término de búsqueda
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

try {
    // Conexión a la base de datos con charset UTF-8
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Establecer charset UTF-8 para la conexión
    $conexion->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Construir la consulta base con JOIN para filtrar por cantidad de facturas
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
        $sqlBase .= " AND (s.MATRICULA LIKE :busqueda 
                         OR s.NOMBRE LIKE :busqueda 
                         OR s.CELULAR LIKE :busqueda
                         OR s.EMAIL LIKE :busqueda)";
        $parametros[':busqueda'] = '%' . $busqueda . '%';
    }
    
    // Contar total de registros para paginación
    $sqlCount = "SELECT COUNT(*) " . $sqlBase;
    $stmtCount = $conexion->prepare($sqlCount);
    $stmtCount->execute($parametros);
    $totalRegistros = $stmtCount->fetchColumn();
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
    
    // Consulta principal con paginación
    $sql = "SELECT s.IDSOCIO, s.MATRICULA, s.NOMBRE, s.CELULAR, s.EMAIL " . $sqlBase . " 
            ORDER BY s.NOMBRE ASC 
            LIMIT :inicio, :limite";
    
    $stmt = $conexion->prepare($sql);
    
    // Vincular parámetros
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
    <title>Listado de Matriculados Activos - Colegio de Arquitectos de la Provincia de Misiones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .search-container {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .card-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(44, 62, 80, 0.1);
        }
        .pagination .page-link {
            color: #2c3e50;
        }
        .pagination .page-item.active .page-link {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
        .stats-card {
            border-left: 4px solid #2c3e50;
        }
        .main-title {
            font-size: 1.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .colegio-badge {
            background-color: #2c3e50;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: inline-block;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <!-- Header con buscador -->
        <div class="search-container">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center text-white mb-4">
                        <div class="colegio-badge">
                            <i class="fas fa-home-scale me-2"></i>Colegio de Arquitectos de la Provincia de Misiones
                        </div>
                        <h1 class="main-title">
                            <i class="fas fa-users me-2"></i>Listado de Matriculados Activos
                        </h1>
                        <p class="subtitle">Sistema de gestión y búsqueda de profesionales habilitados</p>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <form method="GET" action="" accept-charset="UTF-8">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       name="busqueda" 
                                       value="<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"
                                       placeholder="Buscar por matrícula, nombre, celular o email... (Solo socios con <?php echo $limiteFacturas; ?> facturas o menos)">
                                <button class="btn btn-light" type="submit">
                                    <i class="fas fa-search me-1"></i>Buscar
                                </button>
                                <?php if (!empty($busqueda)): ?>
                                    <a href="?" class="btn btn-outline-light">
                                        <i class="fas fa-times me-1"></i>Limpiar
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php else: ?>
                
                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Total de Matriculados</h5>
                                        <h2 class="text-primary"><?php echo number_format($totalRegistros); ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Página Actual</h5>
                                        <h2 class="text-info"><?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-file-alt fa-2x text-info opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title text-muted">Mostrando</h5>
                                        <h2 class="text-success"><?php echo count($socios); ?> registros</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-list fa-2x text-success opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de resultados -->
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            <?php if (!empty($busqueda)): ?>
                                Resultados para: "<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"
                            <?php else: ?>
                                Todos los Matriculados Activos
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($socios) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">
                                                <i class="fas fa-id-card me-1"></i>Matrícula
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-user me-1"></i>Nombre
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-phone me-1"></i>Celular
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-envelope me-1"></i>Email
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($socios as $socio): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary"><?php echo htmlspecialchars($socio['MATRICULA'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($socio['NOMBRE'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                                </td>
                                                <td>
                                                    <?php if (!empty($socio['CELULAR'])): ?>
                                                        <a href="tel:<?php echo htmlspecialchars($socio['CELULAR'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                           class="text-decoration-none">
                                                            <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($socio['CELULAR'], ENT_QUOTES, 'UTF-8'); ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted fst-italic">Sin celular</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($socio['EMAIL'])): ?>
                                                        <a href="mailto:<?php echo htmlspecialchars($socio['EMAIL'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                           class="text-decoration-none">
                                                            <?php echo htmlspecialchars($socio['EMAIL'], ENT_QUOTES, 'UTF-8'); ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted fst-italic">Sin email</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No se encontraron resultados</h4>
                                <p class="text-muted">
                                    <?php if (!empty($busqueda)): ?>
                                        No hay matriculados que coincidan con "<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php else: ?>
                                        No hay matriculados activos en la base de datos
                                    <?php endif; ?>
                                </p>
                                <?php if (!empty($busqueda)): ?>
                                    <a href="?" class="btn btn-primary">
                                        <i class="fas fa-arrow-left me-1"></i>Ver todos los matriculados
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Paginación -->
                <?php if ($totalPaginas > 1): ?>
                    <nav aria-label="Navegación de páginas" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <!-- Botón Anterior -->
                            <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" 
                                   href="?pagina=<?php echo $paginaActual - 1; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                                    <i class="fas fa-chevron-left"></i> Anterior
                                </a>
                            </li>

                            <!-- Números de página -->
                            <?php
                            $inicio_pag = max(1, $paginaActual - 2);
                            $fin_pag = min($totalPaginas, $paginaActual + 2);
                            
                            if ($inicio_pag > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?pagina=1' . (!empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : '') . '">1</a></li>';
                                if ($inicio_pag > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }
                            
                            for ($i = $inicio_pag; $i <= $fin_pag; $i++) {
                                $active = ($i == $paginaActual) ? 'active' : '';
                                echo '<li class="page-item ' . $active . '">';
                                echo '<a class="page-link" href="?pagina=' . $i . (!empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : '') . '">' . $i . '</a>';
                                echo '</li>';
                            }
                            
                            if ($fin_pag < $totalPaginas) {
                                if ($fin_pag < $totalPaginas - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="?pagina=' . $totalPaginas . (!empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : '') . '">' . $totalPaginas . '</a></li>';
                            }
                            ?>

                            <!-- Botón Siguiente -->
                            <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                                <a class="page-link" 
                                   href="?pagina=<?php echo $paginaActual + 1; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?>">
                                    Siguiente <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>