<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservas extends Model
{
    protected $table = "reservas";
    protected $fillable = [
        "id_usuario",
        "id_equipamento",
        "data_reserva",
        "data_terminado"
    ];

    public $timestamps = true;
}
