<?php

class CatalogoModel
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    public function getSexo()
    {
        try {
            return $this->db->query("SELECT * FROM ` cat_sexo` ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getSangre()
    {
        try {
            return $this->db->query("SELECT * FROM `cat_tiposangre` ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getRutas()
    {
        try {
            return $this->db->query("SELECT * FROM `cat_rutas` ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getEstadoCivil()
    {
        try {
            return $this->db->query("SELECT * FROM `cat_estadocivil` ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getOcupaciones()
    {
        try {
            return $this->db
                ->query("SELECT C_OCUP, OCUPACION FROM `cat_ocupaciones` WHERE Activo = 1 ORDER BY OCUPACION ASC")
                ->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getMotivosTerminacion()
    {
        try {
            return $this->db
                ->query("SELECT C_TERMINACION, MOTIVO FROM `cat_motivos_terminacion` ORDER BY MOTIVO ASC")
                ->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getTipoEmpleado()
    {
        try {
            return $this->db
                ->query("SELECT * FROM `cat_tipoempleado` WHERE Activo = 1 ORDER BY Nombre ASC")
                ->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}