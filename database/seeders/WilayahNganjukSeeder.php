<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Support\Facades\DB;

class WilayahNganjukSeeder extends Seeder
{
    public function run()
    {
        // Data Kecamatan dan Desa di Kabupaten Nganjuk
        $data = [
            'Bagor' => [
                'Bagorkulon', 'Balongrejo', 'Banarankulon', 'Banaranwetan', 'Buduran', 
                'Gandu', 'Gemenggeng', 'Girirejo', 'Guyangan', 'Karangtengah', 
                'Kedondong', 'Kendalrejo', 'Kerepkidul', 'Kutorejo', 'Ngumpul', 
                'Paron', 'Pesudukuh', 'Petak', 'Sekarputih', 'Selorejo', 'Sugihwaras'
            ],
            'Baron' => [
                'Baron', 'Garu', 'Gebangkerep', 'Jambi', 'Jekek', 'Katerban', 
                'Kemaduh', 'Kemlokolegi', 'Mabung', 'Sambiroto', 'Waung'
            ],
            'Berbek' => [
                'Balongrejo', 'Bendungrejo', 'Berbek', 'Bulu', 'Cepoko', 'Grojogan', 
                'Kacangan', 'Maguan', 'Mlilir', 'Ngrawan', 'Patranrejo', 'Salamrojo', 
                'Semare', 'Sendangbumen', 'Sengkut', 'Sonopatik', 'Sumberurip', 
                'Sumberwindu', 'Tiripan'
            ],
            'Gondang' => [
                'Balonggebang', 'Campur', 'Gondangkulon', 'Jaan', 'Karangsemi', 
                'Kedungglugu', 'Ketawang', 'Losari', 'Mojoseto', 'Nglinggo', 
                'Ngunjung', 'Pandean', 'Sanggrahan', 'Senggowar', 'Senjayan', 
                'Sumberagung', 'Sumberjo'
            ],
            'Jatikalen' => [
                'Begendeng', 'Dawuhan', 'Dlururejo', 'Gondang Wetan', 'Jatikalen', 
                'Lumpangkuwik', 'Munung', 'Ngasem', 'Perning', 'Pule', 'Pulowetan'
            ],
            'Kertosono' => [
                'Banaran', 'Bangsri', 'Drenges', 'Juwono', 'Kalianyar', 'Kepuh', 
                'Kudu', 'Kutorejo', 'Lambangkuning', 'Nglawak', 'Pandantoyo', 
                'Pelem', 'Tanjung', 'Tembarak'
            ],
            'Lengkong' => [
                'Balongasem', 'Bangle', 'Banjardowo', 'Jatipunggur', 'Jegreg', 
                'Kedungmlaten', 'Ketandan', 'Lengkong', 'Ngepung', 'Ngringin', 
                'Pinggir', 'Prayungan', 'Sawahan', 'Sumberkepuh', 'Sumbermiri', 
                'Sumbersono'
            ],
            'Loceret' => [
                'Bajulan', 'Candirejo', 'Gejagan', 'Genjeng', 'Godean', 'Jatirejo', 
                'Karangsono', 'Kenep', 'Kwagean', 'Loceret', 'Macanan', 'Mungkung', 
                'Ngepeh', 'Nglaban', 'Patihan', 'Putukrejo', 'Sekaran', 'Sombron', 
                'Sukorejo', 'Tanjungrejo', 'Teken Glagahan', 'Tempel Wetan'
            ],
            'Nganjuk' => [
                'Balongpacul', 'Begadung', 'Bogo', 'Cangkringan', 'Ganungkidul', 
                'Jatirejo', 'Kartoharjo', 'Kauman', 'Kedungdowo', 'Kramat', 
                'Mangundikaran', 'Payaman', 'Ploso', 'Ringinanom', 'Werungotok'
            ],
            'Ngetos' => [
                'Blongko', 'Kepel', 'Klodan', 'Kuncir', 'Kweden', 'Mojoduwur', 
                'Ngetos', 'Oro-oro Ombo', 'Suru'
            ],
            'Ngluyu' => [
                'Bajang', 'Gampeng', 'Lengkong Lor', 'Ngluyu', 'Sugihwaras', 'Tempuran'
            ],
            'Ngronggot' => [
                'Awar-awar', 'Banjarsari', 'Betet', 'Cengkok', 'Dadapan', 'Juwet', 
                'Kaloran', 'Kelutan', 'Klurahan', 'Mojokendil', 'Ngronggot', 
                'Tanjungkalang', 'Trayang', 'Watudandang'
            ],
            'Pace' => [
                'Babadan', 'Banaran', 'Batokan', 'Bodor', 'Cerme', 'Gemenggeng', 
                'Gondang', 'Jatigreges', 'Jetis', 'Joho', 'Kecubung', 'Kepanjen', 
                'Mlandangan', 'Pacekulon', 'Pacewetan', 'Plosoharjo', 'Sanan', 'Soko', 'Sumbersono'
            ],
            'Patianrowo' => [
                'Babadan', 'Bukur', 'Lestari', 'Ngepung', 'Ngrombot', 'Pakuncen', 
                'Patianrowo', 'Pecuk', 'Pisang', 'Rowomarto', 'Tirtobinangun'
            ],
            'Prambon' => [
                'Baleturi', 'Bandung', 'Gondanglegi', 'Kurungrejo', 'Mojoagung', 
                'Nglawak', 'Rowoharjo', 'Sanggrahan', 'Singkalanyar', 'Sono Ageng', 
                'Sugihwaras', 'Tanjungtani', 'Tegaron', 'Watudandang'
            ],
            'Rejoso' => [
                'Banjarejo', 'Bendoasri', 'Gempol', 'Jatirejo', 'Jintel', 
                'Kedungpadang', 'Klagen', 'Mlorah', 'Mojorembun', 'Mungkung', 
                'Musir Kidul', 'Musir Lor', 'Ngadiboyo', 'Ngangkatan', 'Puhkerep', 
                'Rejoso', 'Sambikerep', 'Setren', 'Talang', 'Talun', 'Tritik', 'Wengkal'
            ],
            'Sawahan' => [
                'Bareng', 'Bendolo', 'Duren', 'Kebonagung', 'Margopatut', 
                'Ngliman', 'Sawahan', 'Sidorejo', 'Siwalan'
            ],
            'Sukomoro' => [
                'Bagorwetan', 'Blitaran', 'Bungur', 'Kedungsoko', 'Kapas', 'Nglundo', 
                'Ngrami', 'Ngrengket', 'Pehserut', 'Putren', 'Sukomoro', 'Sumengko'
            ],
            'Tanjunganom' => [
                'Banjaranyar', 'Demangan', 'Getas', 'Jogomerto', 'Kampungbaru', 
                'Kedungombo', 'Kedungrejo', 'Malangsari', 'Ngadirejo', 'Sambirejo', 
                'Sidoharjo', 'Sonobekel', 'Sumberkepuh', 'Tanjunganom', 'Warujayeng', 'Wates'
            ],
            'Wilangan' => [
                'Mancon', 'Ngadipiro', 'Ngudikan', 'Sudimoroharjo', 'Sukoharjo', 'Wilangan'
            ],
        ];

        DB::beginTransaction();
        try {
            // 1. Nonaktifkan cek Foreign Key agar bisa truncate tabel yang berelasi
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // 2. Kosongkan tabel (Reset ID kembali ke 1)
            $this->command->info('Mengosongkan tabel desa dan kecamatan...');
            Desa::truncate();
            Kecamatan::truncate();
            
            // 3. Aktifkan kembali cek Foreign Key
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->command->info('Mulai menginput data baru...');

            foreach ($data as $namaKecamatan => $daftarDesa) {
                // Buat Kecamatan
                // Kita gunakan create() karena tabel sudah pasti kosong
                $kecamatan = Kecamatan::create(['nama' => $namaKecamatan]);

                // Buat Desa-desa untuk kecamatan tersebut
                foreach ($daftarDesa as $namaDesa) {
                    Desa::create([
                        'kecamatan_id' => $kecamatan->id,
                        'nama' => $namaDesa
                    ]);
                }
            }
            
            DB::commit();
            $this->command->info('BERHASIL! Data Kecamatan dan Desa Kabupaten Nganjuk telah diperbarui.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Pastikan foreign key check dikembalikan jika terjadi error di tengah jalan
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->error('Gagal menginput data: ' . $e->getMessage());
        }
    }
}