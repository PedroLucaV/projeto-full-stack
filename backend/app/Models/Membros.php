<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membros extends Model
{
    protected $table = "membros";

    protected $fillable = [
        "nome",
        'cpf',
        'data_de_nascimento',
        'endereco',
        'telefone',
        'data_de_matricula',
        'plano_de_contrato'
    ];

    public $timestamps = true;
}
