<?php

class Colaborador
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    public function insertar($data)
    {
        try {
            $sql = "INSERT INTO colaboradores
            (identidad, nombre, apellido, edad,
             sexo_id, tipo_sangre_id, ruta_id, ocupacion_id, estado_civil_id,
             nacionalidad, email, celular)
            VALUES
            (:identidad, :nombre, :apellido, :edad,
             :sexo_id, :tipo_sangre_id, :ruta_id, :ocupacion_id, :estado_civil_id,
             :nacionalidad, :email, :celular)";

            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute($data);
        } catch (PDOException $e) {
            throw new Exception("Error al insertar: " . $e->getMessage());
        }
    }

    public function obtenerTodos()
    {
        try {
            $result = $this->db->query("SELECT * FROM colaboradores ORDER BY id DESC");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener colaboradores: " . $e->getMessage());
        }
    }

    public function obtenerPorId($id)
    {
        try {
            $sql = "SELECT * FROM `colaboradores` WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["id" => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener colaborador: " . $e->getMessage());
        }
    }

    public function actualizar($id, $data)
    {
        try {
            $campos = [];
            foreach ($data as $campo => $valor) {
                $campos[] = "$campo = :$campo";
            }
            $campos_sql = implode(", ", $campos);

            $sql = "UPDATE colaboradores SET $campos_sql WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $data['id'] = $id;
            
            return $stmt->execute($data);
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar: " . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            $sql = "DELETE FROM colaboradores WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(["id" => $id]);
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}