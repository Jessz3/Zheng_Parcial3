<?php

require_once "config/conexion.php"; // o donde tengas tu clase

try {
    $db = new Conexion();
    $conn = $db->getConexion();

    echo "✔ Conexión exitosa a la base de datos";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}