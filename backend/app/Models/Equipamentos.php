<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamentos extends Model
{
    protected $table = "equipamento";
    protected  $fillable = [
        "nome",
        "marca",
        "modelo",
        "ano_de_fabricacao",
        "numero_de_serie",
        "capacidade_maxima",
        "localizacao",
        "estado"
    ];

    public $timestamps = true;
}
