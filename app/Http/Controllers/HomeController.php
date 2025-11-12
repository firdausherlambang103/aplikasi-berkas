<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berkas; // <-- Tambahkan ini
use App\Models\Klien; // <-- Tambahkan ini
use Illuminate\Support\Facades\DB; // <-- Tambahkan ini

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // --- 1. Statistik Kartu (Widgets) ---
        $totalBerkas = Berkas::count();
        $totalKlien = Klien::count();

        // --- 2. Data untuk Grafik ---
        
        // Label bulan untuk sumbu X
        $bulanLabels = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        // Ambil data berkas masuk per bulan untuk TAHUN INI
        $berkasPerBulan = Berkas::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereYear('created_at', date('Y')) // Hanya ambil data tahun ini
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->pluck('jumlah', 'bulan') // Hasilnya: [1 => 10, 2 => 5, 4 => 12]
            ->all();

        // Siapkan array data 12 bulan, inisialisasi dengan 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            // Jika ada data di $berkasPerBulan untuk bulan $i, gunakan. Jika tidak, gunakan 0.
            $chartData[] = $berkasPerBulan[$i] ?? 0;
        }

        // Kirim semua data ke view
        return view('home', compact(
            'totalBerkas', 
            'totalKlien', 
            'bulanLabels', 
            'chartData'
        ));
    }
}
