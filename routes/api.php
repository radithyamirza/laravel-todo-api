<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ChartController;

Route::post('/todos', [TodoController::class, 'store']);
Route::get('/todos/export', [TodoController::class, 'exportExcel']);
Route::get('/chart', [ChartController::class, 'chart']);