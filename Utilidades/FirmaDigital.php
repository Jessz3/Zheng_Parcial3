<?php

class FirmaDigital
{
    public static function firmar($datos)
    {
        $data = json_encode($datos);

        return hash('sha256', $data);
    }
}