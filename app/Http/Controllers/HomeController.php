<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berkas;
use App\Models\Klien;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        // 1. Tentukan Tahun & Bulan (Default: Tahun & Bulan saat ini)
        $year = date('Y');
        $selectedMonth = $request->input('bulan', date('n')); // Ambil dari input atau default bulan ini

        // 2. Data untuk Kotak Atas (Total Berkas per Klien Tahun Ini)
        $totalPerKlien = Klien::withCount(['berkas' => function ($query) use ($year) {
            $query->whereYear('created_at', $year);
        }])->orderBy('nama_klien', 'asc')->get();

        // 3. Data untuk Tabel Bawah (Detail per Klien pada Bulan yang Dipilih)
        $statsBulanan = Klien::leftJoin('berkas', function($join) use ($year, $selectedMonth) {
            $join->on('klien.id', '=', 'berkas.klien_id')
                 ->whereYear('berkas.created_at', $year)
                 ->whereMonth('berkas.created_at', $selectedMonth);
        })
        ->select('klien.id', 'klien.kode_klien', 'klien.nama_klien', DB::raw('COUNT(berkas.id) as jumlah_berkas'))
        ->groupBy('klien.id', 'klien.kode_klien', 'klien.nama_klien')
        ->orderBy('klien.nama_klien', 'asc')
        ->get();

        // Daftar nama bulan untuk dropdown
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('home', compact('totalPerKlien', 'statsBulanan', 'year', 'selectedMonth', 'bulanList'));
    }
}