<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lead\GetLeadController;
use App\Http\Controllers\Lead\PutLeadController;
use App\Http\Controllers\Lead\PostLeadController;
use App\Http\Controllers\Lead\DeleteLeadController;

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

Route::get('/lead/{lead_id}', [GetLeadController::class, '__invoke']);
Route::post('/lead', [PostLeadController::class, '__invoke']);
Route::put('/lead/{lead_id}', [PutLeadController::class, '__invoke']);
Route::delete('/lead/{uuid}', [DeleteLeadController::class, '__invoke']);

