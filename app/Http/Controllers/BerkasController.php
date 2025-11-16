<?php

namespace App\Http\Controllers;

// ... (use statements lainnya) ...

class BerkasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $semuaBerkas = Berkas::with('klien', 'kecamatan', 'desa', 'jenisPermohonan')
                            ->latest()
                            ->paginate(10);
        
        $templates = WaTemplate::where('status_template', 'aktif')->get(); // <-- PERBAIKAN: Harus 'status_template'
        $placeholders = WaPlaceholder::all();

        return view('berkas.index', compact('semuaBerkas', 'templates', 'placeholders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $klienTersedia = Klien::orderBy('nama_klien', 'asc')->get();
        $kecamatans = Kecamatan::orderBy('nama', 'asc')->get();
        $jenisPermohonans = JenisPermohonan::orderBy('nama', 'asc')->get();

        return view('berkas.create', compact('klienTersedia', 'kecamatans', 'jenisPermohonans'));
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
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'desa_id' => 'required|exists:desas,id',
            'jenis_permohonan_id' => 'required|exists:jenis_permohonans,id',
            'spa' => 'nullable|string',
            'alih_media' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kode_biling' => 'nullable|string|max:255',
            'jumlah_bayar' => 'nullable|numeric',
        ]);

        $berkas = Berkas::create($validated);

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
        $templates = WaTemplate::where('status_template', 'aktif')->get(); // <-- PERBAIKAN: Harus 'status_template'
        
        $kecamatans = Kecamatan::orderBy('nama', 'asc')->get();
        $jenisPermohonans = JenisPermohonan::orderBy('nama', 'asc')->get();
        $desas = Desa::where('kecamatan_id', $berkas->kecamatan_id)->orderBy('nama', 'asc')->get();

        return view('berkas.edit', compact(
            'berkas', 
            'klienTersedia', 
            'templates', 
            'kecamatans', 
            'jenisPermohonans', 
            'desas'
        ));
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
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'desa_id' => 'required|exists:desas,id',
            'jenis_permohonan_id' => 'required|exists:jenis_permohonans,id',
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

    /**
     * FUNGSI API BARU UNTUK MENGAMBIL DESA
     * * @param  \Illuminate\Http\Request  $request
     * @param  int  $kecamatan_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDesaApi(Request $request, $kecamatan_id)
    {
        $desas = Desa::where('kecamatan_id', $kecamatan_id)->orderBy('nama', 'asc')->get();
        return response()->json($desas);
    }
}