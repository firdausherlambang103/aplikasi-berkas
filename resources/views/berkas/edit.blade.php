@extends('adminlte::page')

@section('title', 'Edit Berkas')

@section('content_header')
    <h1>Edit Berkas: {{ $berkas->nama_pemohon }}</h1>
@stop

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Form Perubahan Data</h3>
    </div>
    
    <form action="{{ route('berkas.update', $berkas->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Ada Kesalahan Input!</h5>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                {{-- KOLOM KIRI --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomer Berkas (Internal)</label>
                        <input type="text" name="nomer_berkas" class="form-control" placeholder="Contoh: 2025/001" value="{{ old('nomer_berkas', $berkas->nomer_berkas) }}">
                    </div>

                    <div class="form-group">
                        <label>Kode Klien (Opsional - Auto-fill WA)</label>
                        <select id="kode_klien_select" name="klien_id" class="form-control select2 @error('klien_id') is-invalid @enderror">
                            <option value="">-- Pilih Kode (untuk auto-fill) --</option>
                            @foreach($klienTersedia as $klien)
                                <option value="{{ $klien->id }}" 
                                        data-nomer-wa="{{ $klien->nomer_wa }}" 
                                        {{ old('klien_id', $berkas->klien_id) == $klien->id ? 'selected' : '' }}>
                                    {{ $klien->kode_klien }} ({{ $klien->nama_klien }})
                                </option>
                            @endforeach
                        </select>
                        @error('klien_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <label>Nama Pemohon <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pemohon" class="form-control @error('nama_pemohon') is-invalid @enderror" placeholder="Masukkan nama pemohon" value="{{ old('nama_pemohon', $berkas->nama_pemohon) }}" required>
                        @error('nama_pemohon') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Nomer WA Pemohon</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                            </div>
                            <input type="text" name="nomer_wa" id="nomer_wa" class="form-control" placeholder="Cth: 628123456789" value="{{ old('nomer_wa', $berkas->nomer_wa) }}">
                        </div>
                    </div>

                    {{-- UPDATE: KOREKTOR JADI DROPDOWN --}}
                    <div class="form-group">
                        <label>Korektor</label>
                        <select name="korektor" class="form-control @error('korektor') is-invalid @enderror">
                            <option value="">-- Pilih Korektor --</option>
                            {{-- Pastikan controller mengirim variable $users --}}
                            @if(isset($users))
                                @foreach($users as $user)
                                    {{-- Menyimpan Nama User --}}
                                    <option value="{{ $user->name }}" {{ old('korektor', $berkas->korektor) == $user->name ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            @else
                                {{-- Fallback jika variable users belum dikirim dari controller --}}
                                <option value="{{ $berkas->korektor }}" selected>{{ $berkas->korektor }}</option>
                            @endif
                        </select>
                        @error('korektor') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    {{-- END UPDATE --}}

                    <hr>

                    <div class="form-group">
                        <label>Jenis Hak <span class="text-danger">*</span></label>
                        <select name="jenis_hak" class="form-control @error('jenis_hak') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Hak --</option>
                            <option value="SHM" {{ old('jenis_hak', $berkas->jenis_hak) == 'SHM' ? 'selected' : '' }}>SHM</option>
                            <option value="SHGB" {{ old('jenis_hak', $berkas->jenis_hak) == 'SHGB' ? 'selected' : '' }}>SHGB</option>
                            <option value="SHW" {{ old('jenis_hak', $berkas->jenis_hak) == 'SHW' ? 'selected' : '' }}>SHW</option>
                            <option value="SHP" {{ old('jenis_hak', $berkas->jenis_hak) == 'SHP' ? 'selected' : '' }}>SHP</option>
                            <option value="Leter C" {{ old('jenis_hak', $berkas->jenis_hak) == 'Leter C' ? 'selected' : '' }}>Leter C</option>
                            <option value="Lainnya" {{ old('jenis_hak', $berkas->jenis_hak) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_hak') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Nomer Hak <span class="text-danger">*</span></label>
                        <input type="text" name="nomer_hak" class="form-control @error('nomer_hak') is-invalid @enderror" value="{{ old('nomer_hak', $berkas->nomer_hak) }}" required>
                        @error('nomer_hak') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- DROPDOWN KECAMATAN --}}
                    <div class="form-group">
                        <label>Kecamatan <span class="text-danger">*</span></label>
                        <select name="kecamatan_id" id="kecamatan_id" class="form-control @error('kecamatan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec->id }}" {{ old('kecamatan_id', $berkas->kecamatan_id) == $kec->id ? 'selected' : '' }}>
                                    {{ $kec->nama ?? $kec->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                        @error('kecamatan_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- DROPDOWN DESA --}}
                    <div class="form-group">
                        <label>Desa <span class="text-danger">*</span></label>
                        <select name="desa_id" id="desa_id" class="form-control @error('desa_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kecamatan Dulu --</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}" {{ old('desa_id', $berkas->desa_id) == $desa->id ? 'selected' : '' }}>
                                    {{ $desa->nama ?? $desa->nama_desa }}
                                </option>
                            @endforeach
                        </select>
                        @error('desa_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Jenis Permohonan <span class="text-danger">*</span></label>
                        <select name="jenis_permohonan_id" class="form-control @error('jenis_permohonan_id') is-invalid @enderror" required>
                             <option value="">-- Pilih Jenis Permohonan --</option>
                             @foreach($jenisPermohonans as $jp)
                                <option value="{{ $jp->id }}" {{ old('jenis_permohonan_id', $berkas->jenis_permohonan_id) == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_permohonan_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            @foreach(['Baru', 'Cetak SPS', 'Selesai', 'Kembali', 'Entri Data', 'Dibatalkan'] as $st)
                                <option value="{{ $st }}" {{ old('status', $berkas->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <hr>
                    
                    <div class="form-group">
                        <label>SPA</label>
                        <textarea name="spa" class="form-control" rows="3" placeholder="Info SPA...">{{ old('spa', $berkas->spa) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Alih Media</label>
                        <textarea name="alih_media" class="form-control" rows="3" placeholder="Info Alih Media...">{{ old('alih_media', $berkas->alih_media) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="5" placeholder="Keterangan tambahan...">{{ old('keterangan', $berkas->keterangan) }}</textarea>
                    </div>

                    <hr>
                    
                    <div class="form-group">
                        <label>Kode Billing</label>
                        <input type="text" name="kode_biling" class="form-control" value="{{ old('kode_biling', $berkas->kode_biling) }}">
                    </div>
                    <div class="form-group">
                        <label>Jumlah Bayar (Hanya Angka)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="jumlah_bayar" class="form-control" value="{{ old('jumlah_bayar', $berkas->jumlah_bayar) }}" placeholder="0">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('berkas.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@stop

{{-- CSS FIX UNTUK ADMINLTE DARK MODE --}}
@section('css')
<style>
    /* Memaksa warna teks opsi menjadi hitam agar terbaca */
    select.form-control option {
        color: #000000 !important;
        background-color: #ffffff !important;
    }
    .dark-mode select.form-control option {
        color: #000000 !important;
        background-color: #ffffff !important;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Auto-fill WA dari Klien
        $('#kode_klien_select').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var nomerWa = selectedOption.data('nomer-wa') || '';
            // Hanya isi jika nomer wa di input masih kosong agar tidak menimpa editan user
            if($('#nomer_wa').val() == '' || nomerWa != '') {
                $('#nomer_wa').val(nomerWa);
            }
        });

        // Dependent Dropdown Kecamatan -> Desa
        $('#kecamatan_id').on('change', function() {
            var kecamatanID = $(this).val();
            var desaSelect = $('#desa_id');
            desaSelect.empty().append('<option value="">Loading...</option>'); 

            if (kecamatanID) {
                $.ajax({
                    url: '/get-desa/' + kecamatanID, 
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        desaSelect.empty().append('<option value="">-- Pilih Desa --</option>');
                        $.each(data, function(key, value) {
                            // Cek jika ada desa_id yang tersimpan sebelumnya (untuk edit jika ajax reload)
                            desaSelect.append('<option value="'+ value.id +'">'+ value.nama +'</option>');
                        });
                    },
                    error: function() {
                         desaSelect.empty().append('<option value="">Gagal mengambil data</option>');
                    }
                });
            } else {
                desaSelect.empty().append('<option value="">-- Pilih Kecamatan Dulu --</option>');
            }
        });
    });
</script>
@stop