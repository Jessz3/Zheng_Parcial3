<?php
/**
 * Script para crear las tablas necesarias en la base de datos
 * Ejecutar en: http://localhost/DesarrolloVII/Zheng_ParcialRRHH/crear_bd.php
 */

require_once "config/Conexion.php";

$conn = (new Conexion())->getConexion();

$sql = [
    // Tabla de Sexo
    "CREATE TABLE IF NOT EXISTS `cat_sexo` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nombre` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    // Insertar datos de sexo
    "INSERT IGNORE INTO `cat_sexo` (`id`, `nombre`) VALUES 
    (2, 'Hombre'),
    (3, 'Mujer')",

    // Tabla de Tipo de Sangre
    "CREATE TABLE IF NOT EXISTS `cat_tiposangre` (
        `id` int NOT NULL AUTO_INCREMENT,
        `Nombre` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    // Insertar datos de tipo de sangre
    "INSERT IGNORE INTO `cat_tiposangre` (`id`, `Nombre`) VALUES 
    (1, 'O+'),
    (2, 'O-'),
    (3, 'A-'),
    (4, 'A+'),
    (5, 'B-'),
    (6, 'B+'),
    (7, 'AB-'),
    (8, 'AB+')",

    // Tabla de Rutas
    "CREATE TABLE IF NOT EXISTS `cat_rutas` (
        `id` int NOT NULL AUTO_INCREMENT,
        `Nombre` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    // Insertar datos de rutas
    "INSERT IGNORE INTO `cat_rutas` (`id`, `Nombre`) VALUES 
    (1, 'Panamá Este'),
    (2, 'Panamá Oeste'),
    (3, 'Panamá Norte'),
    (4, 'Panamá Centro')",

    // Tabla de Estado Civil
    "CREATE TABLE IF NOT EXISTS `cat_estadocivil` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nombre` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    // Insertar datos de estado civil
    "INSERT IGNORE INTO `cat_estadocivil` (`id`, `nombre`) VALUES 
    (1, 'Seleccionar'),
    (2, 'Soltero (a)'),
    (3, 'Casado (a)'),
    (4, 'Divorciado (a)'),
    (5, 'Viudo (a)'),
    (6, 'Unido (a)')",

    // Tabla de Ocupaciones (Simplificada para pruebas)
    "CREATE TABLE IF NOT EXISTS `cat_ocupaciones` (
        `C_OCUP` int NOT NULL AUTO_INCREMENT,
        `OCUPACION` varchar(50) DEFAULT NULL,
        `Activo` int NOT NULL DEFAULT '1',
        PRIMARY KEY (`C_OCUP`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci",

    // Insertar datos de ocupaciones básicas
    "INSERT IGNORE INTO `cat_ocupaciones` (`C_OCUP`, `OCUPACION`, `Activo`) VALUES 
    (1, 'ABOGADO I', 1),
    (2, 'ADMINISTRADOR', 1),
    (3, 'AGENTE DE SEGURIDAD', 1),
    (4, 'ALBAÑIL', 1),
    (5, 'ALMACENISTA I', 1),
    (6, 'ANALISTA DE SISTEMAS', 1),
    (7, 'ARQUITECTO', 1),
    (8, 'ASESOR LEGAL', 1),
    (9, 'ASISTENTE ADMINISTRATIVO I', 1),
    (10, 'AUDITOR', 1)"
];

$exitos = 0;
$errores = 0;

echo "<style>
    body { font-family: Arial; margin: 20px; background: #f5f5f5; }
    .exito { color: #155724; margin: 8px 0; }
    .error { color: #721c24; margin: 8px 0; }
    .contenedor { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { border-bottom: 3px solid #007bff; padding-bottom: 10px; color: #333; }
    .resumen { margin-top: 20px; padding: 15px; background: #f0f0f0; border-radius: 5px; }
</style>";

echo "<div class='contenedor'>";
echo "<h2>📊 Verificación de Tablas de Base de Datos</h2>";

foreach ($sql as $index => $query) {
    try {
        $conn->exec($query);
        echo "<p class='exito'>✓ Query " . ($index + 1) . " ejecutada correctamente</p>";
        $exitos++;
    } catch (PDOException $e) {
        // Algunos errores son normales (tablas ya existen), así que no los contamos como errores
        $mensajeError = $e->getMessage();
        if (strpos($mensajeError, 'already exists') !== false) {
            echo "<p class='exito'>✓ Query " . ($index + 1) . " - Tabla ya existe (OK)</p>";
            $exitos++;
        } else {
            echo "<p class='error'>✗ Query " . ($index + 1) . " - " . htmlspecialchars($mensajeError) . "</p>";
            $errores++;
        }
    }
}

echo "<div class='resumen'>";
echo "<h3>📈 Resumen</h3>";
echo "<p>✓ Exitosas: <strong style='color: green;'>$exitos</strong></p>";
echo "<p>✗ Con errores: <strong style='color: red;'>$errores</strong></p>";

// Verificar que las tablas existan
echo "<h3>🔍 Verificación de Tablas</h3>";
$tablas = ['cat_sexo', 'cat_tiposangre', 'cat_rutas', 'cat_estadocivil', 'cat_ocupaciones', 'colaboradores'];
foreach ($tablas as $tabla) {
    try {
        $result = $conn->query("SELECT COUNT(*) as count FROM `$tabla` LIMIT 1");
        $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
        echo "<p class='exito'>✓ Tabla '$tabla' existe y tiene $count registros</p>";
    } catch (PDOException $e) {
        echo "<p class='error'>✗ Tabla '$tabla' NO existe o hay problema</p>";
    }
}

if ($errores == 0) {
    echo "</div>";
    echo "<p style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724; border-left: 5px solid #28a745; margin-top: 20px;'>
        <strong>✓ Sistema listo!</strong><br>
        Ahora puedes <a href='Index.php?accion=listar' style='color: #155724; font-weight: bold;'>acceder al sistema</a>
    </p>";
} else {
    echo "</div>";
    echo "<p style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24; border-left: 5px solid #dc3545;'>
        <strong>⚠ Revisa los errores arriba</strong>
    </p>";
}

echo "</div>";
?>
