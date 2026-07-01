<?php

class Validaciones
{
    // validar que no esté vacío
    public static function requerido($valor)
    {
        return isset($valor) && trim($valor) !== "";
    }

    // validar números
    public static function esNumero($valor)
    {
        return is_numeric($valor);
    }

    // validar edad lógica
    public static function edadValida($edad)
    {
        return is_numeric($edad) && (int)$edad >= 18 && (int)$edad <= 70;
    }

    // validar email
    public static function emailValido($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // validar celular (Panamá: 7-8 dígitos)
    public static function celularValido($celular)
    {
        return preg_match("/^[0-9]{7,8}$/", $celular);
    }

    // validar identidad (Panamá: X-XXX-XXXXX)
    public static function identidadValida($identidad)
    {
        return preg_match("/^[0-9]+-[0-9]{3}-[0-9]+$/", $identidad);
    }

    // validar que no contenga caracteres HTML peligrosos
    public static function sinHTML($valor)
    {
        return htmlspecialchars_decode($valor) === $valor;
    }

    // validar longitud mínima
    public static function longitudMinima($valor, $minimo)
    {
        return strlen($valor) >= $minimo;
    }

    // validar longitud máxima
    public static function longitudMaxima($valor, $maximo)
    {
        return strlen($valor) <= $maximo;
    }
}