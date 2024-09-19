<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'getEventsForShow']);
Route::get('/events/{id}/places', [EventController::class, 'getPlaces']);
Route::post('/events/{id}/reserve', [EventController::class, 'reserve']);
