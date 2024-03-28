<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Help
{
    /** 
     * Formato de fechas
     */
    public static function customDateFormat($fecha, $formato = 'd/m/Y')
    {
        $aux = new Carbon($fecha);
        $formatoFecha = $aux->format($formato);
        return $formatoFecha;
    }
}