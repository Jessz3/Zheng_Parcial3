<?php
/**
 * Script para corregir la tabla cat_sexo
 * Ejecutar en: http://localhost/DesarrolloVII/Zheng_ParcialRRHH/reparar_bd.php
 */

require_once "config/Conexion.php";

$conn = (new Conexion())->getConexion();

echo "<style>
    body { font-family: Arial; margin: 20px; background: #f5f5f5; }
    .exito { color: #155724; margin: 8px 0; }
    .error { color: #721c24; margin: 8px 0; }
    .contenedor { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { border-bottom: 3px solid #007bff; padding-bottom: 10px; color: #333; }
</style>";

echo "<div class='contenedor'>";
echo "<h2>🔧 Reparación de Base de Datos</h2>";

// Paso 1: Eliminar la tabla cat_sexo con espacio si existe
try {
    $conn->exec("DROP TABLE IF EXISTS ` cat_sexo`");
    echo "<p class='exito'>✓ Tabla ` cat_sexo` (con espacio) eliminada</p>";
} catch (PDOException $e) {
    echo "<p class='exito'>✓ No había tabla con espacio</p>";
}

// Paso 2: Crear tabla cat_sexo correctamente (sin espacio)
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS `cat_sexo` (
        `id` int NOT NULL AUTO_INCREMENT,
        `nombre` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci");
    echo "<p class='exito'>✓ Tabla `cat_sexo` creada correctamente</p>";
} catch (PDOException $e) {
    echo "<p class='error'>✗ Error al crear tabla: " . $e->getMessage() . "</p>";
}

// Paso 3: Insertar datos
try {
    $conn->exec("INSERT INTO `cat_sexo` (`id`, `nombre`) VALUES 
    (2, 'Hombre'),
    (3, 'Mujer')");
    echo "<p class='exito'>✓ Datos de sexo insertados</p>";
} catch (PDOException $e) {
    echo "<p class='error'>✗ Error al insertar datos: " . $e->getMessage() . "</p>";
}

// Verificación final
echo "<h3>Verificación Final</h3>";
try {
    $result = $conn->query("SELECT COUNT(*) as count FROM `cat_sexo`");
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p class='exito'>✓ Tabla `cat_sexo` ahora tiene $count registros</p>";
} catch (PDOException $e) {
    echo "<p class='error'>✗ No se puede verificar: " . $e->getMessage() . "</p>";
}

echo "<p style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724; border-left: 5px solid #28a745; margin-top: 20px;'>
    <strong>✓ Base de datos reparada!</strong><br>
    Ahora puedes <a href='Index.php?accion=nuevo' style='color: #155724; font-weight: bold;'>crear un nuevo colaborador</a>
</p>";

echo "</div>";
?>
