<?php

use App\Http\Controllers\Api\Crm\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// CRM Module Routes
Route::middleware('auth:sanctum')->group(function () {
    // Tasks
    Route::apiResource('tasks', TaskController::class);
    Route::put('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
}); 