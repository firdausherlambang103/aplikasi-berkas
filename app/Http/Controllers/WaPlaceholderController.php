<?php

namespace App\Http\Controllers;

use App\Models\WaPlaceholder;
use Illuminate\Http\Request;

class WaPlaceholderController extends Controller
{
    public function index()
    {
        $placeholders = WaPlaceholder::all();
        return view('wa_placeholders.index', compact('placeholders'));
    }

    public function create()
    {
        return view('wa_placeholders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'placeholder_key' => 'required|string|max:255|unique:wa_placeholders,placeholder_key',
            'deskripsi' => 'required|string|max:255',
            'data_source' => 'required|string|max:255',
        ]);

        // Validasi format key
        if (substr($request->placeholder_key, 0, 1) !== '[' || substr($request->placeholder_key, -1) !== ']') {
            return back()->withInput()->withErrors(['placeholder_key' => 'Format placeholder harus diawali [ dan diakhiri ]. Contoh: [nama]']);
        }
        
        // Validasi format data_source
        if (!preg_match('/^[a-z_]+\.[a-z_]+$/', $request->data_source)) {
             return back()->withInput()->withErrors(['data_source' => 'Format Data Source harus [tabel].[kolom]. Contoh: klien.nama_klien atau berkas.nomer_hak']);
        }

        WaPlaceholder::create($request->all());

        return redirect()->route('wa-placeholders.index')
                         ->with('success', 'Placeholder berhasil ditambahkan.');
    }

    public function edit(WaPlaceholder $waPlaceholder)
    {
        return view('wa_placeholders.edit', compact('waPlaceholder'));
    }

    public function update(Request $request, WaPlaceholder $waPlaceholder)
    {
        $request->validate([
            'placeholder_key' => 'required|string|max:255|unique:wa_placeholders,placeholder_key,' . $waPlaceholder->id,
            'deskripsi' => 'required|string|max:255',
            'data_source' => 'required|string|max:255',
        ]);

        if (substr($request->placeholder_key, 0, 1) !== '[' || substr($request->placeholder_key, -1) !== ']') {
            return back()->withInput()->withErrors(['placeholder_key' => 'Format placeholder harus diawali [ dan diakhiri ]. Contoh: [nama]']);
        }
        
        if (!preg_match('/^[a-z_]+\.[a-z_]+$/', $request->data_source)) {
             return back()->withInput()->withErrors(['data_source' => 'Format Data Source harus [tabel].[kolom]. Contoh: klien.nama_klien atau berkas.nomer_hak']);
        }

        $waPlaceholder->update($request->all());

        return redirect()->route('wa-placeholders.index')
                         ->with('success', 'Placeholder berhasil diperbarui.');
    }

    public function destroy(WaPlaceholder $waPlaceholder)
    {
        $waPlaceholder->delete();
        return redirect()->route('wa-placeholders.index')
                         ->with('success', 'Placeholder berhasil dihapus.');
    }
}