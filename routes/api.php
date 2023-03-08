<?php

    use App\Http\Controllers\Api\AuthController;
    use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
    Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $currentUser = \Illuminate\Support\Facades\Auth::user();
    return  $currentUser->id;
});

Route::middleware('auth:sanctum')->prefix('pjm')->group(function (){
    Route::post('add-client', [\App\Http\Controllers\ClienteController::class, 'addClient'])->middleware('permission:add-client')->name('add.client');
    Route::post('add-project', [\App\Http\Controllers\ProgettoController::class, 'addProject'])->middleware('permission:add-project')->name('add.project');
    Route::post('add-task', [\App\Http\Controllers\TaskController::class, 'addTask'])->middleware('permission:add-task|add-dev')->name('add.task');
});

    Route::middleware('auth:sanctum')->prefix('dev')->group(function (){
        Route::post('edit-status', [\App\Http\Controllers\TaskController::class, 'editStatus'])->middleware('permission:edit-status')->name('edit.status');

    });
