<?php

namespace App\Http\Controllers;

use App\Models\JenisPermohonan;
use Illuminate\Http\Request;

class JenisPermohonanController extends Controller
{
    public function index()
    {
        $permohonans = JenisPermohonan::orderBy('nama', 'asc')->paginate(10);
        return view('jenis_permohonan.index', compact('permohonans'));
    }

    public function create()
    {
        return view('jenis_permohonan.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:jenis_permohonans,nama']);
        JenisPermohonan::create($request->all());
        return redirect()->route('jenis-permohonan.index')->with('success', 'Jenis Permohonan berhasil disimpan.');
    }

    public function edit(JenisPermohonan $jenisPermohonan)
    {
        return view('jenis_permohonan.edit', compact('jenisPermohonan'));
    }

    public function update(Request $request, JenisPermohonan $jenisPermohonan)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:jenis_permohonans,nama,' . $jenisPermohonan->id]);
        $jenisPermohonan->update($request->all());
        return redirect()->route('jenis-permohonan.index')->with('success', 'Jenis Permohonan berhasil diperbarui.');
    }

    public function destroy(JenisPermohonan $jenisPermohonan)
    {
        $jenisPermohonan->delete();
        return redirect()->route('jenis-permohonan.index')->with('success', 'Jenis Permohonan berhasil dihapus.');
    }
}