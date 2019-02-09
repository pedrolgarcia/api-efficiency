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
        
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        if($this->verifyEmail($request) > 0) {
            return response()->json(['error' => 'Falha no cadastro', 'message' => 'Já existe um usuário com este e-mail'], 400);
        }
        
        if($request->password != $request->passwordConfirmation) {
            return response()->json(['error' => 'Falha no cadastro', 'message' => 'Confirmação de senha inválida'], 400);
        }

        $data = $request->except('email_verified_at', 'remember_token');
        $data['password'] = Hash::make($request->password);
        $data['avatar'] = '/src/assets/users/profile.png';

        $user = User::create($data);
        $user->save();

        return response()->json(['success' => 'Cadastrou com sucesso!', 'message' => 'Acesse agora mesmo sua conta e aproveite nosso app!', $user], 200);       
    }

    public function photoUpload(Request $request) {
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/users_imgs');
            $image->move($destinationPath, $name);
            $this->save();

            $path = $destinationPath . $name;
    
            return response()->json(['success' => 'Foto salva com sucesso!', 'path' => $path ]);
        }
    }

    public function show()
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

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        $me = User::find(auth()->user()->id);

        if(!Hash::check($request->oldPassword, $me->password)) {
            return response()->json(['error' => $request->oldPassword, 'message' => $me->password], 400);
        }
        if($request->password !== $request->confirmPassword) {
            return response()->json(['error' => 'Falha ao editar perfil', 'message' => 'Confirmação de senha inválida'], 400);
        }

        $data = $request->except('email_verified_at', 'remember_token');
        $data['password'] = Hash::make($request->password);

        $me->update($data);

        return response()->json(['success' => 'Editou com sucesso!', 'message' => 'Perfil editado com sucesso!', $me], 200);       
        
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
