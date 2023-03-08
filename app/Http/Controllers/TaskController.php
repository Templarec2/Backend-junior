<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Progetto;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function addTask(Request $request){

        try {
            $validateTask = Validator::make($request->all(),
                [
                    'titolo' => 'required|unique:App\Models\Task,titolo',
                    'descrizione' => 'required',
                    'progetto' => 'required|exists:App\Models\Progetto,nome',
                    'developer' => 'required|exists:App\Models\User,name',
                    'priorita' => Rule::in([0,1,2])
                ]);

            if($validateTask->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateTask->errors()
                ], 400);
            }

            $progetto = Progetto::where('nome', $request->progetto)->first();
            $developer = User::where('name', $request->developer)->first();
            if (!$developer->hasRole('developer')){
                return response()->json([
                    'status' => false,
                    'message' => $developer->name.  ' non ha il ruolo di developer'
                ], 400);
            }
            $priorita = null;
            if ($request->priorita) {
                $priorita = $request->priorita;
            }
            $task = Task::create([
                'titolo' => $request->titolo,
                'descrizione' => $request->descrizione,
                'developer' => $developer->id,
                'stato' => 'To do',
                'progetto_id' => $progetto->id,
                'priorita' => $priorita ?: 1
            ]);

            if ($progetto){
                return response()->json([
                    'status' => true,
                    'message' => 'Task aggiunta correttamente!',
                    'task' => [
                        'progetto' => $progetto->nome,
                        'task' =>$task->titolo,
                        'developer' => $developer->nome
                    ],

                ],201);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function editStatus(Request $request){

        try {
            $validateTask = Validator::make($request->all(),
                [
                    'progetto' =>'required|exists:App\Models\Progetto,nome',
                    'task' => 'required|exists:App\Models\Task,titolo',
                    'stato' => [
                        'required',
                        Rule::in(['Backlog', 'To do', 'In progress', 'Done'])
                        ],

                ]);

            if($validateTask->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateTask->errors()
                ], 401);
            }
            $currentUser = Auth::user();

            $progetto = Progetto::where('nome', $request->progetto)->first();
            $task = Task::where('titolo', $request->task )->first();


            if ($task->developer != $currentUser->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Questa task non appartiene all\'utente corrente',
                ], 401);
            }

            $task->stato = $request->stato;
            $task->save();
            if ($task){
                return response()->json([
                    'status' => true,
                    'message' => 'Task aggiornata correttamente!',
                    'task' => [
                        'stato' => $task->stato,

                    ],

                ],201);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
