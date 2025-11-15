<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaLogController;
use App\Http\Controllers\KlienController; // <-- TAMBAHKAN IMPORT INI

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

// HAPUS route lama 'get-by-kode' jika ada, karena kita akan pakai ID
// Route::get('/klien/get-by-kode/{kode}', [KlienController::class, 'getByKode']);

// TAMBAHKAN ENDPOINT BARU INI (mengambil data by ID)
Route::get('/klien/get-by-id/{id}', [KlienController::class, 'getById']);

Route::post('/log-wa-send', [WaLogController::class, 'store']);