<?php

namespace App\Http\Controllers;
use App\User;
use App\Task;
use App\Setting;
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

        Setting::create([
            'tips' => '1',
            'language_id' => '1',
            'theme_id' => '1',
            'user_id' => $user->id,
        ]);

        return response()->json(['success' => 'Cadastrou com sucesso!', 'message' => 'Acesse agora mesmo sua conta e aproveite nosso app!', $user], 200);       
    }

    public function photoUpload(Request $request, $id) {
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
        ]);
    
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = time().'_'.str_replace(' ', '', $image->getClientOriginalName());
            $destinationPath = public_path('/users_imgs');
            $image->move($destinationPath, $name);
            
            $path = '/users_imgs/' . $name;
            
            if(!$id) {
                $user = User::orderBy('created_at', 'desc')->first();
            } else {
                $user = User::find($id);
            }

            $user->avatar = $path;
            $user->save();
    
            return response()->json(['success' => 'Foto salva com sucesso!', 'path' => $path ]);
        }

        return response()->json(['success' => 'Nenhuma foto enviada!']);
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

        if($request->password) {
            $data = $request->except('email_verified_at', 'remember_token');

            if(!Hash::check($request->oldPassword, $me->password)) {
                return response()->json(['error' => $request->oldPassword, 'message' => $me->password], 400);
            }
            if($request->password !== $request->confirmPassword) {
                return response()->json(['error' => 'Falha ao editar perfil', 'message' => 'Confirmação de senha inválida'], 400);
            }

            $data['password'] = Hash::make($request->password);
        }

        if(!$request->password) {
            $data = $request->except('email_verified_at', 'remember_token', 'password');
        }

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
