@extends('adminlte::page')

@section('title', 'Edit Berkas')

@section('content_header')
    <h1>Edit Berkas: {{ $berkas->nama_pemohon }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('berkas.update', $berkas->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomer Berkas (Internal)</label>
                        <input type="text" name="nomer_berkas" class="form-control" placeholder="Contoh: 2025/001" value="{{ old('nomer_berkas', $berkas->nomer_berkas) }}">
                    </div>

                    <div class="form-group">
                        <label>Kode Klien (Opsional - Auto-fill WA)</label>
                        <select id="kode_klien_select" name="klien_id" class="form-control @error('klien_id') is-invalid @enderror">
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
                        <label>Nama Pemohon (Wajib)</label>
                        <input type="text" name="nama_pemohon" class="form-control @error('nama_pemohon') is-invalid @enderror" placeholder="Masukkan nama pemohon" value="{{ old('nama_pemohon', $berkas->nama_pemohon) }}" required>
                         @error('nama_pemohon') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Nomer WA Pemohon</label>
                        <input type="text" name="nomer_wa" id="nomer_wa" class="form-control" placeholder="Cth: 628123456789" value="{{ old('nomer_wa', $berkas->nomer_wa) }}">
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Jenis Hak</label>
                        <select name="jenis_hak" class="form-control @error('jenis_hak') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Hak --</option>
                            <option value="SHM" {{ old('jenis_hak', $berkas->jenis_hak) == 'SHM' ? 'selected' : '' }}>SHM</option>
                            <option value="SHGB" {{ old('jenis_hak', $berkas->jenis_hak) == 'SHGB' ? 'selected' : '' }}>SHGB</option>
                            <option value="SHW" {{ old('jenis_hak', $berkas->jenis_hak) == 'SHW' ? 'selected' : '' }}>SHW</option>
                            <option value="SHP" {{ old('jenis_hak', $berkas->jenis_hak) == 'SHP' ? 'selected' : '' }}>SHP</option>
                            <option value="Leter C" {{ old('jenis_hak', $berkas->jenis_hak) == 'Leter C' ? 'selected' : '' }}>Leter C</option>
                        </select>
                         @error('jenis_hak') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Nomer Hak</label>
                        <input type="text" name="nomer_hak" class="form-control @error('nomer_hak') is-invalid @enderror" value="{{ old('nomer_hak', $berkas->nomer_hak) }}" required>
                         @error('nomer_hak') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- DROPDOWN KECAMATAN --}}
                    <div class="form-group">
                        <label>Kecamatan</label>
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
                        <label>Desa</label>
                        <select name="desa_id" id="desa_id" class="form-control @error('desa_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kecamatan Dulu --</option>
                            {{-- Pre-load desa berdasarkan data berkas --}}
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}" {{ old('desa_id', $berkas->desa_id) == $desa->id ? 'selected' : '' }}>
                                    {{ $desa->nama ?? $desa->nama_desa }}
                                </option>
                            @endforeach
                        </select>
                        @error('desa_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Jenis Permohonan</label>
                        <select name="jenis_permohonan_id" class="form-control @error('jenis_permohonan_id') is-invalid @enderror" required>
                             <option value="">-- Pilih Jenis Permohonan --</option>
                             @foreach($jenisPermohonans as $jp)
                                <option value="{{ $jp->id }}" {{ old('jenis_permohonan_id', $berkas->jenis_permohonan_id) == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_permohonan_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- PERBAIKAN: Status diubah jadi Dropdown --}}
                     <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Baru" {{ old('status', $berkas->status) == 'Baru' ? 'selected' : '' }}>Baru</option>
                            <option value="Proses" {{ old('status', $berkas->status) == 'Proses' ? 'selected' : '' }}>Proses</option>
                            <option value="Selesai" {{ old('status', $berkas->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Kendala" {{ old('status', $berkas->status) == 'Kendala' ? 'selected' : '' }}>Kendala</option>
                            <option value="Dibatalkan" {{ old('status', $berkas->status) == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- PERBAIKAN: Posisi Berkas DIHAPUS --}}
                    {{-- PERBAIKAN: Tanggal Selesai DIHAPUS --}}

                    <hr>
                    
                    <div class="form-group">
                        <label>SPA</label>
                        <textarea name="spa" class="form-control" rows="3">{{ old('spa', $berkas->spa) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Alih Media</label>
                        <textarea name="alih_media" class="form-control" rows="3">{{ old('alih_media', $berkas->alih_media) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="5">{{ old('keterangan', $berkas->keterangan) }}</textarea>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label>Kode Billing</label>
                        <input type="text" name="kode_biling" class="form-control" value="{{ old('kode_biling', $berkas->kode_biling) }}">
                    </div>
                    <div class="form-group">
                        <label>Jumlah Bayar (Hanya Angka)</label>
                        <input type="number" name="jumlah_bayar" class="form-control" value="{{ old('jumlah_bayar', $berkas->jumlah_bayar) }}" placeholder="Contoh: 500000">
                    </div>

                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary">Update Berkas</button>
        </form>
    </div>
</div>
@stop

{{-- CSS FIX UNTUK ADMINLTE DARK MODE --}}
@section('css')
<style>
    /* Memaksa warna teks opsi menjadi hitam */
    select.form-control option {
        color: #000000 !important;
        background-color: #ffffff !important;
    }
    /* Khusus untuk tema AdminLTE */
    .dark-mode select.form-control option {
        color: #000000 !important;
        background-color: #ffffff !important;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Auto-fill WA
        $('#kode_klien_select').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var nomerWa = selectedOption.data('nomer-wa') || '';
            $('#nomer_wa').val(nomerWa);
        });

        // Dependent Dropdown
        $('#kecamatan_id').on('change', function() {
            var kecamatanID = $(this).val();
            var desaSelect = $('#desa_id');
            desaSelect.empty().append('<option value="">Loading...</option>'); 

            if (kecamatanID) {
                // Menggunakan URL /get-desa/ (tanpa /api/) sesuai dengan routes yang diperbaiki
                $.ajax({
                    url: '/get-desa/' + kecamatanID, 
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        desaSelect.empty().append('<option value="">-- Pilih Desa --</option>');
                        $.each(data, function(key, value) {
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