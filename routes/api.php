<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaLogController;
use App\Http\Controllers\KlienController; // <-- PASTIKAN IMPORT INI ADA
use App\Http\Controllers\KlienController; // Tambahkan ini
use App\Http\Controllers\BerkasController; // Tambahkan ini

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// --- TAMBAHKAN ROUTE INI ---
Route::get('/klien/get-by-id/{id}', [KlienController::class, 'getById']);

// Ini untuk logging (sudah ada)
Route::post('/log-wa-send', [WaLogController::class, 'store']);


// API BARU UNTUK DEPENDENT DROPDOWN
Route::get('/get-desa/{kecamatan_id}', [BerkasController::class, 'getDesaApi']);