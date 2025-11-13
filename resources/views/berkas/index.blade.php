@php
    // Ambil template DARI DATABASE satu kali saja
    $setting = \App\Models\Setting::where('key', 'wa_template')->first();
    $templatePesanWA = $setting ? $setting->value : "Template default (DB Error: Silakan cek halaman Pengaturan Pesan)";
@endphp

@extends('adminlte::page')

@section('title', 'Daftar Berkas')

@section('content_header')
    <h1>Daftar Berkas</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $message }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('berkas.create') }}" class="btn btn-primary">Tambah Berkas Baru</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="berkas-table">
                <thead>
                    <tr>
                        <th style="width: 10px">ID</th>
                        <th>Jenis Hak</th>
                        <th>Nomer Hak</th>
                        <th>Kec/Desa</th>
                        <th>Pemohon (WA)</th>
                        <th style="width: 280px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($semuaBerkas as $berkas)
                    <tr>
                        <td>{{ $berkas->id }}</td>
                        <td>{{ $berkas->jenis_hak }}</td>
                        <td>{{ $berkas->nomer_hak }}</td>
                        <td>{{ $berkas->kecamatan }} / {{ $berkas->desa }}</td>
                        <td>
                            @if($berkas->klien)
                                {{ $berkas->klien->nama_klien }}
                                <br>
                                <small>({{ $berkas->klien->nomer_wa }})</small>
                            @else
                                <small>(Umum)</small>
                            @endif
                        </td>
                        <td>
                            @if($berkas->klien && $berkas->klien->nomer_wa)
                                @php
                                    // 1. Siapkan data pengganti
                                    $placeholders = [
                                        '[nama]'        => $berkas->klien->nama_klien,
                                        '[kode_klien]'  => $berkas->klien->kode_klien,
                                        '[nomer_hak]'   => $berkas->nomer_hak,
                                        '[jenis_hak]'   => $berkas->jenis_hak,
                                        '[kecamatan]'   => $berkas->kecamatan,
                                        '[desa]'        => $berkas->desa,
                                    ];

                                    // 2. Buat pesan menggunakan TEMPLATE DARI DATABASE
                                    $pesan = str_replace(
                                        array_keys($placeholders), 
                                        array_values($placeholders), 
                                        $templatePesanWA
                                    );
                                    
                                    // 3. Format Nomer WA
                                    $nomerWa = $berkas->klien->nomer_wa;
                                    if(substr($nomerWa, 0, 1) == "0") {
                                        $nomerWa = "62" . substr($nomerWa, 1);
                                    }
                                    $nomerWa = str_replace('+', '', $nomerWa); 
                                @endphp
                                
                                <button class="btn btn-success btn-sm btn-kirim-wa" 
                                        data-nomor="{{ $nomerWa }}" 
                                        data-pesan="{{ $pesan }}">
                                    <i class="fab fa-whatsapp"></i> Kirim Info
                                </button>
                            @endif
                            
                            <form action="{{ route('berkas.destroy', $berkas->id) }}" method="POST" style="display:inline-block;">
                                {{-- Gunakan $berka->id jika Anda mengikuti standar 'berka' --}}
                                {{-- <a href="{{ route('berkas.edit', $berka->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                                {{-- Gunakan $berkas->id jika Anda menggunakan 'berkas' --}}
                                <a href="{{ route('berkas.edit', $berkas->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus berkas ini? Semua data akan hilang.')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- ================================================= --}}
    {{--    MODAL UNTUK KONFIRMASI PENGIRIMAN PESAN WA       --}}
    {{-- ================================================= --}}
    <div class="modal fade" id="kirimWaModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Konfirmasi Pengiriman Pesan WA</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            
            <div class="form-group">
                <label>Kirim Ke (Read-only):</label>
                <input type="text" id="modal-nomor-wa" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Isi Pesan (Konfirmasi):</label>
                <textarea id="modal-isi-pesan" class="form-control" rows="10" readonly></textarea>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-success" id="btn-kirim-final">Kirim Sekarang</button>
          </div>
        </div>
      </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    
    // --- 1. Inisialisasi DataTables ---
    $('#berkas-table').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

    // --- 2. Event Handler untuk Tombol di Tabel (Membuka Modal) ---
    // Gunakan 'body' sebagai delegasi yang lebih aman
    $('body').on('click', '.btn-kirim-wa', function() {
        
        const nomor = $(this).data('nomor');
        const pesan = $(this).data('pesan');

        $('#modal-nomor-wa').val(nomor);
        $('#modal-isi-pesan').val(pesan); 

        $('#kirimWaModal').modal('show');
    });


    // --- 3. Event Handler untuk Tombol "Kirim Sekarang" di dalam Modal ---
    $('#btn-kirim-final').on('click', function() {
        const button = $(this);
        const nomor = $('#modal-nomor-wa').val();
        const pesan = $('#modal-isi-pesan').val();

        if (!nomor || !pesan) {
            alert('Nomor atau Pesan tidak boleh kosong.');
            return;
        }

        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');
        button.prop('disabled', true);

        // --- INI ADALAH BAGIAN UTAMA ---
        fetch('http://localhost:3000/kirim-pesan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nomor: nomor,
                pesan: pesan
            })
        })
        .then(response => {
             // Cek jika response tidak OK (cth: 500 Internal Server Error)
            if (!response.ok) {
                // lempar error agar ditangkap oleh .catch()
                throw new Error('Server merespon dengan error: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Pesan berhasil terkirim!');
                $('#kirimWaModal').modal('hide');
            } else {
                alert('Gagal mengirim: ' + data.message);
            }
        })
        .catch(error => {
            // Ini adalah bagian jika server Node.js MATI atau URL salah
            console.error('Error saat fetch:', error);
            alert('GAGAL MENGHUBUNGI SERVER WA.\n\nPastikan 1) Server Node.js berjalan, dan 2) Tidak ada error CORS di console (F12).');
        })
        .finally(() => {
            button.html(originalText);
            button.prop('disabled', false);
        });
    });

});
</script>
@stop