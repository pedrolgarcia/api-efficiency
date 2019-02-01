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
            $tasks = $project->tasks;
            $project->status;
            // Dados adicionais
            Carbon::setLocale('pt_BR');
            $dateStart = Carbon::parse($project->started_at);
            $project['start'] = $dateStart->diffForHumans(Carbon::now());

            if ($project->completed_at) {
                $dateEnd = Carbon::parse($project->completed_at);
                $project['end'] = $dateEnd->diffForHumans(Carbon::now());
            }
            
            $project['time'] = Carbon::createFromFormat('H:i:s', '00:00:00');
            
            $project['tasks_qtd'] = $tasks->count();
            foreach ($tasks as $key => $task) {
                if ($task->time) {
                    $hour = substr($task->time, 0, 1);
                    $minute = substr($task->time, 3, 4);
                    $second = substr($task->time, 6, 7);
                    
                    $project['time']->addSecond($second);
                    $project['time']->addMinute($minute);
                    $project['time']->addHour($hour);
                }
            }

            $project['time'] = substr($project['time'], 11, 18);

            if ($project->ended_at < Carbon::now()->toDateTimeString()) {
                $dateLate = Carbon::parse($project->ended_at);
                $project['late'] = $dateLate->diffForHumans(Carbon::now());
                $project['late'] = str_replace('atrás', '', $project['late']);
            }
        }

        return response()->json($projects, 200);
    }

    public function store(Request $request)
    {
        Project::create([
            'name' => $request->data['nome'],
            'started_at' => $request->data['inicio'],
            'ended_at' => $request->data['entrega'],
            'description' => $request->data['descricao'],
            'user_id' => auth()->user()->id,
            'status_id' => 1,
        ]);

        return response()->json([
            'success' => 'Projeto criado com sucesso!', 
            'message' => 'O próximo passo é criar sua primeira tarefa. Vamos lá!']
        , 200);
    }

    public function show($id)
    {
        $project = Project::find($id);
        $tasks = $project->tasks;
        $project->status;
        Carbon::setLocale('pt_BR');
        $dateStart = Carbon::parse($project->started_at);
        $project['start'] = $dateStart->diffForHumans(Carbon::now());

        if ($project->completed_at) {
            $dateEnd = Carbon::parse($project->completed_at);
            $project['end'] = $dateEnd->diffForHumans(Carbon::now());
        }
        
        $project['time'] = Carbon::createFromFormat('H:i:s', '00:00:00');
        
        foreach ($tasks as $key => $task) {
            if ($task->time) {
                $hour = substr($task->time, 0, 1);
                $minute = substr($task->time, 3, 4);
                $second = substr($task->time, 6, 7);
                
                $project['time']->addSecond($second);
                $project['time']->addMinute($minute);
                $project['time']->addHour($hour);
            }
        }

        $project['time'] = substr($project['time'], 11, 18);

        if ($project->ended_at < Carbon::now()->toDateTimeString()) {
            $dateLate = Carbon::parse($project->ended_at);
            $project['late'] = $dateLate->diffForHumans(Carbon::now());
            $project['late'] = str_replace('atrás', '', $project['late']);
        }

        return response()->json($project, 200);
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

        foreach ($project->tasks as $task) {
            $task->update([
                'status_id' => 2,
                'completed_at' => Carbon::now()
            ]);
        }

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

        $project->tasks->last()->update([
            'status_id' => 1,
            'completed_at' => null
        ]);

        return response()->json([
            'success' => ' De volta ao trabalho!', 
            'message' => 'O projeto ' . $project->name . ' está em andamento novamente.']
        , 200);
    }
}
