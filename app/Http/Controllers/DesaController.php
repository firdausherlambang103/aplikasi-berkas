<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    public function index()
    {
        $desas = Desa::with('kecamatan')->orderBy('nama', 'asc')->paginate(10);
        return view('desa.index', compact('desas'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::orderBy('nama', 'asc')->get();
        return view('desa.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama' => 'required|string|max:255',
        ]);
        Desa::create($request->all());
        return redirect()->route('desa.index')->with('success', 'Desa berhasil disimpan.');
    }

    public function edit(Desa $desa)
    {
        $kecamatans = Kecamatan::orderBy('nama', 'asc')->get();
        return view('desa.edit', compact('desa', 'kecamatans'));
    }

    public function update(Request $request, Desa $desa)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama' => 'required|string|max:255',
        ]);
        $desa->update($request->all());
        return redirect()->route('desa.index')->with('success', 'Desa berhasil diperbarui.');
    }

    public function destroy(Desa $desa)
    {
        $desa->delete();
        return redirect()->route('desa.index')->with('success', 'Desa berhasil dihapus.');
    }

    public function getDesaByKecamatan($id)
    {
        $desas = Desa::where('kecamatan_id', $id)->orderBy('nama', 'asc')->get();
        return response()->json($desas);
    }
}