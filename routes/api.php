<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KlienController; // Tambahkan ini
use App\Http\Controllers\BerkasController; // Tambahkan ini

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

// API LAMA (Boleh dihapus jika tidak dipakai)
Route::get('/klien/get-by-id/{id}', [KlienController::class, 'getById']);

// API BARU UNTUK DEPENDENT DROPDOWN
// Route::get('/get-desa/{kecamatan_id}', [BerkasController::class, 'getDesaApi']); // <-- HAPUS BARIS INI