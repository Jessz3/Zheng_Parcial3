<?php

require_once "config/Conexion.php";
require_once "Modelo/PerfilLaboral.php";
require_once "Utilidades/Validaciones.php";
require_once "Utilidades/Sanitizacion.php";

class PerfilLaboralController
{
    private $model;

    public function __construct()
    {
        $db = (new Conexion())->getConexion();
        $this->model = new PerfilLaboral($db);
    }

    public function guardar()
    {
        if (
            !Validaciones::requerido($_POST["puesto"]) ||
            !Validaciones::esNumero($_POST["salario"]) ||
            !Validaciones::requerido($_POST["fecha_inicio"])
        ) {
            die("❌ Error en datos del perfil laboral");
        }

        $data = [
            "colaborador_id" => $_POST["colaborador_id"],
            "puesto" => Sanitizacion::tipoTitulo($_POST["puesto"]),
            "salario" => $_POST["salario"],
            "fecha_inicio" => $_POST["fecha_inicio"],
            "fecha_fin" => $_POST["fecha_fin"] ?: null,
            "es_activo" => 1,
            "empleado_activo" => 1,
            "planilla" => Sanitizacion::limpiarTexto($_POST["planilla"])
        ];

        if ($this->model->insertar($data)) {
            header("Location: index.php?msg=ok");
        } else {
            die("Error al guardar perfil laboral");
        }
        exit;
    }
}