<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $userData = $request->validate([
            "email" => "required|email",
            "senha" => "required"
        ]);

        $user = User::where("email", $userData["email"])->first();

        if(!$user){
            return response(['message'=> 'Usuario não encontrado'], 404);
        }
        
        if(!Hash::check($userData['senha'], $user['senha_hash'])){
            return response(['message'=> 'Senha não condiz'],403);
        }

        $user['tipo'] == 'Membro' ? $token = $user->createToken('member_token') : $token = $user->createToken('admin_token');
        $access = ['token' => $token->plainTextToken, 'id' => $user['id'], 'token_name' => $token->accessToken['name']];
        return $access;
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response(['message'=> ''],204);
    }

    public function me(Request $request){
        $id = $request->user()->currentAccessToken()['tokenable']->id;
        $perfil = User::findOrFail($id);

        return response(['data' => $perfil], 200);
    }
}
