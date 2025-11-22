<?php

namespace App\Http\Controllers;

// PASTIKAN SEMUA MODEL INI ADA DI ATAS
use App\Models\Berkas;
use App\Models\Klien;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\JenisPermohonan;
use App\Models\WaTemplate;
use App\Models\WaPlaceholder;
use App\Models\User; // <--- PENTING: Agar tidak error Class not found
use App\Services\WaNotificationService;
use Illuminate\Http\Request;

class BerkasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Siapkan Query dengan Eager Loading (Relasi)
        // Menggunakan 'dataKecamatan' & 'dataDesa' sesuai perbaikan Model sebelumnya
        $query = Berkas::with(['klien', 'dataKecamatan', 'dataDesa', 'jenisPermohonan', 'waLogs']);

        // 2. Logika Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                $q->where('nomer_berkas', 'LIKE', "%{$search}%")
                  ->orWhere('nama_pemohon', 'LIKE', "%{$search}%")
                  ->orWhere('nomer_hak', 'LIKE', "%{$search}%")
                  ->orWhere('nomer_wa', 'LIKE', "%{$search}%")
                  // Cari berdasarkan nama Kecamatan (via Relasi)
                  ->orWhereHas('dataKecamatan', function($qKec) use ($search) {
                      $qKec->where('nama', 'LIKE', "%{$search}%");
                  })
                  // Cari berdasarkan nama Desa (via Relasi)
                  ->orWhereHas('dataDesa', function($qDesa) use ($search) {
                      $qDesa->where('nama', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 3. Eksekusi Query dengan Pagination
        $semuaBerkas = $query->latest()
                             ->paginate(10)
                             ->withQueryString(); // Agar parameter search tidak hilang saat pindah halaman
        
        $templates = WaTemplate::where('status_template', 'aktif')->get(); 
        $placeholders = WaPlaceholder::all();

        return view('berkas.index', compact('semuaBerkas', 'templates', 'placeholders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $klienTersedia = Klien::orderBy('nama_klien', 'asc')->get();
        $kecamatans = Kecamatan::orderBy('nama', 'asc')->get();
        $jenisPermohonans = JenisPermohonan::orderBy('nama', 'asc')->get();
        
        // Ambil data user untuk dropdown Korektor
        $users = User::orderBy('name', 'asc')->get();

        return view('berkas.create', compact('klienTersedia', 'kecamatans', 'jenisPermohonans', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, WaNotificationService $waService)
    {
        $validated = $request->validate([
            'nomer_berkas' => 'nullable|string|max:255',
            'klien_id' => 'nullable|exists:klien,id', // Pastikan tabel 'klien' (bukan kliens)
            'nama_pemohon' => 'required|string|max:255',
            'nomer_wa' => 'nullable|string|max:20',
            'jenis_hak' => 'required|string|max:50',
            'nomer_hak' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'desa_id' => 'required|exists:desas,id',
            'jenis_permohonan_id' => 'required|exists:jenis_permohonans,id',
            'spa' => 'nullable|string',
            'alih_media' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kode_biling' => 'nullable|string|max:255',
            'jumlah_bayar' => 'nullable|numeric',
            
            // Field Baru
            'korektor' => 'nullable|string', 
            'status' => 'required|string',   
        ]);

        $berkas = Berkas::create($validated);

        // Kirim notifikasi WA jika ada nomer WA
        if ($berkas->nomer_wa) {
            $waService->sendNotificationOnCreate($berkas);
        }

        return redirect()->route('berkas.index')->with('success', 'Registrasi berkas baru berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berkas $berkas)
    {
        $klienTersedia = Klien::orderBy('nama_klien', 'asc')->get();
        $templates = WaTemplate::where('status_template', 'aktif')->get(); 
        $kecamatans = Kecamatan::orderBy('nama', 'asc')->get();
        $jenisPermohonans = JenisPermohonan::orderBy('nama', 'asc')->get();
        $users = User::orderBy('name', 'asc')->get(); // Data korektor
        
        // Ambil desa yang sesuai dengan kecamatan berkas saat ini
        $desas = Desa::where('kecamatan_id', $berkas->kecamatan_id)->orderBy('nama', 'asc')->get();

        return view('berkas.edit', compact(
            'berkas', 
            'klienTersedia', 
            'templates', 
            'kecamatans', 
            'jenisPermohonans', 
            'desas',
            'users'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berkas $berkas)
    {
        $validated = $request->validate([
            'nomer_berkas' => 'nullable|string|max:255',
            'klien_id' => 'nullable|exists:klien,id',
            'nama_pemohon' => 'required|string|max:255',
            'nomer_wa' => 'nullable|string|max:20',
            'jenis_hak' => 'required|string|max:50',
            'nomer_hak' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'desa_id' => 'required|exists:desas,id',
            'jenis_permohonan_id' => 'required|exists:jenis_permohonans,id',
            'spa' => 'nullable|string',
            'alih_media' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kode_biling' => 'nullable|string|max:255',
            'jumlah_bayar' => 'nullable|numeric',
            
            // Field Update (Posisi & Tanggal Selesai DIHAPUS sesuai permintaan)
            'status' => 'required|string', 
            'korektor' => 'nullable|string',
        ]);

        $berkas->update($validated);

        return redirect()->route('berkas.index')->with('success', 'Data berkas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berkas $berkas)
    {
        $berkas->delete();
        return redirect()->route('berkas.index')->with('success', 'Berkas berhasil dihapus.');
    }

    /**
     * FUNGSI API UNTUK MENGAMBIL DESA BERDASARKAN KECAMATAN
     */
    public function getDesaApi(Request $request, $kecamatan_id)
    {
        // Mengambil data desa, diurutkan berdasarkan nama
        $desas = Desa::where('kecamatan_id', $kecamatan_id)->orderBy('nama', 'asc')->get();
        return response()->json($desas);
    }
}