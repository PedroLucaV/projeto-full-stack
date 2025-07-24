<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\MembrosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservasController;
use App\Models\Membros;

Route::get('/', function (){
    return response('Hello World', 200);
});

Route::prefix('/equipamentos')->group(function (){
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/', [EquipamentoController::class, 'getAll']);
        Route::post('/', [EquipamentoController::class,'store']);
        Route::put('/{id}/localizacao', [EquipamentoController::class,'updateLocation']);
        Route::get('/disponiveis', [EquipamentoController::class,'findAvaliable']);
    });
});

Route::prefix('/membros')->group(function (){
    Route::post('/register', [MembrosController::class, 'registrarUsuario']); //criacao de usuario pelo membro
    Route::post('/rAdm', [MembrosController::class, 'registrarAdmin']);
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/', [MembrosController::class, 'getAll']);
        Route::post('/', [MembrosController::class, 'criarMembros']); //exclusivoAdmin
        Route::put('/{id}', [MembrosController::class, 'editMembro']); //Adm ou proprio membro
        Route::delete('/{id}', [MembrosController::class, 'deleteMembro']); //Adm exclusivo
        Route::get('/perfil', [MembrosController::class, 'getMe']);
    });
});

Route::prefix('/reservas')->group(function (){
    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/', [ReservasController::class, 'getAll']);
        Route::post('/', [ReservasController::class, 'createReserva']);
        Route::put('/{id}/estender', [ReservasController::class, 'estender']);
        Route::delete('/{id}', [ReservasController::class, '']);
        Route::get('/minhas', [ReservasController::class, 'getMine']);
        Route::get('/historico', [ReservasController::class, 'getHistory']);
    });
});

Route::prefix('auth')->group(function (){
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function (){
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});