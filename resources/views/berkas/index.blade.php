@php
    // Controller sudah me-pass $semuaBerkas, $templates, dan $placeholders
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
                        <th>Nomer Berkas</th>
                        <th>Nama Pemohon</th>
                        <th>Nomer Hak</th>
                        <th>Kec/Desa</th>
                        <th style="width: 180px">Info WA</th> 
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($semuaBerkas as $berkas)
                    <tr>
                        <td>{{ $berkas->id }}</td>
                        <td>{{ $berkas->nomer_berkas }}</td>
                        <td>
                            {{ $berkas->nama_pemohon }}
                            {{-- FAKTA A: Logika ini berjalan, artinya $berkas->nomer_wa ADA --}}
                            @if($berkas->nomer_wa)
                                <br><small>WA: {{ $berkas->nomer_wa }}</small>
                            @endif
                        </td>
                        <td>{{ $berkas->jenis_hak }} / {{ $berkas->nomer_hak }}</td>
                        <td>{{ $berkas->kecamatan }} / {{ $berkas->desa }}</td>
                        
                        {{-- =============================================== --}}
                        {{--      LOGIKA TOMBOL WA (DIPERBARUI)              --}}
                        {{-- =============================================== --}}
                        <td>
                            {{-- Cek HANYA jika berkas punya nomer WA --}}
                            @if($berkas->nomer_wa && $templates->count() > 0)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Kirim Info
                                    </button>
                                    <div class="dropdown-menu">
                                        @foreach($templates as $template)
                                            @php
                                                $logCount = $berkas->waLogs->where('wa_template_id', $template->id)->count();
                                                $nomerWa = $berkas->nomer_wa;
                                                if(substr($nomerWa, 0, 1) == "0") {
                                                    $nomerWa = "62" . substr($nomerWa, 1);
                                                }
                                                $nomerWa = str_replace('+', '', $nomerWa); 
                                            @endphp
                                            
                                            <a class="dropdown-item btn-kirim-wa" href="#"
                                                data-nomor="{{ $nomerWa }}"
                                                data-template-id="{{ $template->id }}"
                                                data-berkas-id="{{ $berkas->id }}"
                                                data-template-text="{{ $template->template_text }}"
                                                data-berkas-json="{{ json_encode($berkas->toArray()) }}"
                                                data-klien-json="{{ json_encode($berkas->klien ? $berkas->klien->toArray() : null) }}"
                                            >
                                                {{ $template->nama_template }}
                                                <span class="badge badge-primary badge-pill float-right">{{ $logCount }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <small class="text-muted">
                                    {{-- FAKTA B: Logika ini berjalan, artinya $berkas->nomer_wa KOSONG --}}
                                    {{-- INI ADALAH BUKTI CACHE KORUP --}}
                                    @if(!$berkas->nomer_wa)
                                    (No WA terdaftar)
                                    @elseif($templates->count() == 0)
                                    (Belum ada template)
                                    @endif
                                </small>
                            @endif
                        </td>

                        {{-- =============================================== --}}
                        {{--          KOLOM AKSI (Telah dipisah)             --}}
                        {{-- =============================================== --}}
                        <td>
                            <form action="{{ route('berkas.destroy', $berkas->id) }}" method="POST" style="display:inline-block;">
                                <a href="{{ route('berkas.edit', $berkas->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus berkas ini?')">Hapus</button>
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
            <input type="hidden" id="modal-berkas-id">
            <input type="hidden" id="modal-template-id">
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
    
    // --- 0. AMBIL DATA PLACEHOLDER DARI CONTROLLER ---
    const allPlaceholders = @json($placeholders);

    // --- 1. Inisialisasi DataTables (DIBUNGKUS DENGAN TRY...CATCH) ---
    try {
        $('#berkas-table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    } catch (e) {
        console.error("Gagal memuat DataTables:", e);
        if (typeof toastr !== 'undefined') {
            toastr.warning('Plugin DataTables gagal dimuat. Fitur tabel mungkin non-aktif.', 'Peringatan');
        } else {
            console.warn("Plugin DataTables gagal dimuat.");
        }
    }

    // --- 2. Event Handler untuk Tombol di Tabel (Membuka Modal) ---
    $('body').on('click', '.btn-kirim-wa', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const nomor = button.data('nomor');
        const berkasId = button.data('berkas-id');
        const templateId = button.data('template-id');
        let templateText = button.data('template-text');
        const dataBerkas = button.data('berkas-json');
        const dataKlien = button.data('klien-json');

        // --- Logika Penggantian Placeholder Dinamis (Sudah Benar) ---
        let pesanFinal = templateText;
        allPlaceholders.forEach(function(ph) {
            const key = ph.placeholder_key; // Cth: [nama]
            const source = ph.data_source;  // Cth: klien.nama_klien
            
            let value = ''; 
            const sourceParts = source.split('.');
            const relation = sourceParts[0]; // 'klien' atau 'berkas'
            const column = sourceParts[1]; // 'nama_klien' atau 'nomer_hak'

            if (relation === 'klien' && dataKlien) {
                value = dataKlien[column] || ''; 
            } else if (relation === 'berkas' && dataBerkas) {
                value = dataBerkas[column] || ''; 
            }
            
            // Ganti placeholder dengan nilainya
            pesanFinal = pesanFinal.replace(new RegExp(key.replace(/\[/g, '\\[').replace(/\]/g, '\\]'), 'g'), value);
        });
        
        $('#modal-nomor-wa').val(nomor);
        $('#modal-berkas-id').val(berkasId);
        $('#modal-template-id').val(templateId);
        $('#modal-isi-pesan').val(pesanFinal); 

        $('#kirimWaModal').modal('show');
    });


    // --- 3. Event Handler untuk Tombol "Kirim Sekarang" di dalam Modal ---
    $('body').on('click', '#btn-kirim-final', function() {
        
        const button = $(this);
        const nomor = $('#modal-nomor-wa').val();
        const pesan = $('#modal-isi-pesan').val();
        const berkas_id = $('#modal-berkas-id').val();
        const wa_template_id = $('#modal-template-id').val();

        if (!nomor || !pesan || !berkas_id || !wa_template_id) {
            if (typeof toastr !== 'undefined') {
                toastr.error('Data tidak lengkap. Gagal mengirim.');
            } else {
                alert('Data tidak lengkap. Gagal mengirim.'); 
            }
            return;
        }

        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');
        button.prop('disabled', true);

        fetch('http://localhost:3000/kirim-pesan', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nomor: nomor,
                pesan: pesan,
                berkas_id: berkas_id,
                wa_template_id: wa_template_id
            })
        })
        .then(response => {
            if (!response.ok) {
                 return response.json().then(errData => {
                    throw new Error(errData.message || `Server merespon dengan error: ${response.status}`);
                }).catch(() => {
                    throw new Error(`Server merespon dengan error: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success('Pesan berhasil terkirim!'); 
                } else {
                    alert('Pesan berhasil terkirim!'); 
                }
                $('#kirimWaModal').modal('hide');
                // Reload halaman untuk update counter jumlah terkirim
                location.reload(); 
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Gagal mengirim: ' + data.message);
                } else {
                    alert('Gagal mengirim: ' + data.message); 
                }
            }
        })
        .catch(error => {
            console.error('Error saat fetch:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('GAGAL MENGHUBUNGI SERVER WA.<br>Pastikan server Node.js berjalan.', 'Server Error');
            } else {
                alert('GAGAL MENGHUBUNGI SERVER WA.\n\nError: ' + error.message); 
            }
        })
        .finally(() => {
            button.html(originalText);
            button.prop('disabled', false);
        });
    });

});
</script>
@stop