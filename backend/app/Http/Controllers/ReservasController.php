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

        $verifyIfBusy ="SELECT e.id as id_maquina,
                        (SELECT count(*) from reservas as r where r.id_equipamento = e.id and r.data_termino >= $data_reserva or $data_reserva) as reservas
                        FROM equipamento as e where e.id = 1";
        if(count(DB::select($verifyIfBusy)) > 0){
            return response(['message'=> 'Maquina já reservada para esta hora'], 405);
        }

        $verifyUserAppoints = "SELECT count(*) as reservas from reservas where id_usuario = $userId and status = 'ativo'";

        if(count(DB::select($verifyUserAppoints)) > 0 && DB::select($verifyUserAppoints)[0]->reservas == 2){
            return response(['message' => 'Você já possui duas maquinas reservadas no momento'], 405);
        }

        $query = "INSERT INTO reservas (id_usuario, id_equipamento, data_reserva, data_termino) values ($userId, $id_maquina, $data_reserva,ADDTIME($data_reserva, '0 0:45:0.000000'))";

        $reserva = DB::select($query);

        return response(['data'=> $reserva], 201);
    }

    public function estender(Request $request, $id){
        $reserva = Reservas::findOrFail($id);
        $idEqu = $reserva['id_equipamento'];
        $dtR = $reserva['data_reserva'];
        $dtT = $reserva['data_termino'];
        $idR = $reserva['id'];
        $verifyQueue = "SELECT * FROM reservas where id_equipamento = $idEqu && data_reserva <= ADDTIME($dtR, '0 0:30:0.000000'))";
        if(count(DB::select($verifyQueue)) > 0){
            return response(["message"=> "Não é possivel estender a reserva, já há outro na fila"], 405);
        }

        $query = "UPDATE reservas SET data_termino = ADDTIME($dtT, '0 0:30:0.000000') where id = $idR";
        $res = DB::select($query);
        return response(['data'=> $res], 200);
    }

    public function cancelar(Request $request, $id){
        $reserva = Reservas::findOrFail($id);
        $idR = $reserva['id'];

        $query = "UPDATE reservas SET status = 'terminado' where id = $idR";
        DB::select($query);
        return response(["message"=> "Reserva Cancelada"], 200);
    }
}
