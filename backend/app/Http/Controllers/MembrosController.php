<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membros;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MembrosController extends Controller
{
    public function getAll(){
        $data = Membros::all();
        return response()->json($data);
    }

    public function criarMembros(Request $request){
        $membro = Membros::create($request->validate([
            "nome" => "required",
            "cpf" => "required",
            "data_de_nascimento" => "required",
            "endereco" => "required",
            "telefone" => "required",
            "data_de_matricula" => "required",
            "plano_de_contrato" => "required",
        ]));

        return response(['data'=> $membro], 201);
    }

    public function registrarUsuario(Request $request){
        $membro = DB::select('SELECT id as id_membro, nome, cpf, telefone FROM membros WHERE cpf = "'. $request->validate(['cpf'=>'required'])['cpf'] . '"');

        $m = (array) $membro[0];
        if(count($membro) == 0){
            return response(['message'=>'Membro não Encontrado'], 404);
        }
        $usuarioExists = DB::select('SELECT id_membro FROM usuarios WHERE id_membro = "'. $membro[0]->id_membro . '"');

        if(count($usuarioExists) > 0){
            return response(['message'=> 'Usuario já existe'], 403);
        }

        $usuario = $request->validate([
            'email' => 'required|email',
            'senha' => 'required'
        ]);

        $usuario['senha_hash'] = Hash::make($usuario['senha']);
        $usuario['tipo'] = 'membro';


        $user = User::create(array_merge($usuario, $m));
        
        return response(['data'=> $user], 201);
    }

    public function editMembro(Request $request, $id){
        $membro = Membros::findOrFail($id);
        $membro->update($request->validate([
            "nome" => "required",
            "endereco" => "required",
            "telefone" => "required",
            "data_de_matricula" => "required",
            "plano_de_contrato" => "required",
        ]));

        return response(['data' => $membro], 200);
    }

    public function deleteMembro($id){
        $usuario = User::where('id_membro', $id)->first();
        $usuario->delete();
        
        $membro = Membros::findOrFail($id);
        $membro->delete();

        return response('', 204);
    }

    public function getMe($id){
        $perfil = Membros::findOrFail($id);

        return response(['data'=> $perfil],200);
    }
}
