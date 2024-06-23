<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RoomController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('patients')->group(function () {
    Route::post('/admit', [PatientController::class, 'admit']);
    Route::post('/discharge/{id}', [PatientController::class, 'discharge']);
    Route::get('/list', [PatientController::class, 'list']);
});

Route::get('/rooms/available', [PatientController::class, 'availableRooms']);
Route::get('/rooms/occupied', [PatientController::class, 'occupiedRooms']);
