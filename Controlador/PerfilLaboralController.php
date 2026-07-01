<?php

require_once "config/Conexion.php";
require_once "Modelo/PerfilLaboral.php";
require_once "Modelo/Colaborador.php";
require_once "Modelo/CatalogoModel.php";
require_once "Utilidades/Validaciones.php";
require_once "Utilidades/Sanitizacion.php";

class PerfilLaboralController
{
    private $model;
    private $cat;
    private $colaboradorModel;

    public function __construct()
    {
        $db = (new Conexion())->getConexion();
        $this->model = new PerfilLaboral($db);
        $this->cat = new CatalogoModel($db);
        $this->colaboradorModel = new Colaborador($db);
    }

    public function nuevo()
    {
        return [
            'colaboradores' => $this->colaboradorModel->obtenerTodos(),
            'catalogos' => [
                'tipoEmpleado' => $this->cat->getTipoEmpleado() ?? [],
                'motivosTerminacion' => $this->cat->getMotivosTerminacion() ?? [],
                'planillas' => [
                    ['id' => 1, 'Nombre' => 'Permanente'],
                    ['id' => 2, 'Nombre' => 'Eventual'],
                    ['id' => 3, 'Nombre' => 'Interino'],
                    ['id' => 4, 'Nombre' => 'Por Contrato']
                ]
            ]
        ];
    }

    public function guardar()
    {
        if (
            !Validaciones::esNumero($_POST["colaborador_id"]) ||
            !Validaciones::requerido($_POST["puesto"]) ||
            !Validaciones::esNumero($_POST["salario"]) ||
            !Validaciones::requerido($_POST["fecha_inicio"]) ||
            !Validaciones::esNumero($_POST["tipo_empleado_id"]) ||
            !Validaciones::esNumero($_POST["planilla_id"])
        ) {
            die("❌ Error en datos del perfil laboral");
        }

        $fechaFin = !empty($_POST["fecha_fin"]) ? $_POST["fecha_fin"] : null;
        if ($fechaFin && $fechaFin < $_POST["fecha_inicio"]) {
            die("❌ La fecha fin no puede ser anterior a la fecha inicio");
        }

        if ($fechaFin && !Validaciones::requerido($_POST["motivo_terminacion"])) {
            die("❌ Si hay fecha de fin, debe indicar motivo de terminación");
        }

        $data = [
            "colaborador_id" => (int)$_POST["colaborador_id"],
            "puesto" => Sanitizacion::tipoTitulo($_POST["puesto"]),
            "salario" => (float)$_POST["salario"],
            "tipo_empleado_id" => (int)$_POST["tipo_empleado_id"],
            "planilla_id" => (int)$_POST["planilla_id"],
            "fecha_inicio" => $_POST["fecha_inicio"],
            "fecha_fin" => $fechaFin,
            "es_activo" => $fechaFin ? 0 : 1,
            "empleado_activo" => $fechaFin ? 0 : 1,
            "motivo_terminacion" => !empty($_POST["motivo_terminacion"]) ? Sanitizacion::limpiarTexto($_POST["motivo_terminacion"]) : null
        ];

        if ($this->model->insertar($data)) {
            header("Location: Index.php?accion=listar&msg=perfil_ok");
            exit;
        }

        die("Error al guardar perfil laboral");
    }
}