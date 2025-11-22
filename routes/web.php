<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\KlienController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\WaLogController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\JenisPermohonanController;
use App\Http\Controllers\WaTemplateController;
use App\Http\Controllers\WaPlaceholderController;

// --- PERBAIKAN 1: REDIRECT ROOT KE LOGIN ---
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Gunakan parameters untuk memperbaiki error pluralisasi (berkas vs berka)
    Route::resource('berkas', BerkasController::class)->parameters(['berkas' => 'berkas']);
    
    Route::get('/pengaturan-pesan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan-pesan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    
    Route::resource('klien', KlienController::class); 
    Route::resource('wa-placeholders', WaPlaceholderController::class);
    
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('desa', DesaController::class);
    Route::resource('jenis-permohonan', JenisPermohonanController::class);
    Route::resource('wa-templates', WaTemplateController::class);
    
    Route::post('/log-wa-send', [WaLogController::class, 'store']);
    
    // Route API untuk AJAX
    Route::get('/get-desa/{id}', [DesaController::class, 'getDesaByKecamatan'])->name('get.desa');
});