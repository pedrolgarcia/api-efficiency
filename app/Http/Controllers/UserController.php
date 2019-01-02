<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Contracts\JWTSubject;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        if(User::where('email', $request->email)) {
            return response()->json(['error' => 'Já existe um usuário com este e-mail']);
        } else if($request->password != $request->passwordConfirmation) {
            return response()->json(['error' => 'Confirmação de senha inválida'], 401);
        } else {
            $password = Hash::make($request->password);

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $password;
            $user->avatar = '/src/assets/users/profile.png';
            $user->save();

            return response()->json(['success' => 'Usuário cadastrado com sucesso', $user], 200);
        }        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
