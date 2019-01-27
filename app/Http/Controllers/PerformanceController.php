<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use Carbon\Carbon;

class PerformanceController extends Controller
{
    public function getProjectsTasks($status = null) {
        $user = auth()->user();
        if ($status === '3') {
            $projects = $user->projects
                             ->where('status_id', 1)
                             ->where('ended_at', '<', Carbon::now()->toDateTimeString());      
        } else {
            $projects = $status ? $user->projects->where('status_id', $status) : $user->projects;
        }
        $data['projects'] = $projects->count();    
        
        $data['tasks'] = 0;
        foreach ($user->projects as $project) {
            if ($status === '3') {
                $tasks = $project->tasks
                                 ->where('status_id', 1)
                                 ->where('ended_at', '<', Carbon::now()->toDateTimeString()); 
            } else {
                $tasks = $status ? $project->tasks->where('status_id', $status) : $project->tasks;
            } 
            if ($tasks) {
                $data['tasks'] = $data['tasks'] + $tasks->count();
            }
        }

        return response()->json($data, 200);
    }

    public function getErrors() {
        $user = auth()->user();
        $projects = $user->projects;

        $data['errors'] = 0;
        $data['compilation'] = 0;
        $data['syntax'] = 0;
        $data['semantics'] = 0;
        $data['logic'] = 0;
        $data['planning'] = 0;
        $data['average'] = 0;
        $qtdTask = 0;
        foreach ($projects as $project) {
            $tasks = $project->tasks;
            if ($tasks) {
                foreach ($tasks as $task) {
                    $reports = $task->reports;
                    $qtdTask++;
                    if ($reports) {
                        foreach ($reports as $report) {
                            if ($report && $report->errorReport) {
                                $data['errors'] = $data['errors'] + $report->errorReport->errors->count();
                                $data['compilation'] = $data['compilation'] + $report->errorReport->errors->where('error_type_id', 1)->count();
                                $data['syntax'] = $data['syntax'] + $report->errorReport->errors->where('error_type_id', 2)->count();
                                $data['semantics'] = $data['semantics'] + $report->errorReport->errors->where('error_type_id', 3)->count();
                                $data['logic'] = $data['logic'] + $report->errorReport->errors->where('error_type_id', 4)->count();
                                $data['planning'] = $data['planning'] + $report->errorReport->errors->where('error_type_id', 5)->count();
                            }
                        }
                    }
                }
            }
        }

        $data['average'] = $data['errors'] / $qtdTask;
        $data['average'] = number_format((float)$data['average'], 1, ',', '');
        
        return response()->json($data, 200);
    }

    public function getTasksByCategory() {
        $user = auth()->user();
        $projects = $user->projects;

        $data['modeling'] = 0;
        $data['documentation'] = 0;
        $data['programming'] = 0;
        $data['design'] = 0;
        $data['test'] = 0;
        $data['other'] = 0;
        foreach ($projects as $project) {
            $tasks = $project->tasks;
            $data['modeling'] = $data['modeling'] + $tasks->where('category_id', 1)->count();
            $data['documentation'] = $data['documentation'] + $tasks->where('category_id', 2)->count();
            $data['design'] = $data['design'] + $tasks->where('category_id', 3)->count();
            $data['programming'] = $data['programming'] + $tasks->where('category_id', 4)->count();
            $data['test'] = $data['test'] + $tasks->where('category_id', 5)->count();
            $data['other'] = $data['other'] + $tasks->where('category_id', 6)->count();
        }

        return response()->json($data, 200);
    }
}
