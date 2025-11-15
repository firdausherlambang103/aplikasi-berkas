<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\Klien; // PENTING: Pastikan Klien model di-import
use Illuminate\Http\Request;
use App\Models\WaTemplate; // Diambil dari file asli Anda
use App\Services\WaNotificationService; // Diambil dari file asli Anda

class BerkasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $berkas = Berkas::with('klien')->latest()->paginate(10);
        return view('berkas.index', compact('berkas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // FUNGSI INI DITAMBAHKAN
        // Kita mengambil semua data klien untuk dikirim ke dropdown
        // 'klienTersedia' harus cocok dengan nama variabel di @foreach pada view
        $klienTersedia = Klien::orderBy('nama_klien', 'asc')->get();

        // Kirim data klien ke view
        return view('berkas.create', compact('klienTersedia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, WaNotificationService $waService)
    {
        $validated = $request->validate([
            'nomer_berkas' => 'nullable|string|max:255',
            'klien_id' => 'nullable|exists:kliens,id',
            'nama_pemohon' => 'required|string|max:255',
            'nomer_wa' => 'nullable|string|max:20',
            'jenis_hak' => 'required|string|max:50',
            'nomer_hak' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'jenis_permohonan' => 'required|string|max:255',
            'spa' => 'nullable|string',
            'alih_media' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kode_biling' => 'nullable|string|max:255',
            'jumlah_bayar' => 'nullable|numeric',
        ]);

        $berkas = Berkas::create($validated);

        // Cek jika nomer WA ada dan template 'registrasi' aktif
        if ($berkas->nomer_wa) {
            $waService->sendNotificationOnCreate($berkas);
        }

        return redirect()->route('berkas.index')->with('success', 'Registrasi berkas baru berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Berkas  $berkas
     * @return \Illuminate\Http\Response
     */
    public function edit(Berkas $berkas)
    {
        $klienTersedia = Klien::orderBy('nama_klien', 'asc')->get();
        $templates = WaTemplate::where('status', 'aktif')->get();
        return view('berkas.edit', compact('berkas', 'klienTersedia', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Berkas  $berkas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Berkas $berkas)
    {
        $validated = $request->validate([
            'nomer_berkas' => 'nullable|string|max:255',
            'klien_id' => 'nullable|exists:kliens,id',
            'nama_pemohon' => 'required|string|max:255',
            'nomer_wa' => 'nullable|string|max:20',
            'jenis_hak' => 'required|string|max:50',
            'nomer_hak' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'jenis_permohonan' => 'required|string|max:255',
            'spa' => 'nullable|string',
            'alih_media' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'status' => 'required|string|max:50',
            'posisi' => 'required|string|max:255',
            'kode_biling' => 'nullable|string|max:255',
            'jumlah_bayar' => 'nullable|numeric',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $berkas->update($validated);

        return redirect()->route('berkas.index')->with('success', 'Data berkas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Berkas  $berkas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Berkas $berkas)
    {
        $berkas->delete();
        return redirect()->route('berkas.index')->with('success', 'Berkas berhasil dihapus.');
    }
}