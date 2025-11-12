use App\Http\Controllers\KlienController;

// Route untuk mengambil nomer WA berdasarkan kode
Route::get('/klien/get-by-kode/{kode}', [KlienController::class, 'getByKode']);