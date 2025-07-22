<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = "usuarios";
    protected $fillable = [
        'nome',
        'cpf',
        'telefone',
        'email',
        'senha_hash',
        'tipo',
        'id_membro'
    ];
    public $timestamps = true;
}
