<?php

namespace App\Http\Controllers;
use App\User;
use App\Task;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Tymon\JWTAuth\Contracts\JWTSubject;

class UserController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $me = $user->replicate();

        $projects = $user->projects;
        $me['totalProjects'] = $projects->count();
        $me['totalTasks'] = 0;
        $me['overdueTasks'] = 0;

        foreach ($projects as $project) {
            if($project->tasks) {
                $tasks = $project->tasks;
                $me['totalTasks'] += $tasks->count();

                $overdueTasks = $tasks->where('ended_at', '<', Carbon::now()->toDateTimeString())->count();
                if($overdueTasks > 0) {
                    $me['overdueTasks'] = $overdueTasks;
                }
            }
        }

        return response()->json($me);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        if($this->verifyEmail($request) > 0) {
            return response()->json(['error' => 'Falha no cadastro', 'message' => 'Já existe um usuário com este e-mail'], 400);
        } else if($request->password != $request->passwordConfirmation) {
            return response()->json(['error' => 'Falha no cadastro', 'message' => 'Confirmação de senha inválida'], 400);
        } else {
            $data = $request->except('email_verified_at', 'remember_token');
            $data['password'] = Hash::make($request->password);
            $data['avatar'] = '/src/assets/users/profile.png';

            $user = User::create($data);
            $user->save();

            return response()->json(['success' => 'Cadastrou com sucesso!', 'message' => 'Acesse agora mesmo sua conta e aproveite nosso app!', $user], 200);
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

    public function verifyEmail(Request $request, $edit = false) {
        $verification = $edit ? 
            User::where('email', $request->email)->count() :
            User::where('email', $request->email)->where('id', '!=', $request->id)->count();

        return $verification;
    }
}
