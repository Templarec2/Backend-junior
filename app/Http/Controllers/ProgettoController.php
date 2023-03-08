<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Progetto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProgettoController extends Controller
{
    public function addProject(Request $request){
        try {
            $validateProject = Validator::make($request->all(),
                [
                    'nome' => 'required|unique:App\Models\Progetto,nome',
                    'cliente_cognome' => 'required|exists:App\Models\Cliente,cognome',
                    'cliente_nome' => 'required|exists:App\Models\Cliente,nome'
                ]);

            if($validateProject->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateProject->errors()
                ], 400);
            }

            $cliente = Cliente::where('cognome', $request->cliente_cognome)->where('nome', $request->cliente_nome)->first();
            if (!$cliente){
                return response()->json([
                    'status' => false,
                    'message' => $request->cliente_nome . ' ' . $request->cliente_cognome . ' non trovato'
                ], 404);
            }

            $progetto = Progetto::create([
                'nome' => $request->nome,
                'cliente_id' => $cliente->id,
                'pjm_id' => Auth::user()->id,
            ]);
            if ($progetto){
                return response()->json([
                    'status' => true,
                    'message' => 'Progetto aggiunto correttamente!',
                    'progetto' => [
                        'nome' => $progetto->nome,
                        'cliente' =>$cliente->nome . ' ' . $cliente->cognome
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
