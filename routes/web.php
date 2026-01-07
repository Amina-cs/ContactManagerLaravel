<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactController::class, 'getAll']);

Route::post('/add', [ContactController::class, 'add']);
Route::put('/update/{id}', [ContactController::class, 'update']);
Route::delete('/delete/{id}', [ContactController::class, 'delete']);


Route::get('/getAll',[ContactController::class,'getAll']);

