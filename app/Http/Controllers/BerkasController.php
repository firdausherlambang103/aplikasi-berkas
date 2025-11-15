<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klien;
use App\Models\Berkas;
use App\Models\WaPlaceholder; // Pastikan ini ada

class BerkasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relasi 'klien' dan 'waLogs'
        $semuaBerkas = Berkas::with('klien', 'waLogs')->latest()->get();
        
        // Ambil semua template
        $templates = \App\Models\WaTemplate::all(); 
 
        // Ambil semua placeholder
        $placeholders = WaPlaceholder::all();
 
        return view('berkas.index', compact('semuaBerkas', 'templates', 'placeholders'));
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
            'nomer_berkas' => 'nullable|string|max:100|unique:berkas,nomer_berkas',
            'klien_id' => 'nullable|exists:klien,id',
            'nama_pemohon' => 'required|string|max:255',
            'nomer_wa' => 'nullable|string|max:25',
            
            // --- PERBAIKAN: Menyamakan validasi dengan 'update' ---
            'jenis_hak' => 'required|in:SHGB,SHM,SHW,SHP,Leter C', 

            'nomer_hak' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'jenis_permohonan' => 'required|string',
            'spa' => 'nullable|string',
            'alih_media' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kode_biling' => 'nullable|string|max:100',
            'jumlah_bayar' => 'nullable|numeric',
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berkas $berka)
    {
        $klienTersedia = \App\Models\Klien::all(); 
        
        // Variabel ini tidak lagi dibutuhkan karena Nomer WA diisi manual
        // $nomer_wa_saat_ini = $berka->klien ? $berka->klien->nomer_wa : null;
        
        return view('berkas.edit', compact('berka', 'klienTersedia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berkas $berka)
    {
        $request->validate([
            // Pastikan validasi unique mengabaikan data saat ini
            'nomer_berkas' => 'nullable|string|max:100|unique:berkas,nomer_berkas,' . $berka->id,
            'klien_id' => 'nullable|exists:klien,id',
            'nama_pemohon' => 'required|string|max:255',
            'nomer_wa' => 'nullable|string|max:25',
            'jenis_hak' => 'required|in:SHGB,SHM,SHW,SHP,Leter C',
            'nomer_hak' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'jenis_permohonan' => 'required|string',
            'spa' => 'nullable|string',
            'alih_media' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kode_biling' => 'nullable|string|max:100',
            'jumlah_bayar' => 'nullable|numeric',
        ]);
 
        $berka->update($request->all()); 
 
        return redirect()->route('berkas.index')
                         ->with('success', 'Data Berkas Nomer Hak ' . $berka->nomer_hak . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berkas $berka)
    {
        $nomer_hak = $berka->nomer_hak;
        $berka->delete(); 
        
        return redirect()->route('berkas.index')
                         ->with('success', 'Data Berkas Nomer Hak ' . $nomer_hak . ' berhasil dihapus.');
    }
}