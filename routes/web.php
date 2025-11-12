<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\KlienController;
use App\Http\Controllers\PengaturanController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Grup route yang memerlukan login
Route::middleware(['auth'])->group(function () {
    Route::resource('berkas', BerkasController::class);

    // Anda perlu membuat CRUD untuk Klien juga agar bisa mendaftarkan kode
    Route::resource('klien', KlienController::class); 
});

Route::middleware(['auth'])->group(function () {
    // ... (Route 'berkas' dan 'klien' Anda) ...

    // Route untuk Halaman Pengaturan
    Route::get('/pengaturan-pesan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan-pesan', [PengaturanController::class, 'update'])->name('pengaturan.update');
});