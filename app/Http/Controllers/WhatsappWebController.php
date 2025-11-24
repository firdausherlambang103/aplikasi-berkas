<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappWebController extends Controller
{
    // Ganti URL ini sesuai alamat service Node.js Anda berjalan
    // Biasanya http://localhost:3000 atau http://localhost:8000
    private $nodeUrl = 'http://localhost:3000';

    /**
     * Menampilkan halaman scan QR dan status.
     */
    public function index()
    {
        return view('admin.whatsapp.scan');
    }

    /**
     * Mengambil status koneksi dari Node.js
     * Endpoint Node.js yang diharapkan: GET /status
     */
    public function getStatus()
    {
        try {
            $response = Http::timeout(3)->get($this->nodeUrl . '/status');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Tidak dapat terhubung ke Node.js Service. Pastikan service berjalan.'
            ], 500);
        }
    }

    /**
     * Mengambil gambar QR Code dari Node.js
     * Endpoint Node.js yang diharapkan: GET /qr
     */
    public function getQr()
    {
        try {
            $response = Http::timeout(3)->get($this->nodeUrl . '/qr');
            // Asumsi response Node.js mengirim JSON { "qr": "data:image/png;base64,..." }
            // Atau bisa juga mengirim image stream langsung.
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['qr' => null], 500);
        }
    }

    /**
     * Melakukan logout / disconnect sesi WA
     * Endpoint Node.js yang diharapkan: POST /logout
     */
    public function logout()
    {
        try {
            $response = Http::post($this->nodeUrl . '/logout');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal logout'], 500);
        }
    }
}