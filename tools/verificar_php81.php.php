<?php

/**
 * Sistema de Verificación de Compatibilidad PHP 8.1
 * 
 * Esta herramienta verifica si tu código PHP es compatible con PHP 8.1
 * y detecta posibles problemas de compatibilidad.
 */

class PHP81CompatibilityChecker
{
    private $issues = [];
    private $checkedFiles = 0;
    private $totalIssues = 0;

    public function __construct()
    {
        echo "=== Sistema de Verificación de Compatibilidad PHP 8.1 ===\n";
        echo "Versión actual de PHP: " . PHP_VERSION . "\n";
        echo "Verificando compatibilidad con PHP 8.1...\n\n";
    }

    /**
     * Verifica un directorio completo
     */
    public function checkDirectory($directory)
    {
        if (!is_dir($directory)) {
            echo "Error: El directorio '$directory' no existe.\n";
            return false;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->checkFile($file->getPathname());
            }
        }

        $this->displayResults();
        return true;
    }

    /**
     * Verifica un archivo específico
     */
    public function checkFile($filePath)
    {
        if (!file_exists($filePath)) {
            echo "Error: El archivo '$filePath' no existe.\n";
            return false;
        }

        $this->checkedFiles++;
        $content = file_get_contents($filePath);
        
        echo "Verificando: " . basename($filePath) . "\n";

        // Verificaciones de compatibilidad
        $this->checkDeprecatedFeatures($filePath, $content);
        $this->checkNewFeatures($filePath, $content);
        $this->checkSyntaxChanges($filePath, $content);
        $this->checkFunctionChanges($filePath, $content);
        
        return true;
    }

    /**
     * Verifica características deprecadas en PHP 8.1
     */
    private function checkDeprecatedFeatures($file, $content)
    {
        $deprecatedPatterns = [
            // Pasar null a funciones que no lo aceptan
            '/\b(strlen|array_key_exists|array_search)\s*\(\s*null\s*[,\)]/' => 'Pasar null a funciones string/array está deprecado',
            
            // Filtros de validación con flags no válidos
            '/FILTER_FLAG_SCHEME_REQUIRED|FILTER_FLAG_HOST_REQUIRED/' => 'Estos filtros están deprecados',
            
            // Funciones de fecha deprecadas
            '/\bstrftime\s*\(/' => 'strftime() está deprecado, usar IntlDateFormatter',
            '/\bgmstrftime\s*\(/' => 'gmstrftime() está deprecado, usar IntlDateFormatter',
            
            // Funciones de hash deprecadas
            '/\bmhash\s*\(/' => 'mhash() está deprecado, usar hash()',
            '/\bmhash_keygen_s2k\s*\(/' => 'mhash_keygen_s2k() está deprecado',
            
            // Serialización de objetos incompletos
            '/\b__PHP_Incomplete_Class\b/' => 'Serialización de objetos incompletos está deprecado',
            
            // Funciones de imagen deprecadas
            '/\bimagecreatefromjpeg2000\s*\(/' => 'imagecreatefromjpeg2000() está deprecado',
        ];

        foreach ($deprecatedPatterns as $pattern => $message) {
            if (preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                $this->addIssue($file, $message, $matches[0][1], 'DEPRECATED');
            }
        }
    }

    /**
     * Verifica nuevas características de PHP 8.1
     */
    private function checkNewFeatures($file, $content)
    {
        $newFeatures = [
            // Enumeraciones
            '/\benum\s+\w+/' => 'Usando enumeraciones (requiere PHP 8.1+)',
            
            // Propiedades readonly
            '/\breadonly\s+/' => 'Usando propiedades readonly (requiere PHP 8.1+)',
            
            // Intersección de tipos
            '/\w+\s*&\s*\w+/' => 'Posible intersección de tipos (requiere PHP 8.1+)',
            
            // Funciones nuevas
            '/\barray_is_list\s*\(/' => 'array_is_list() requiere PHP 8.1+',
            '/\bfsync\s*\(/' => 'fsync() requiere PHP 8.1+',
            '/\bfdatasync\s*\(/' => 'fdatasync() requiere PHP 8.1+',
        ];

        foreach ($newFeatures as $pattern => $message) {
            if (preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                $this->addIssue($file, $message, $matches[0][1], 'NEW_FEATURE');
            }
        }
    }

    /**
     * Verifica cambios de sintaxis
     */
    private function checkSyntaxChanges($file, $content)
    {
        $syntaxChanges = [
            // Pasar por referencia a funciones internas
            '/\barray_multisort\s*\(\s*[^,]+,\s*&/' => 'Pasar por referencia a array_multisort cambió',
            
            // Cambios en herencia
            '/\bfinal\s+const\s+/' => 'Constantes final requieren verificación',
            
            // Cambios en reflection
            '/\bReflectionClass::newInstance\s*\(/' => 'newInstance() sin argumentos cambió comportamiento',
        ];

        foreach ($syntaxChanges as $pattern => $message) {
            if (preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                $this->addIssue($file, $message, $matches[0][1], 'SYNTAX_CHANGE');
            }
        }
    }

    /**
     * Verifica cambios en funciones
     */
    private function checkFunctionChanges($file, $content)
    {
        $functionChanges = [
            // Cambios en openssl
            '/\bopenssl_cipher_iv_length\s*\(/' => 'Verificar comportamiento con cifrados inválidos',
            '/\bopenssl_encrypt\s*\(/' => 'Verificar manejo de IV en OpenSSL',
            
            // Cambios en mysqli
            '/\bmysqli_execute\s*\(/' => 'mysqli_execute() cambió comportamiento con parámetros',
            
            // Cambios en hash
            '/\bhash_hkdf\s*\(/' => 'hash_hkdf() cambió validación de parámetros',
            
            // Cambios en serialize
            '/\bunserialize\s*\(/' => 'unserialize() cambió comportamiento con clases',
        ];

        foreach ($functionChanges as $pattern => $message) {
            if (preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                $this->addIssue($file, $message, $matches[0][1], 'FUNCTION_CHANGE');
            }
        }
    }

    /**
     * Agrega un problema encontrado
     */
    private function addIssue($file, $message, $offset, $type)
    {
        $line = substr_count(file_get_contents($file), "\n", 0, $offset) + 1;
        
        $this->issues[] = [
            'file' => $file,
            'line' => $line,
            'message' => $message,
            'type' => $type
        ];
        
        $this->totalIssues++;
    }

    /**
     * Muestra los resultados de la verificación
     */
    private function displayResults()
    {
        echo "\n=== RESULTADOS DE LA VERIFICACIÓN ===\n";
        echo "Archivos verificados: {$this->checkedFiles}\n";
        echo "Problemas encontrados: {$this->totalIssues}\n\n";

        if (empty($this->issues)) {
            echo "✅ ¡Excelente! No se encontraron problemas de compatibilidad con PHP 8.1.\n";
            return;
        }

        // Agrupa por tipo
        $groupedIssues = [];
        foreach ($this->issues as $issue) {
            $groupedIssues[$issue['type']][] = $issue;
        }

        foreach ($groupedIssues as $type => $issues) {
            echo $this->getTypeHeader($type) . "\n";
            foreach ($issues as $issue) {
                echo "  📁 " . basename($issue['file']) . " (línea {$issue['line']})\n";
                echo "     {$issue['message']}\n\n";
            }
        }

        echo $this->getRecommendations();
    }

    /**
     * Obtiene el encabezado para cada tipo de problema
     */
    private function getTypeHeader($type)
    {
        switch ($type) {
            case 'DEPRECATED':
                return "⚠️  CARACTERÍSTICAS DEPRECADAS";
            case 'NEW_FEATURE':
                return "🆕 CARACTERÍSTICAS NUEVAS";
            case 'SYNTAX_CHANGE':
                return "🔄 CAMBIOS DE SINTAXIS";
            case 'FUNCTION_CHANGE':
                return "🔧 CAMBIOS EN FUNCIONES";
            default:
                return "❓ OTROS PROBLEMAS";
        }
    }

    /**
     * Obtiene recomendaciones basadas en los problemas encontrados
     */
    private function getRecommendations()
    {
        $recommendations = "📋 RECOMENDACIONES:\n\n";
        
        $recommendations .= "1. Actualizar composer.json:\n";
        $recommendations .= "   \"require\": {\n";
        $recommendations .= "       \"php\": \">=8.1\"\n";
        $recommendations .= "   }\n\n";
        
        $recommendations .= "2. Verificar dependencias:\n";
        $recommendations .= "   composer update --dry-run\n\n";
        
        $recommendations .= "3. Ejecutar pruebas:\n";
        $recommendations .= "   vendor/bin/phpunit\n\n";
        
        $recommendations .= "4. Herramientas adicionales:\n";
        $recommendations .= "   - PHPStan: composer require --dev phpstan/phpstan\n";
        $recommendations .= "   - PHP_CodeSniffer: composer require --dev squizlabs/php_codesniffer\n";
        $recommendations .= "   - Rector: composer require --dev rector/rector\n\n";
        
        return $recommendations;
    }

    /**
     * Verifica la configuración del servidor
     */
    public function checkServerConfiguration()
    {
        echo "=== VERIFICACIÓN DE CONFIGURACIÓN DEL SERVIDOR ===\n";
        
        // Verificar versión de PHP
        echo "Versión de PHP: " . PHP_VERSION . "\n";
        if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
            echo "✅ PHP 8.1+ está instalado\n";
        } else {
            echo "❌ Se requiere PHP 8.1 o superior\n";
        }
        
        // Verificar extensiones importantes
        $extensions = ['mbstring', 'xml', 'curl', 'json', 'openssl', 'mysqli'];
        echo "\nExtensiones verificadas:\n";
        foreach ($extensions as $ext) {
            if (extension_loaded($ext)) {
                echo "✅ $ext\n";
            } else {
                echo "❌ $ext (requerida)\n";
            }
        }
        
        // Verificar configuración importante
        echo "\nConfiguración PHP:\n";
        echo "memory_limit: " . ini_get('memory_limit') . "\n";
        echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
        echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
        echo "post_max_size: " . ini_get('post_max_size') . "\n";
        
        echo "\n";
    }
}

// Uso del sistema
if (php_sapi_name() === 'cli') {
    $checker = new PHP81CompatibilityChecker();
    
    // Verificar configuración del servidor
    $checker->checkServerConfiguration();
    
    // Verificar directorio del proyecto
    $projectDir = isset($argv[1]) ? $argv[1] : '.';
    
    if (is_dir($projectDir)) {
        $checker->checkDirectory($projectDir);
    } else {
        echo "Uso: php verificar_php81.php [directorio]\n";
        echo "Ejemplo: php verificar_php81.php /ruta/a/tu/proyecto\n";
    }
} else {
    echo "Esta herramienta debe ejecutarse desde la línea de comandos.\n";
    echo "Uso: php verificar_php81.php [directorio]\n";
}

?>