<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LecturaController;
use App\Models\Zona;

Route::post('/lecturas', [LecturaController::class, 'store']);

Route::get('/zonas', function () {
    return Zona::all();
});

Route::get('/zonas/{id}', function ($id) {
    return Zona::findOrFail($id);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');