<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Poi extends Model
{
    use HasFactory;

    protected $table = 'cliente';

    public static function lastUpdated()
    {
        $fecha = DB::table('qm_api_control')->where('tabla','cliente')->value('fecha');
        if(is_null($fecha)){
            return '2020-01-01 00:00:00';
        }
        return $fecha;
    }

    public static function setUpdated($fecha)
    {
        DB::table('qm_api_control')
        ->upsert([
            [
            'fecha' => $fecha,
            'tabla' => 'cliente'
            ]
        ],['tabla'],['fecha']);
    }
}
