<?php

class PerfilLaboral
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    public function insertar($data)
    {
        $sql = "INSERT INTO perfiles_laborales
        (colaborador_id, puesto, salario, tipo_empleado_id, planilla_id, fecha_inicio, fecha_fin, es_activo, empleado_activo, motivo_terminacion)
        VALUES
        (:colaborador_id, :puesto, :salario, :tipo_empleado_id, :planilla_id, :fecha_inicio, :fecha_fin, :es_activo, :empleado_activo, :motivo_terminacion)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}