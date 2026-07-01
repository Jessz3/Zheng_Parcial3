<?php

class Conexion
{
    private $conexion;

    public function __construct()
    {
        $host = "localhost";
        $bd = "parcial_3";
        $usuario = "root";
        $password = "";

        $dsn = "mysql:host=$host;dbname=$bd;charset=utf8mb4";

        try
        {
            $this->conexion = new PDO($dsn, $usuario, $password);

            $this->conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }
        catch(PDOException $e)
        {
            die("Error de conexión: ".$e->getMessage());
        }
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    // INSERT
    public function insertSeguro($tabla,$datos)
    {
        $campos = implode(",",array_keys($datos));

        $valores = ":".implode(",:",array_keys($datos));

        $sql = "INSERT INTO $tabla ($campos)
                VALUES ($valores)";

        $stmt = $this->conexion->prepare($sql);

        foreach($datos as $campo=>$valor)
        {
            $stmt->bindValue(":".$campo,$valor);
        }

        return $stmt->execute();
    }

    // UPDATE
    public function updateSeguro($tabla,$datos,$condiciones)
    {
        $set=[];

        foreach($datos as $campo=>$valor)
        {
            $set[]="$campo=:$campo";
        }

        $where=[];

        foreach($condiciones as $campo=>$valor)
        {
            $where[]="$campo=:cond_$campo";
        }

        $sql="UPDATE $tabla
              SET ".implode(",",$set)."
              WHERE ".implode(" AND ",$where);

        $stmt=$this->conexion->prepare($sql);

        foreach($datos as $campo=>$valor)
        {
            $stmt->bindValue(":$campo",$valor);
        }

        foreach($condiciones as $campo=>$valor)
        {
            $stmt->bindValue(":cond_$campo",$valor);
        }

        return $stmt->execute();
    }

    // DELETE
    public function deleteSeguro($tabla,$condiciones)
    {
        $where=[];

        foreach($condiciones as $campo=>$valor)
        {
            $where[]="$campo=:$campo";
        }

        $sql="DELETE FROM $tabla
              WHERE ".implode(" AND ",$where);

        $stmt=$this->conexion->prepare($sql);

        foreach($condiciones as $campo=>$valor)
        {
            $stmt->bindValue(":$campo",$valor);
        }

        return $stmt->execute();
    }

    // SELECT
    public function selectSeguro($sql, $params = [])
    {
        $stmt = $this->conexion->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lastId()
    {
        return $this->conexion->lastInsertId();
    }

}