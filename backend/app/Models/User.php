<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
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
