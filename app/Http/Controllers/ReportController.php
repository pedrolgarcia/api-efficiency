<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\Annotation;
use App\TimeReport;
use App\ErrorReport;
use App\Error;
use App\ErrorType;
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

    public function getErrorTypes() {
        $errorTypes = ErrorType::all();
        return response()->json($errorTypes, 200);
    }

    public function getErrorReport($taskId) {
        $reports = Report::where('task_id', $taskId)->get();
        if ($reports) {
            foreach ($reports as $report) {
                if ($report && $report->errorReport) {
                    $report->errorReport->errors;
                    return response()->json($report, 200);  
                }
            }
        }

        return response()->json(['error' => 'Nenhum relatório de tempo registrado nesta tarefa.', 'message' => 'Registre um agora mesmo!'], 400);
    }
    
    public function saveErrorReport(Request $request, $taskId) {
        $reports = Report::where('task_id', $taskId)->get();
        if ($reports) {
            foreach ($reports as $report) {
                if ($report && $report->errorReport) {
                    $report->update([
                        'task_id' => $taskId,
                        'updated_at' => Carbon::now()
                    ]);
                    $report->errorReport->update([
                        'report_id' => $report->id
                    ]); 
                    foreach($request->data as $req) {
                        $error = $report->errorReport->errors->find($req['id']);
                        if($error) {
                            $altered = true;
                            $error->update([
                                'occurrences' => $req['ocorrencias'],
                                'error_type_id' => $req['tipo'],
                                'attempts_solve' => $req['tentativas'],
                                'discovery_at' => $req['descobrimento'],
                                'removed_at' => $req['remocao'],
                                'description' => $req['descricao'],
                                'error_report_id' => $report->errorReport->id

                            ]);
                        } else {
                            $created = true;
                            Error::create([
                                'occurrences' => $req['ocorrencias'],
                                'error_type_id' => $req['tipo'],
                                'attempts_solve' => $req['tentativas'],
                                'discovery_at' => $req['descobrimento'],
                                'removed_at' => $req['remocao'],
                                'description' => $req['descricao'],
                                'error_report_id' => $report->errorReport->id
                            ]);
                        }
                    }
                    if (isset($created)) {
                        return response()->json(['success' => 'Relatórios de erro criado com sucesso!'], 200);    
                    }
                    
                    return response()->json(['success' => 'Relatórios de erro alterado com sucesso!'], 200);    
                }
            }
        }

        $report = Report::create(['task_id' => $taskId]);
        ErrorReport::create([
            'report_id' => $report->id
        ]);
        foreach($request->data as $req) {
            Error::create([
                'occurrences' => $req['ocorrencias'],
                'error_type_id' => $req['tipo'],
                'attempts_solve' => $req['tentativas'],
                'discovery_at' => $req['descobrimento'],
                'removed_at' => $req['remocao'],
                'description' => $req['descricao'],
                'error_report_id' => $report->errorReport->id
            ]);
        }

        return response()->json(['success' => 'Relatório de erro criado com sucesso!'], 200);
    }

    public function deleteErrorReport($id) {
        $error = Error::find($id);
        if ($error) {            
            $error->delete();
            if(Error::all()->count() === 0) {
                $error->errorReport->delete();
                $error->errorReport->report->delete();
            }
            return response()->json(['success' => 'Relatório de erro excluído com sucesso!'], 200);
        }
    }
}
