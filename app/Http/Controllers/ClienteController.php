<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    public function addClient(Request $request){
        try {
            $validateClient = Validator::make($request->all(),
                [
                    'nome' => 'required',
                    'cognome' => 'required'
                ]);

            if($validateClient->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateClient->errors()
                ], 401);
            }

            $cliente = Cliente::create([
                'nome' => $request->nome,
                'cognome' => $request->cognome,
            ]);

            if ($cliente){
                return response()->json([
                    'status' => true,
                    'message' => 'Cliente aggiunto correttamente!',
                    'cliente' => [
                        'nome' => $cliente->nome,
                        'cognome' => $cliente->cognome
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
