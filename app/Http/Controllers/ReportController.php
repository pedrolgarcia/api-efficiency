<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\Annotation;

class ReportController extends Controller
{
    public function getAnnotation($taskId) {
        $report = Report::where('task_id', $taskId);
        $annotation = Annotation::where('report_id', $report->id);

        return response()->json($report, 200);  
    }
    
    public function saveAnnotation(Request $request, $taskId) {
        if(isset($request->id_anotacao)) {
            $report = Report::where('task_id', $taskId);
            $report->update(['task_id' => $taskId]);
            Annotation::create([
                'annotation' => $request->anotacao,
                'report_id' => $report->id
            ]); 
        } else {
            $report = Report::create(['task_id' => $taskId]);
            Annotation::create([
                'annotation' => $request->anotacao,
                'report_id' => $report->id
            ]);
        }

        return response()->json(['success' => 'Anotação criada com sucesso!'], 200);
    }
}
