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
// PENTING: Tambahkan Controller Admin
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\WhatsappWebController;
// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    
    // --- MODUL UTAMA ---
    Route::resource('berkas', BerkasController::class)->parameters(['berkas' => 'berkas']);
    
    Route::get('/pengaturan-pesan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan-pesan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    
    Route::resource('klien', KlienController::class); 
    Route::resource('wa-placeholders', WaPlaceholderController::class);
    
    // --- DATA MASTER ---
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('desa', DesaController::class);
    Route::resource('jenis-permohonan', JenisPermohonanController::class);
    Route::resource('wa-templates', WaTemplateController::class);
    
    // --- API & LOG ---
    Route::post('/log-wa-send', [WaLogController::class, 'store']);
    Route::get('/get-desa/{id}', [DesaController::class, 'getDesaByKecamatan'])->name('get.desa');

    // --- MENU ADMINISTRATOR (User & Riwayat) ---
    // Pastikan rute ini ada agar tidak 404
    Route::get('/users', [AdminController::class, 'indexUser'])->name('admin.users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    Route::get('/riwayat', [AdminController::class, 'indexRiwayat'])->name('admin.riwayat.index');
});
Route::group(['prefix' => 'whatsapp', 'as' => 'wa.'], function () {
    Route::get('/scan', [WhatsappWebController::class, 'index'])->name('index');
    Route::get('/status', [WhatsappWebController::class, 'getStatus'])->name('status');
    Route::get('/qr', [WhatsappWebController::class, 'getQr'])->name('qr');
    Route::post('/logout', [WhatsappWebController::class, 'logout'])->name('logout');
});