<?php

class Sanitizacion
{
    // Limpia una cadena de texto eliminando espacios en blanco, etiquetas HTML y caracteres especiales
    public static function limpiarTexto($cadena)
    {
        $cadena = trim($cadena);
        $cadena = strip_tags($cadena);
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, "UTF-8");

        return $cadena;
    }

    // Convierte la primera letra de cada palabra en mayúscula
    public static function tipoTitulo($cadena)
    {
        if (empty($cadena)) return "";
        
        $cadena = mb_strtolower($cadena, "UTF-8");

        return mb_convert_case(
            $cadena,
            MB_CASE_TITLE,
            "UTF-8"
        );
    }

    public static function limpiarEmail($email)
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    public static function limpiarNumero($numero)
    {
        return preg_replace('/[^0-9]/', '', $numero);
    }

    public static function limpiarIdentidad($identidad)
    {
        // Formato: X-XXX-XXXXX
        return preg_replace('/[^0-9-]/', '', trim($identidad));
    }

    public static function esEmailValido($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Escapa HTML para salida segura
    public static function escaparHTML($valor)
    {
        return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
    }

    // Limpia todo tipo de entrada peligrosa
    public static function sanitizarGeneral($valor)
    {
        return htmlspecialchars(strip_tags(trim($valor)), ENT_QUOTES, "UTF-8");
    }
}