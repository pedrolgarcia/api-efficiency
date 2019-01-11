<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Project;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $projects = $user->projects;

        foreach ($projects as $project) {
            $project->tasks;
            $project->status;
        }

        return response()->json($projects, 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $project = Project::find($id);
        $project->tasks;
        $project->status;

        return response()->json($project, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        $project->name = $request->data['nome'];
        $project->description = $request->data['descricao'];
        $project->started_at = $request->data['inicio'];
        $project->ended_at = $request->data['entrega'];

        $project->save();

        return response()->json(['success' => 'Projeto alterado com sucesso!'], 200);
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();

        return response()->json(['success' => 'Projeto excluído com sucesso!'], 200);
    }

    public function filter(Request $request) {
        $user = auth()->user();
        $projects = $user->projects;

        $projects = $projects->where('status_id', $request->filter);

        if ($projects->count() == 0) {
            return response()->json(['message' => 'Nenhum projeto encontrado'], 400);
        }

        $data = [];
        foreach ($projects as $project) {
            $project->tasks;
            $project->status;
            array_push($data, $project);
        }

        return response()->json($data, 200);
    }

    public function finish($id) {
        $project = Project::find($id);

        $project->update([
            'status_id' => 2,
            'completed_at' => Carbon::now()
        ]);

        return response()->json([
            'success' => $project->name . ' finalizado com sucesso!', 
            'message' => 'Parabéns você concluiu seu projeto no dia ' . $project->completed_at]
        , 200);
    }

    public function back($id) {
        $project = Project::find($id);

        $project->update([
            'status_id' => 1,
            'completed_at' => null
        ]);

        return response()->json([
            'success' => ' De volta ao trabalho!', 
            'message' => 'O projeto ' . $project->name . ' está em andamento novamente.']
        , 200);
    }
}
