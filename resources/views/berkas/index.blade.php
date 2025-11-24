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
            <h3 class="card-title">
                <a href="{{ route('berkas.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Berkas Baru
                </a>
            </h3>

            {{-- --- FITUR PENCARIAN --- --}}
            <div class="card-tools">
                <form action="{{ route('berkas.index') }}" method="GET">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" name="search" class="form-control float-right" placeholder="Cari Berkas/Pemohon..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- --- END FITUR PENCARIAN --- --}}
        </div>

        <div class="card-body table-responsive p-0">
            {{-- Tambahkan table-responsive agar tabel aman di layar kecil --}}
            <table class="table table-bordered table-striped table-hover text-nowrap" id="berkas-table">
                <thead>
                    <tr>
                        <th style="width: 10px">ID</th>
                        <th>Nomer Berkas</th>
                        <th>Nama Pemohon</th>
                        <th>Nomer Hak</th>
                        <th>Kec/Desa</th>
                        <th>Status</th> {{-- Kolom Status Baru --}}
                        <th style="width: 150px">Info WA</th> 
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semuaBerkas as $berkas)
                    <tr>
                        <td>{{ $berkas->id }}</td>
                        <td>{{ $berkas->nomer_berkas }}</td>
                        <td>
                            {{ $berkas->nama_pemohon }}
                            @if($berkas->nomer_wa)
                                <br><small class="text-muted"><i class="fab fa-whatsapp"></i> {{ $berkas->nomer_wa }}</small>
                            @endif
                        </td>
                        <td>{{ $berkas->jenis_hak }} / {{ $berkas->nomer_hak }}</td>
                        
                        <td>
                            {{-- Tampilkan Nama Kecamatan/Desa dengan aman --}}
                            {{ $berkas->dataKecamatan->nama ?? '-' }} 
                            / 
                            {{ $berkas->dataDesa->nama ?? '-' }}
                        </td>

                        <td>
                            {{-- Badge Status Warna-warni --}}
                            @php
                                $badgeClass = match($berkas->status) {
                                    'Selesai' => 'badge-success',
                                    'Proses' => 'badge-info',
                                    'Kendala' => 'badge-warning',
                                    'Dibatalkan' => 'badge-danger',
                                    default => 'badge-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $berkas->status }}</span>
                        </td>
                        
                        <td>
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
                                                if(substr($nomerWa, 0, 1) == "0") { $nomerWa = "62" . substr($nomerWa, 1); }
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
                                    @if(!$berkas->nomer_wa) (No WA) @else (No Template) @endif
                                </small>
                            @endif
                        </td>

                        <td>
                            <form action="{{ route('berkas.destroy', $berkas->id) }}" method="POST" style="display:inline-block;">
                                <a href="{{ route('berkas.edit', $berkas->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus berkas ini?')" title="Hapus"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            @if(request('search'))
                                Data tidak ditemukan untuk pencarian "<strong>{{ request('search') }}</strong>"
                            @else
                                Belum ada data berkas.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer clearfix">
            {{-- Pagination Link --}}
            {{ $semuaBerkas->links() }}
        </div>
    </div>


    {{-- MODAL KONFIRMASI PENGIRIMAN PESAN WA --}}
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
    
    const allPlaceholders = @json($placeholders);

    // --- Inisialisasi DataTables (HANYA FITUR SORTING, KARENA PAGING & SEARCH KITA PAKAI LARAVEL) ---
    try {
        $('#berkas-table').DataTable({
            "paging": false,     // Matikan paging datatables (kita pakai Laravel punya)
            "lengthChange": false,
            "searching": false,  // Matikan search datatables (kita pakai input search custom di atas)
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
        });
    } catch (e) {
        console.warn("Plugin DataTables gagal dimuat.");
    }

    // --- FUNGSI BANTUAN NESTED DATA ---
    function getNestedValue(obj, path) {
        if (!path) return '';
        return path.split('.').reduce((acc, part) => (acc && acc[part] !== undefined) ? acc[part] : '', obj);
    }

    // --- Handler Tombol Buka Modal ---
    $('body').on('click', '.btn-kirim-wa', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const nomor = button.data('nomor');
        const berkasId = button.data('berkas-id');
        const templateId = button.data('template-id');
        let templateText = button.data('template-text');
        const dataBerkas = button.data('berkas-json');
        const dataKlien = button.data('klien-json');

        let pesanFinal = templateText;
        
        allPlaceholders.forEach(function(ph) {
            const key = ph.placeholder_key; 
            const source = ph.data_source;  
            
            let value = ''; 
            const firstDotIndex = source.indexOf('.');
            
            if (firstDotIndex !== -1) {
                const root = source.substring(0, firstDotIndex); 
                const path = source.substring(firstDotIndex + 1); 

                if (root === 'klien' && dataKlien) {
                    value = getNestedValue(dataKlien, path);
                } else if (root === 'berkas' && dataBerkas) {
                    value = getNestedValue(dataBerkas, path);
                }
            }

            if (typeof value === 'object') value = ''; 
            pesanFinal = pesanFinal.replace(new RegExp(key.replace(/\[/g, '\\[').replace(/\]/g, '\\]'), 'g'), value);
        });
        
        $('#modal-nomor-wa').val(nomor);
        $('#modal-berkas-id').val(berkasId);
        $('#modal-template-id').val(templateId);
        $('#modal-isi-pesan').val(pesanFinal); 

        $('#kirimWaModal').modal('show');
    });


    // --- Handler Tombol "Kirim Sekarang" ---
    $('body').on('click', '#btn-kirim-final', function() {
        
        const button = $(this);
        const nomor = $('#modal-nomor-wa').val();
        const pesan = $('#modal-isi-pesan').val();
        const berkas_id = $('#modal-berkas-id').val();
        const wa_template_id = $('#modal-template-id').val();

        if (!nomor || !pesan || !berkas_id || !wa_template_id) {
            alert('Data tidak lengkap. Gagal mengirim.'); 
            return;
        }

        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');
        button.prop('disabled', true);

        fetch('http://192.168.0.42:3000/kirim-pesan', {
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
            if (!response.ok) throw new Error(`Server WA Error: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                
                // LAPOR LOG KE LARAVEL
                fetch('/log-wa-send', { 
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    body: JSON.stringify({
                        berkas_id: berkas_id,
                        wa_template_id: wa_template_id
                    })
                })
                .then(res => {
                    if(res.ok) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Pesan terkirim & Log disimpan!'); 
                        } else {
                            alert('Pesan terkirim & Log disimpan!'); 
                        }
                        $('#kirimWaModal').modal('hide');
                        location.reload(); 
                    } else {
                        alert("Pesan terkirim, tapi log gagal disimpan.");
                    }
                });

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
            alert('Gagal menghubungi Server WA. Pastikan Node.js berjalan.'); 
        })
        .finally(() => {
            button.html(originalText);
            button.prop('disabled', false);
        });
    });

});
</script>
@stop