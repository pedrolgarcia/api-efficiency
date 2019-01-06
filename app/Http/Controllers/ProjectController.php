<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $projects = $user->projects;

        foreach ($projects as $project) {
            $project->tasks;
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

        return response()->json($project, 200);
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
