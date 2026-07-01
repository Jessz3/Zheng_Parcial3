<?php

require_once "config/Conexion.php";
require_once "Controlador/ColaboradorController.php";
require_once "Utilidades/Sanitizacion.php";
require_once "Utilidades/Validaciones.php";

$accion = $_GET["accion"] ?? "listar";

$controller = new ColaboradorController();

try {
    switch ($accion) {

        case "nuevo":
            $cat = $controller->getCatalogos();
            require_once "Vista/colaborador.php";
            break;

        case "guardar":
            $controller->guardar();
            break;

        case "listar":
        default:
            $colaboradores = $controller->listar();
            require_once "Vista/listar.php";
            break;
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>