<?php

require_once "config/Conexion.php";
require_once "Controlador/ColaboradorController.php";
require_once "Controlador/PerfilLaboralController.php";
require_once "Utilidades/Sanitizacion.php";
require_once "Utilidades/Validaciones.php";

$accion = $_GET["accion"] ?? "listar";

$colaboradorController = new ColaboradorController();
$perfilLaboralController = new PerfilLaboralController();

try {
    switch ($accion) {

        case "nuevo":
            $cat = $colaboradorController->getCatalogos();
            require_once "Vista/colaborador.php";
            break;

        case "guardar":
            $colaboradorController->guardar();
            break;

        case "perfil_nuevo":
            $resultado = $perfilLaboralController->nuevo();
            $colaboradores = $resultado['colaboradores'];
            $catalogos = $resultado['catalogos'];
            require_once "Vista/perfil_laboral.php";
            break;

        case "guardar_perfil":
            $perfilLaboralController->guardar();
            break;

        case "exportar":
            $colaboradorController->exportarExcel();
            break;

        case "listar":
        default:
            $colaboradores = $colaboradorController->listar();
            require_once "Vista/listar.php";
            break;
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>