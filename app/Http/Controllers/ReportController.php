<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\Annotation;
use App\TimeReport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function getAnnotation($taskId) {
        $reports = Report::where('task_id', $taskId)->get();
        if ($reports) {
            foreach ($reports as $report) {
                if ($report && $report->annotation) {
                    return response()->json($report, 200);  
                }
            }
        }

        return response()->json(['error' => 'Nenhuma anotação encontrada para esta tarefa.', 'message' => 'Crie uma agora mesmo!'], 400);  
    }
    
    public function saveAnnotation(Request $request, $taskId) {
        $reports = Report::where('task_id', $taskId !== 'undefined' ? $taskId : $request->tarefa)->get();
        if ($reports) {
            foreach ($reports as $report) {
                if ($report && $report->annotation) {
                    $report->update([
                        'task_id' => $taskId !== 'undefined' ? $taskId : $request->tarefa,
                        'updated_at' => Carbon::now()
                    ]);
                    $report->annotation->update([
                        'annotation' => $request->anotacao,
                        'report_id' => $report->id
                    ]); 

                    return response()->json(['success' => 'Anotação alterada com sucesso!'], 200);
                }
            }
        }
        
        $report = Report::create(['task_id' => $taskId !== 'undefined' ? $taskId : $request->tarefa]);
        Annotation::create([
            'annotation' => $request->anotacao,
            'report_id' => $report->id
        ]);

        return response()->json(['success' => 'Anotação criada com sucesso!'], 200);
    }

    public function deleteAnnotation($taskId) {
        $reports = Report::where('task_id', $taskId)->get();
        if ($reports) {
            foreach ($reports as $report) {
                if ($report && $report->annotation) {
                    $report->delete();
                    $report->annotation->delete();
                    return response()->json(['success' => 'Anotação excluída com sucesso!'], 200);
                }
            }
        }
    }

    public function getTimeReport($taskId) {
        $reports = Report::where('task_id', $taskId)->get();
        if ($reports) {
            foreach ($reports as $report) {
                if ($report && $report->timeReport) {
                    return response()->json($report, 200);  
                }
            }
        }

        return response()->json(['error' => 'Nenhum relatório de tempo registrado nesta tarefa.', 'message' => 'Registre um agora mesmo!'], 400);
    }
    
    public function saveTimeReport(Request $request, $taskId) {
        $reports = Report::where('task_id', $taskId)->get();
        if ($reports) {
            foreach ($reports as $report) {
                if ($report && $report->timeReport) {
                    $report->update([
                        'task_id' => $taskId,
                        'updated_at' => Carbon::now()
                    ]);
                    $report->timeReport->update([
                        'started_at' => $request->inicio,
                        'ended_at' => $request->fim,
                        'interruptions' => $request->interrupcoes,
                        'comment' => $request->comentario,
                        'report_id' => $report->id
                    ]); 
                    return response()->json(['success' => 'Relatório de tempo alterado com sucesso!'], 200);    
                }
            }
        }

        $report = Report::create(['task_id' => $taskId]);
        TimeReport::create([
            'started_at' => $request->inicio,
            'ended_at' => $request->fim,
            'interruptions' => $request->interrupcoes,
            'comment' => $request->comentario,
            'report_id' => $report->id
        ]);

        return response()->json(['success' => 'Relatório de tempo registrado com sucesso!'], 200);
    }

    public function deleteTimeReport($taskId) {
        $reports = Report::where('task_id', $taskId)->get();
        if ($reports) {
            foreach ($reports as $report) {
                if ($report && $report->timeReport) {
                    $report->delete();
                    $report->timeReport->delete();
                    return response()->json(['success' => 'Relatório excluído com sucesso!'], 200);
                }
            }
        }
    }
}
