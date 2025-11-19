<?php

namespace App\Services;

use App\Models\Berkas;
use App\Models\Setting;
use App\Models\WaLog;
use App\Models\WaPlaceholder;
use App\Models\WaTemplate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WaNotificationService
{
    protected $apiUrl;
    protected $apiKey;

    /**
     * Mengambil kredensial API dari database saat service dipanggil.
     */
    public function __construct()
    {
        // Pastikan Anda memiliki 'key' ini di tabel 'settings' Anda
        $this->apiUrl = Setting::where('key', 'wa_api_url')->first()->value ?? null;
        $this->apiKey = Setting::where('key', 'wa_api_key')->first()->value ?? null;
    }

    /**
     * Fungsi publik yang dipanggil oleh BerkasController.
     * Ini adalah fungsi yang hilang.
     */
    public function sendNotificationOnCreate(Berkas $berkas)
    {
        // 'registrasi' adalah nama_template yang kita cari
        $this->sendNotification($berkas, 'registrasi');
    }

    /**
     * Fungsi utama untuk mengirim notifikasi berdasarkan nama template.
     */
    public function sendNotification(Berkas $berkas, string $templateName)
    {
        // 1. Cek Kredensial API
        if (!$this->apiUrl || !$this->apiKey) {
            Log::error('WA Service: API URL or API Key is not set in settings.');
            return;
        }

        // 2. Cek Nomer WA
        if (!$berkas->nomer_wa) {
            return; // Tidak ada nomer WA, hentikan
        }

        try {
            // 3. Cari Template
            // Menggunakan 'status_template' sesuai database Anda
            $template = WaTemplate::where('nama_template', $templateName)
                                  ->where('status_template', 'aktif') 
                                  ->first();

            if (!$template) {
                return; // Template tidak ada atau tidak aktif
            }

            // 4. Ganti Placeholder
            // Menggunakan 'template_text' sesuai database Anda
            $message = $this->replacePlaceholders($template->template_text, $berkas);

            // 5. Kirim Pesan
            $this->sendMessage($berkas->nomer_wa, $message, $templateName);

        } catch (Exception $e) {
            Log::error('WA Service Error: ' . $e->getMessage());
            $this->logMessage($berkas->nomer_wa, 'Error: ' . $e->getMessage(), 'gagal', $templateName);
        }
    }

    /**
     * Mengganti semua placeholder di dalam pesan.
     */
    private function replacePlaceholders(string $message, Berkas $berkas): string
    {
        $placeholders = WaPlaceholder::all();
        // Muat semua relasi yang mungkin diperlukan
        $berkasData = $berkas->load('klien', 'kecamatan', 'desa', 'jenisPermohonan'); 

        foreach ($placeholders as $placeholder) {
            $key = $placeholder->nama_kolom; // e.g., 'nama_pemohon'
            $value = '';

            // Cek di data Berkas dulu
            if (isset($berkasData->$key)) {
                $value = $berkasData->$key;
            } 
            // PERBAIKAN: Cek dulu apakah $berkasData->klien ADA (tidak null), 
            // BARU cek propertinya.
            else if (isset($berkasData->klien) && isset($berkasData->klien->$key)) { 
                $value = $berkasData->klien->$key;
            }
            // Tambahan untuk data relasi baru (kecamatan, desa, dll)
            else if (isset($berkasData->kecamatan) && $key == 'nama_kecamatan') {
                $value = $berkasData->kecamatan->nama;
            }
            else if (isset($berkasData->desa) && $key == 'nama_desa') {
                $value = $berkasData->desa->nama;
            }
            else if (isset($berkasData->jenisPermohonan) && $key == 'nama_permohonan') {
                $value = $berkasData->jenisPermohonan->nama;
            }

            // Ganti placeholder (e.g., {nama_pemohon}) dengan value
            $message = str_replace($placeholder->placeholder, $value, $message);
        }
        return $message;
    }

    /**
     * Logika inti untuk mengirim pesan ke API eksternal.
     */
    private function sendMessage(string $to, string $message, string $templateName)
    {
        // Pastikan nomer WA berformat 62...
        $to = preg_replace('/^0/', '62', $to);

        // **PENTING**: Sesuaikan payload ini dengan API WA Anda
        $response = Http::post($this->apiUrl, [
            'token' => $this->apiKey,
            'phone' => $to,
            'message' => $message
        ]);

        if ($response->successful()) {
            $this->logMessage($to, $message, 'terkirim', $templateName);
        } else {
            $this->logMessage($to, $message . ' | Error: ' . $response->body(), 'gagal', $templateName);
        }
    }

    /**
     * Mencatat hasil pengiriman ke database.
     */
    private function logMessage(string $nomer_wa, string $pesan, string $status, string $template)
    {
        WaLog::create([
            'nomer_wa' => $nomer_wa,
            'pesan' => $pesan,
            'template' => $template,
            'status' => $status,
        ]);
    }
}