<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipamentoModel extends Model
{
    protected $table = "equipamento";
    protected  $fillable = [
        "nome",
        "marca",
        "modelo",
        "ano_de_fabricacao",
        "numero_de_serie",

    ]
}
