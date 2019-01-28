<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\Project;
use App\Category;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index($project_id)
    {
        $project = Project::find($project_id);
        $tasks = $project->tasks;
        
        foreach ($tasks as $task) {
            $task->status;
            $task->category;
        }

        return response()->json($tasks, 200);
    }

    public function store(Request $request)
    {
        Task::create([
            'name' => $request->data['nome'],
            'started_at' => $request->data['inicio'],
            'ended_at' => $request->data['entrega'],
            'status_id' => 1,
            'category_id' => $request->data['categoria'],
            'project_id' => $request->data['projeto'],
            'description' => $request->data['descricao']
        ]);

        return response()->json(['success' => 'Tarefa cadastrada com sucesso!'], 200);
    }

    public function show($id)
    {
        $task = Task::find($id);
        $task->status;
        $task->category;
        Carbon::setLocale('pt_BR');
        $dateStart = Carbon::parse($task->started_at);
        $task['start'] = $dateStart->diffForHumans(Carbon::now());

        if ($task->completed_at) {
            $dateEnd = Carbon::parse($task->completed_at);
            $task['end'] = $dateEnd->diffForHumans(Carbon::now());
        }

        if ($task->ended_at < Carbon::now()->toDateTimeString()) {
            $dateLate = Carbon::parse($task->ended_at);
            $task['late'] = $dateLate->diffForHumans(Carbon::now());
            $task['late'] = str_replace('atrás', '', $task['late']);
        }

        return response()->json($task, 200);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        $task->update([
            'name' => $request->data['nome'],
            'description' => $request->data['descricao'],
            'started_at' => $request->data['inicio'],
            'ended_at' => $request->data['entrega'],
            'category_id' => $request->data['categoria'],
            'project_id' => $request->data['projeto']
        ]);

        return response()->json(['success' => 'Tarefa alterada com sucesso!'], 200);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return response()->json(['success' => 'Tarefa excluída com sucesso!'], 200);
    }

    public function finish($id) {
        $task = Task::find($id);

        $task->update([
            'status_id' => 2,
            'completed_at' => Carbon::now()
        ]);

        return response()->json([
            'success' => 'Tarefa ' . $task->name . ' finalizada com sucesso!', 
            'message' => 'Parabéns você concluiu sua tarefa no dia ' . $task->completed_at]
        , 200);
    }

    public function back($id) {
        $task = Task::find($id);

        $task->update([
            'status_id' => 1,
            'completed_at' => null
        ]);

        return response()->json([
            'success' => ' De volta ao trabalho!', 
            'message' => 'A tarefa ' . $task->name . ' está em andamento novamente.']
        , 200);
    }

    public function getCategories() {
        $categories = Category::all();

        return response()->json($categories, 200);
    }

    public function setTime(Request $request, $id) {
        $hour = substr($request->tempo, 0, 1);
        $minute = substr($request->tempo, 3, 4);
        $second = substr($request->tempo, 6, 7);

        $task = Task::find($id);
        if ($task->time) {
            $tempo = Carbon::createFromFormat('H:i:s', $task->time);
            $tempo->addSecond($second);
            $tempo->addMinute($minute);
            $tempo->addHour($hour);

            $task->update([
                'time' => $tempo
            ]);
        } else {
            $task->time = $request->tempo;
            $task->save();
        }
    }
}
