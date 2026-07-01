<?php
/**
 * Script de diagnóstico - Verifica que todo está funcionando correctamente
 * Accede en: http://localhost/DesarrolloVII/Zheng_ParcialRRHH/test.php
 */

echo "<h2>Diagnóstico del Sistema</h2>";

// 1. Verificar conexión a BD
echo "<h3>1. Conexión a Base de Datos</h3>";
try {
    require_once "config/Conexion.php";
    $conn = (new Conexion())->getConexion();
    echo "✓ Conexión exitosa a la BD<br>";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
    exit;
}

// 2. Verificar tablas necesarias
echo "<h3>2. Tablas Necesarias</h3>";
$tablas_necesarias = [
    'colaboradores',
    'cat_sexo',
    'cat_tiposangre',
    'cat_rutas',
    'cat_ocupaciones',
    'cat_estadocivil'
];

foreach ($tablas_necesarias as $tabla) {
    try {
        $result = $conn->query("SHOW TABLES LIKE '$tabla'");
        if ($result->rowCount() > 0) {
            echo "✓ Tabla '$tabla' existe<br>";
        } else {
            echo "✗ Tabla '$tabla' NO existe<br>";
        }
    } catch (Exception $e) {
        echo "✗ Error verificando '$tabla': " . $e->getMessage() . "<br>";
    }
}

// 3. Verificar clases
echo "<h3>3. Clases Necesarias</h3>";
$clases = [
    "Sanitizacion" => "Utilidades/Sanitizacion.php",
    "Validaciones" => "Utilidades/Validaciones.php",
    "Colaborador" => "Modelo/Colaborador.php",
    "CatalogoModel" => "Modelo/CatalogoModel.php",
    "ColaboradorController" => "Controlador/ColaboradorController.php"
];

foreach ($clases as $clase => $archivo) {
    if (file_exists($archivo)) {
        echo "✓ Archivo '$archivo' existe<br>";
    } else {
        echo "✗ Archivo '$archivo' NO existe<br>";
    }
}

// 4. Verificar permisos de escritura
echo "<h3>4. Permisos de Escritura</h3>";
$directorios = [
    "Vista",
    "Controlador",
    "Modelo",
    "Utilidades",
    "config",
    "css"
];

foreach ($directorios as $dir) {
    if (is_writable($dir)) {
        echo "✓ Directorio '$dir' es escribible<br>";
    } else {
        echo "⚠ Directorio '$dir' NO es escribible<br>";
    }
}

// 5. Probar catálogos
echo "<h3>5. Catálogos en BD</h3>";
try {
    require_once "Modelo/CatalogoModel.php";
    $cat = new CatalogoModel($conn);
    
    $sexo = $cat->getSexo();
    echo "✓ Catálogo Sexo: " . count($sexo) . " registros<br>";
    
    $sangre = $cat->getSangre();
    echo "✓ Catálogo Sangre: " . count($sangre) . " registros<br>";
    
    $rutas = $cat->getRutas();
    echo "✓ Catálogo Rutas: " . count($rutas) . " registros<br>";
    
    $ocupaciones = $cat->getOcupaciones();
    echo "✓ Catálogo Ocupaciones: " . count($ocupaciones) . " registros<br>";
    
    $estado_civil = $cat->getEstadoCivil();
    echo "✓ Catálogo Estado Civil: " . count($estado_civil) . " registros<br>";
    
} catch (Exception $e) {
    echo "✗ Error al cargar catálogos: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<p><a href='Index.php?accion=listar'>Ir al Sistema Principal</a></p>";
?>
