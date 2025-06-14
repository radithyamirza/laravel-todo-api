<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::post('/todos', [TodoController::class, 'store']);
Route::get('/todos/export', [TodoController::class, 'exportExcel']);