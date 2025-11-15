<?php

namespace App\Http\Controllers;

use App\Models\Klien;
use Illuminate\Http\Request;

class KlienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $klien = Klien::latest()->get();
        return view('klien.index', compact('klien'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('klien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'kode_klien' => 'required|string|max:50|unique:klien,kode_klien',
            'nama_klien' => 'required|string|max:255',
            // Nomer WA harus diawali dengan kode negara, e.g., 6281xxxx
            'nomer_wa' => 'required|string|max:20', 
        ]);

        Klien::create($request->all());

        return redirect()->route('klien.index')
                         ->with('success', 'Klien dengan kode ' . $request->kode_klien . ' berhasil ditambahkan.');
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
    public function edit(Klien $klien)
    {
        return view('klien.edit', compact('klien'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Klien $klien)
    {
        $request->validate([
            // Memastikan kode unik, kecuali untuk dirinya sendiri
            'kode_klien' => 'required|string|max:50|unique:klien,kode_klien,' . $klien->id,
            'nama_klien' => 'required|string|max:255',
            'nomer_wa' => 'required|string|max:20',
        ]);

        $klien->update($request->all());

        return redirect()->route('klien.index')
                         ->with('success', 'Klien ' . $klien->nama_klien . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Klien $klien)
    {
        // Berkas yang berelasi dengan klien ini akan diset NULL karena 
        // kita menggunakan onDelete('set null') di migrasi.
        $klien->delete();

        return redirect()->route('klien.index')
                         ->with('success', 'Klien ' . $klien->nama_klien . ' berhasil dihapus.');
    }

    public function getById($id)
    {
        $klien = \App\Models\Klien::find($id);

        if ($klien) {
            return response()->json([
                'success' => true,
                'nomer_wa' => $klien->nomer_wa,
                'nama_klien' => $klien->nama_klien 
                // Kita juga bisa kirim nama, untuk jaga-jaga
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Klien tidak ditemukan'], 404);
    }
}
