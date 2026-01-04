@php
    // Memastikan variabel tersedia
    $placeholders = $placeholders ?? [];
    $templates = $templates ?? collect([]);
@endphp

@extends('adminlte::page')

@section('title', 'Daftar Berkas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Manajemen Berkas</h1>
        <a href="{{ route('berkas.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Berkas Baru
        </a>
    </div>
@stop

@section('content')
    {{-- Alert Success --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fas fa-check"></i> {{ $message }}
        </div>
    @endif

    <div class="card card-outline card-primary shadow-sm">
        {{-- HEADER & PENCARIAN --}}
        <div class="card-header">
            <h3 class="card-title mt-1">Daftar Semua Berkas</h3>
            <div class="card-tools">
                <form action="{{ route('berkas.index') }}" method="GET">
                    <div class="input-group input-group-sm" style="width: 280px;">
                        <input type="text" name="search" class="form-control float-right" placeholder="Cari Berkas/Pemohon/Klien..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABEL DATA --}}
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped text-nowrap valign-middle" id="berkas-table">
                <thead class="bg-light">
                    <tr>
                        <th width="5%" class="text-center">ID</th>
                        <th width="22%">Info Berkas & Keterangan</th> 
                        <th width="20%">Pemohon & Kode Klien</th>    
                        <th width="15%">Lokasi (Kec/Desa)</th>
                        <th width="10%">Korektor</th>                
                        <th width="8%" class="text-center">Status</th>
                        <th width="10%" class="text-center">Info WA</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semuaBerkas as $berkas)
                    <tr>
                        <td class="text-center align-middle">{{ $berkas->id }}</td>
                        
                        {{-- KOLOM 1: INFO BERKAS --}}
                        <td class="align-middle">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold text-dark" style="font-size: 1.05rem;">
                                    {{ $berkas->nomer_berkas ?? '-' }}
                                </span>
                                <small class="text-muted mb-1">
                                    {{ $berkas->jenis_hak ?? 'Hak' }} / {{ $berkas->nomer_hak ?? '-' }}
                                </small>
                                
                                {{-- Keterangan Minimalis --}}
                                @if(!empty($berkas->keterangan))
                                    <div class="mt-1 p-1 bg-light rounded border border-light text-wrap" style="font-size: 0.8rem; line-height: 1.2; max-width: 350px;">
                                        <i class="fas fa-info-circle text-info mr-1"></i> 
                                        <span class="text-secondary font-italic">{{ Str::limit($berkas->keterangan, 100) }}</span>
                                    </div>
                                @endif
                            </div>
                        </td>

                        {{-- KOLOM 2: PEMOHON & KLIEN (PERBAIKAN LOGIKA DI SINI) --}}
                        <td class="align-middle">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold">{{ $berkas->nama_pemohon }}</span>
                                
                                {{-- LOGIKA BARU: Cek Relasi Klien ATAU Kolom Langsung --}}
                                @php
                                    $kodeKlien = null;
                                    // Cek 1: Apakah ada relasi ke tabel klien?
                                    if(isset($berkas->klien) && !empty($berkas->klien->kode_klien)) {
                                        $kodeKlien = $berkas->klien->kode_klien;
                                    } 
                                    // Cek 2: Atau apakah ada kolom langsung kode_klien di tabel berkas?
                                    elseif(!empty($berkas->kode_klien)) {
                                        $kodeKlien = $berkas->kode_klien;
                                    }
                                @endphp

                                @if($kodeKlien)
                                    <span class="badge badge-info mt-1 mb-1 align-self-start font-weight-normal px-2">
                                        <i class="fas fa-id-badge mr-1"></i> {{ $kodeKlien }}
                                    </span>
                                @endif

                                @if($berkas->nomer_wa)
                                    <small class="text-success">
                                        <i class="fab fa-whatsapp mr-1"></i> {{ $berkas->nomer_wa }}
                                    </small>
                                @else
                                    <small class="text-muted text-italic">(Tidak ada WA)</small>
                                @endif
                            </div>
                        </td>

                        {{-- KOLOM 3: LOKASI --}}
                        <td class="align-middle">
                            <span class="d-block font-weight-bold text-secondary" style="font-size: 0.9rem;">
                                {{ $berkas->dataKecamatan->nama ?? '-' }}
                            </span>
                            <small class="text-muted">
                                {{ $berkas->dataDesa->nama ?? '-' }}
                            </small>
                        </td>

                        {{-- KOLOM 4: KOREKTOR --}}
                        <td class="align-middle">
                            @if(!empty($berkas->korektor))
                                <div class="user-block text-sm d-flex align-items-center">
                                    <span class="img-circle elevation-1 bg-secondary d-flex align-items-center justify-content-center mr-2" style="width: 25px; height: 25px; font-size: 10px;">
                                        {{ strtoupper(substr($berkas->korektor, 0, 2)) }}
                                    </span>
                                    <span class="text-dark" style="font-size: 0.9rem;">
                                        {{ $berkas->korektor }}
                                    </span>
                                </div>
                            @else
                                <span class="text-muted font-italic">-</span>
                            @endif
                        </td>

                        {{-- KOLOM 5: STATUS --}}
                        <td class="align-middle text-center">
                            @php
                                $badgeClass = match($berkas->status) {
                                    'Selesai' => 'badge-success',
                                    'Proses' => 'badge-primary',
                                    'Kendala' => 'badge-warning',
                                    'Dibatalkan', 'Ditolak' => 'badge-danger',
                                    default => 'badge-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-2 py-1">{{ $berkas->status ?? 'Draft' }}</span>
                        </td>
                        
                        {{-- KOLOM 6: INFO WA --}}
                        <td class="align-middle text-center">
                            @if($berkas->nomer_wa && $templates->count() > 0)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-success btn-xs dropdown-toggle shadow-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fab fa-whatsapp"></i> Kirim
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @foreach($templates as $template)
                                            @php
                                                $logCount = $berkas->waLogs ? $berkas->waLogs->where('wa_template_id', $template->id)->count() : 0;
                                                
                                                $nomerWa = $berkas->nomer_wa;
                                                if(substr($nomerWa, 0, 1) == "0") { $nomerWa = "62" . substr($nomerWa, 1); }
                                                $nomerWa = str_replace(['+', '-', ' '], '', $nomerWa); 
                                            @endphp
                                            
                                            <a class="dropdown-item btn-kirim-wa d-flex justify-content-between align-items-center" href="#"
                                                data-nomor="{{ $nomerWa }}"
                                                data-template-id="{{ $template->id }}"
                                                data-berkas-id="{{ $berkas->id }}"
                                                data-template-text="{{ $template->template_text }}"
                                                data-berkas-json="{{ json_encode($berkas->toArray()) }}"
                                                data-klien-json="{{ json_encode($berkas->klien ? $berkas->klien->toArray() : null) }}"
                                            >
                                                <span>{{ $template->nama_template }}</span>
                                                @if($logCount > 0)
                                                    <span class="badge badge-light ml-2">{{ $logCount }}</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        {{-- KOLOM 7: AKSI --}}
                        <td class="align-middle text-center">
                            <div class="btn-group">
                                <a href="{{ route('berkas.edit', $berkas->id) }}" class="btn btn-warning btn-xs" title="Edit">
                                    <i class="fa fa-edit text-white"></i>
                                </a>
                                <form action="{{ route('berkas.destroy', $berkas->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus berkas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" title="Hapus" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 text-gray-300"></i>
                            <p class="mb-0">
                                @if(request('search'))
                                    Data tidak ditemukan untuk pencarian "<strong>{{ request('search') }}</strong>"
                                @else
                                    Belum ada data berkas.
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- FOOTER PAGINATION --}}
        <div class="card-footer clearfix">
            <div class="float-left text-muted text-sm pt-2">
                Menampilkan {{ $semuaBerkas->firstItem() }} s/d {{ $semuaBerkas->lastItem() }} dari {{ $semuaBerkas->total() }} data
            </div>
            <div class="float-right">
                {{ $semuaBerkas->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>


    {{-- MODAL KONFIRMASI PENGIRIMAN PESAN WA --}}
    <div class="modal fade" id="kirimWaModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="modalLabel"><i class="fab fa-whatsapp"></i> Konfirmasi Kirim WA</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="modal-berkas-id">
            <input type="hidden" id="modal-template-id">
            <div class="form-group">
                <label class="font-weight-bold small text-uppercase">Tujuan:</label>
                <input type="text" id="modal-nomor-wa" class="form-control bg-light" readonly style="font-family: monospace; font-size: 1.1rem;">
            </div>
            <div class="form-group">
                <label class="font-weight-bold small text-uppercase">Preview Pesan:</label>
                <textarea id="modal-isi-pesan" class="form-control bg-light" rows="8" readonly style="font-size: 0.9rem;"></textarea>
                <small class="text-muted">*Placeholder telah diganti dengan data asli.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-success shadow" id="btn-kirim-final">
                <i class="fas fa-paper-plane mr-1"></i> Kirim Sekarang
            </button>
          </div>
        </div>
      </div>
    </div>
@stop

@section('css')
    <style>
        .table-vcenter td {
            vertical-align: middle !important;
        }
        .btn-xs {
            padding: 0.125rem 0.4rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    
    // Ambil data placeholder dari Controller
    const allPlaceholders = @json($placeholders);

    // --- DataTables Init (Sorting Only) ---
    try {
        $('#berkas-table').DataTable({
            "paging": false,     
            "lengthChange": false,
            "searching": false,  
            "ordering": true,
            "order": [[ 0, "desc" ]],
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "emptyTable": " " 
            }
        });
    } catch (e) {
        console.warn("Plugin DataTables warning: " + e.message);
    }

    // --- Helper Nested Object ---
    function getNestedValue(obj, path) {
        if (!path) return '';
        return path.split('.').reduce((acc, part) => (acc && acc[part] !== undefined) ? acc[part] : '', obj);
    }

    // --- 1. Tombol Buka Modal ---
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

            if (value === null || typeof value === 'object') value = ''; 
            pesanFinal = pesanFinal.replace(new RegExp(key.replace(/\[/g, '\\[').replace(/\]/g, '\\]'), 'g'), value);
        });
        
        $('#modal-nomor-wa').val(nomor);
        $('#modal-berkas-id').val(berkasId);
        $('#modal-template-id').val(templateId);
        $('#modal-isi-pesan').val(pesanFinal); 

        $('#kirimWaModal').modal('show');
    });

    // --- 2. Tombol Eksekusi Kirim ---
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

        fetch('{{ config('app.wa_api_url', 'http://192.168.0.42:3000') }}/kirim-pesan', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nomor, pesan, berkas_id, wa_template_id })
        })
        .then(response => {
            if (!response.ok) throw new Error(`Server WA Error: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                fetch('/log-wa-send', { 
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    body: JSON.stringify({ berkas_id, wa_template_id })
                })
                .then(res => {
                    if(res.ok) {
                        toastr_success('Pesan terkirim & Log tersimpan!');
                        $('#kirimWaModal').modal('hide');
                        setTimeout(() => location.reload(), 1000); 
                    } else {
                        alert("Pesan WA terkirim, tapi gagal menyimpan Log di database.");
                    }
                });
            } else {
                toastr_error('Gagal mengirim: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghubungi Server WA.'); 
        })
        .finally(() => {
            button.html(originalText);
            button.prop('disabled', false);
        });
    });

    function toastr_success(msg) {
        if (typeof toastr !== 'undefined') toastr.success(msg);
        else alert(msg);
    }
    function toastr_error(msg) {
        if (typeof toastr !== 'undefined') toastr.error(msg);
        else alert(msg);
    }
});
</script>
@stop