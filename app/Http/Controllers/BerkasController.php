<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klien;
use App\Models\Berkas;

class BerkasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data dengan relasi 'klien'
        $semuaBerkas = Berkas::with('klien')->latest()->get();
        return view('berkas.index', compact('semuaBerkas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua klien untuk dropdown
        $klienTersedia = Klien::all();
        return view('berkas.create', compact('klienTersedia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'jenis_hak' => 'required',
        'nomer_hak' => 'required|string|max:100',
        'kecamatan' => 'required|string|max:100',
        'desa' => 'required|string|max:100',
        'jenis_permohonan' => 'required|string',
        'klien_id' => 'nullable|exists:klien,id', // Validasi klien_id
        'spa' => 'nullable|string',
        'alih_media' => 'nullable|string',
        'keterangan' => 'nullable|string',
        ]);

        Berkas::create($request->all());

        return redirect()->route('berkas.index')
                        ->with('success', 'Berkas berhasil diregistrasi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // (Biasanya tidak digunakan di CRUD resource standar, bisa dibiarkan kosong)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berkas $berka)
    {
        // Menggunakan \App\Models\Klien::all() untuk jaga-jaga jika 'use' terlewat
        $klienTersedia = \App\Models\Klien::all(); 
        $nomer_wa_saat_ini = $berka->klien ? $berka->klien->nomer_wa : null;
        
        // Pastikan mengirim variabel $berka (bukan $berkas)
        return view('berkas.edit', compact('berka', 'klienTersedia', 'nomer_wa_saat_ini'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berkas $berka)
    {
        $request->validate([
            'jenis_hak' => 'required|in:SHGB,SHM,SHW,SHP,Leter C', // Validasi enum
            'nomer_hak' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'jenis_permohonan' => 'required|string',
            'klien_id' => 'nullable|exists:klien,id', // Validasi klien_id opsional
            'spa' => 'nullable|string',
            'alih_media' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // PERBAIKAN: Gunakan $berka, bukan $berkas
        $berka->update($request->all()); 

        return redirect()->route('berkas.index')
                         // PERBAIKAN: Gunakan $berka, bukan $berkas
                         ->with('success', 'Data Berkas Nomer Hak ' . $berka->nomer_hak . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berkas $berka)
    {
        // PERBAIKAN: Gunakan $berka, bukan $berkas
        $nomer_hak = $berka->nomer_hak;
        
        // PERBAIKAN: Tambahkan titik koma (;)
        $berka->delete(); 
        
        return redirect()->route('berkas.index')
                         ->with('success', 'Data Berkas Nomer Hak ' . $nomer_hak . ' berhasil dihapus.');
    }
}
// PERBAIKAN: Kurung kurawal '}' yang berlebihan sudah dihapus dari sini.