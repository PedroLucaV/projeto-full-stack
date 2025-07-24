<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Equipamentos;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipamentoController extends Controller
{
    public function getAll(Request $request){
        $data = Equipamentos::all();
        return response()->json(['data'=> $data]);
    }

    public function store(Request $request){
        if($request->user()->currentAccessToken()['name'] != 'admin_token'){
            return response(['message'=>'Operação Não Autorizada'], 401);
        }
        $request->validate([
            "nome" => 'required|string',
            "marca" => 'required|string',
            "modelo" => 'required|string',
            "ano_de_fabricacao" => 'required|integer',
            "numero_de_serie" => 'required|string',
            "capacidade_maxima" => 'required|int',
            "localizacao" => 'required|string',
            "estado" => ['required', Rule::in(['novo', 'bom', 'gasto', 'quebrado'])]
        ]);

        $data = Equipamentos::create($request->all());
        return response(['data' => $data], 201);
    }

    public function updateLocation(Request $request, $id){
        if ($request->user()->currentAccessToken()['name'] != 'admin_token') {
            return response(['message' => 'Operação Não Autorizada'], 401);
        }
        $equipamento = Equipamentos::findOrFail($id);
        $equipamento->update($request->validate([
            "localizacao" => 'required|string'
        ]));

        return response(['data' => $equipamento], 200);
    }

    public function findAvaliable(){
        $qntReservas = DB::select(
            'SELECT e.* from equipamento as e
                    LEFT JOIN reservas as r on e.id = r.id_equipamento
                    WHERE r.id_equipamento IS NULL');

        return response()->json($qntReservas);
    }
}