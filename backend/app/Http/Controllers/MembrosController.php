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
    public function getAll(Request $request){
        if ($request->user()->currentAccessToken()['name'] != 'admin_token') {
            return response(['message' => 'Operação Não Autorizada'], 401);
        }
        $data = Membros::all();
        return response()->json($data);
    }

    public function criarMembros(Request $request){
        if ($request->user()->currentAccessToken()['name'] != 'admin_token') {
            return response(['message' => 'Operação Não Autorizada'], 401);
        }
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

        $m = (array) $membro;
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

        $userData = [
            'senha_hash' => Hash::make($usuario['senha']),
            'tipo' => 'membro',
            'email' => $usuario['email'],
            'id_membro' => $membro[0]->id_membro,
            'cpf' => $membro[0]->cpf,
            'nome' => $membro[0]->nome,
            'telefone' => $membro[0]->telefone
        ];

        $user = User::create($userData);
        // return $userData;
        return response(['data'=> $user], 201);
    }

    public function registrarAdmin(Request $request){
        if ($request->user()->currentAccessToken()['name'] != 'admin_token') {
            return response(['message' => 'Operação Não Autorizada'], 401);
        }

        $user = $request->validate([
            'nome'=> 'required',
            'cpf' => 'required',
            'telefone' => 'required',
            'email' => 'required|email',
            'senha' => 'required'
        ]);

        $userExists = User::where('cpf', $user['cpf'])->first();
        
        if($userExists){
            return response(['message' => 'Usuario já existe'], 403);
        }

        $emailValidate = User::where('email', $user['email'])->first();
        if($emailValidate){
            return response(['message' => 'Usuario já cadastrado com este email'], 403);
        }

        $user['tipo'] = 'Admin';
        $user['senha_hash'] = Hash::make($user['senha']);

        $created = User::create($user);

        return response(['data'=> $created], 201);
    }

    public function editMembro(Request $request, $id){
        $membro = Membros::findOrFail($id);
        if ($request->user()->currentAccessToken()['name'] != 'admin_token' || $request->user()->currentAccessToken()['tokenable']->id != $membro['id']) {
            return response(['message' => 'Operação Não Autorizada'], 401);
        }
        $membro->update($request->validate([
            "nome" => "required",
            "endereco" => "required",
            "telefone" => "required",
            "data_de_matricula" => "required",
            "plano_de_contrato" => "required",
        ]));

        return response(['data' => $membro], 200);
    }

    public function deleteMembro(Request $request,$id){
        if ($request->user()->currentAccessToken()['name'] != 'admin_token') {
            return response(['message' => 'Operação Não Autorizada'], 401);
        }
        $usuario = User::where('id_membro', $id)->first();
        $usuario->delete();
        
        $membro = Membros::findOrFail($id);
        $membro->delete();

        return response('', 204);
    }

    public function getMe(Request $request){
        $id = $request->user()->currentAccessToken()['tokenable']->id_membro;
        $perfil = Membros::findOrFail($id);

        return response(['data'=> $perfil],200);
    }
}
