<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\KlienController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\WaLogController;
// CONTROLLER BARU
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\JenisPermohonanController;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Grup route yang memerlukan login
Route::middleware(['auth'])->group(function () {
    Route::resource('berkas', BerkasController::class);
    Route::get('/pengaturan-pesan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan-pesan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    // Anda perlu membuat CRUD untuk Klien juga agar bisa mendaftarkan kode
    Route::resource('klien', KlienController::class); 
    Route::resource('wa-placeholders', \App\Http\Controllers\WaPlaceholderController::class);
        // RUTE BARU UNTUK DATA MASTER
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('desa', DesaController::class);
    // 'jenis-permohonan' karena nama tabelnya jamak
    Route::resource('jenis-permohonan', JenisPermohonanController::class);
    Route::resource('wa-templates', \App\Http\Controllers\WaTemplateController::class);
    Route::post('/log-wa-send', [WaLogController::class, 'store']);
    Route::get('/get-desa/{id}', [DesaController::class, 'getDesaByKecamatan'])->name('get.desa');
});
