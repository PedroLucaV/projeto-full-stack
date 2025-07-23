<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservasController extends Controller
{
    public function getAll(Request $request){
        $tokenName = $request->user()->currentAccessToken()['name'];
        if($tokenName == 'admin_token'){
            return response(['data' => Reservas::all()], 200);
        }

        $userId = $request->user()->currentAccessToken()['id'];
        $reservas = Reservas::where(['id_usuario', 'status'], [$userId, true])->get();

        return response(['data'=> $reservas],200);
    }

    public function getMine(Request $request){
        $userId = $request->user()->currentAccessToken()['id'];
        $reservas = Reservas::where(['id_usuario', 'status'], [$userId, true])->get();

        return response(['data' => $reservas], 200);
    }

    public function getHistory (Request $request){
        $userId = $request->user()->currentAccessToken()['id'];
        $reservas = Reservas::where('id_usuario', $userId)->get();

        return response(['data' => $reservas], 200);
    }
    
    public function createReserva(Request $request){
        $id_maquina = $request->validate([
            'id_maquina' => 'required'
        ])['id_maquina'];

        $data_reserva = $request->validate(['data_reserva' => 'required|date'])['data_reserva'];
        $userId = $request->user()->currentAccessToken()['id'];

        $verifyIfBusy ="  SELECT e.id,
                          (SELECT count(*) from reservas as r where r.id_equipamento = e.id) as reservas
                          FROM equipamento as e where e.id = $id_maquina;";
        if(DB::select($verifyIfBusy)[0]->reservas > 0){
            return response(['message'=> 'Maquina já reservada para esta hora'], 405);
        }

        // $verifyUserAppoints = 

        // $query = "INSERT INTO reservas (id_usuario, id_equipamento, data_reserva, data_termino) values ($userId, $id_maquina, $data_reserva,ADDTIME(now(), '0 0:45:0.000000'));";

        return '';
    }
}
