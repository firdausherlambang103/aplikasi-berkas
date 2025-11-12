<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting; // <-- Import model

class PengaturanController extends Controller
{
    // Template default JIKA belum ada di database
    private $defaultTemplate = "Yth. Bpk/Ibu [nama], \n\nBerkas Anda dengan Nomer Hak [nomer_hak] (Jenis: [jenis_hak]) telah berhasil diregistrasi pada sistem kami. \n\nTerima kasih.";

    /**
     * Menampilkan halaman form pengaturan.
     */
    public function index()
    {
        // Ambil template dari DB, atau buat baru jika tidak ada
        $template = Setting::firstOrCreate(
            ['key' => 'wa_template'],
            ['value' => $this->defaultTemplate]
        );

        return view('pengaturan.index', compact('template'));
    }

    /**
     * Menyimpan template yang diperbarui.
     */
    public function update(Request $request)
    {
        $request->validate([
            'template_wa' => 'required|string',
        ]);

        // Temukan dan perbarui template di database
        Setting::where('key', 'wa_template')
               ->update(['value' => $request->template_wa]);

        return redirect()->route('pengaturan.index')
                         ->with('success', 'Template pesan WhatsApp berhasil diperbarui.');
    }
}