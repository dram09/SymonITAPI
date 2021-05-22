<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMeasure extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedido';
}
