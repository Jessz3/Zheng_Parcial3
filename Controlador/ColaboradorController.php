<?php

require_once "config/Conexion.php";
require_once "Modelo/Colaborador.php";
require_once "Utilidades/Sanitizacion.php";
require_once "Utilidades/Validaciones.php";
require_once "Modelo/CatalogoModel.php";

class ColaboradorController
{
    private $model;
    private $cat;

    public function __construct()
    {
        $db = (new Conexion())->getConexion();
        $this->model = new Colaborador($db);
        $this->cat = new CatalogoModel($db);
    }

    public function getCatalogos()
    {
        return [
            "sexo" => $this->cat->getSexo() ?? [],
            "sangre" => $this->cat->getSangre() ?? [],
            "rutas" => $this->cat->getRutas() ?? [],
            "estadoCivil" => $this->cat->getEstadoCivil() ?? [],
            "ocupaciones" => $this->cat->getOcupaciones() ?? []
        ];
    }

    public function listar()
    {
        return $this->model->obtenerTodos();
    }

    public function guardar()
    {
        // VALIDACIONES DE CAMPOS REQUERIDOS
        if (empty($_POST["identidad"]) || !Validaciones::requerido($_POST["identidad"])) {
            die("Error: Identidad es requerida");
        }
        if (empty($_POST["nombre"]) || !Validaciones::requerido($_POST["nombre"])) {
            die("Error: Nombre es requerido");
        }
        if (empty($_POST["apellido"]) || !Validaciones::requerido($_POST["apellido"])) {
            die("Error: Apellido es requerido");
        }
        if (empty($_POST["edad"]) || !Validaciones::edadValida($_POST["edad"])) {
            die("Error: Edad debe estar entre 18 y 70 años");
        }
        if (empty($_POST["email"]) || !Validaciones::emailValido($_POST["email"])) {
            die("Error: Email inválido");
        }
        if (empty($_POST["celular"]) || !Validaciones::celularValido($_POST["celular"])) {
            die("Error: Celular inválido (debe tener 7-8 dígitos)");
        }

        // SANITIZACIÓN
        $data = [
            "identidad" => Sanitizacion::limpiarTexto($_POST["identidad"]),
            "nombre" => Sanitizacion::tipoTitulo($_POST["nombre"]),
            "apellido" => Sanitizacion::tipoTitulo($_POST["apellido"]),
            "edad" => (int)$_POST["edad"],

            "sexo_id" => !empty($_POST["sexo_id"]) ? (int)$_POST["sexo_id"] : null,
            "tipo_sangre_id" => !empty($_POST["tipo_sangre_id"]) ? (int)$_POST["tipo_sangre_id"] : null,
            "ruta_id" => !empty($_POST["ruta_id"]) ? (int)$_POST["ruta_id"] : null,
            "ocupacion_id" => !empty($_POST["ocupacion_id"]) ? (int)$_POST["ocupacion_id"] : null,
            "estado_civil_id" => !empty($_POST["estado_civil_id"]) ? (int)$_POST["estado_civil_id"] : null,

            "nacionalidad" => !empty($_POST["nacionalidad"]) ? Sanitizacion::tipoTitulo($_POST["nacionalidad"]) : null,
            "email" => Sanitizacion::limpiarEmail($_POST["email"]),
            "celular" => Sanitizacion::limpiarNumero($_POST["celular"])
        ];

        if ($this->model->insertar($data)) {
            header("Location: Index.php?accion=listar&msg=ok");
            exit;
        } else {
            die("Error al guardar colaborador");
        }
    }
}