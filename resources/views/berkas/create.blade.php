@extends('adminlte::page')

@section('title', 'Registrasi Berkas Baru')

@section('content_header')
    <h1>Registrasi Berkas Baru</h1>
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

        <form action="{{ route('berkas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomer Berkas (Internal)</label>
                        <input type="text" name="nomer_berkas" class="form-control @error('nomer_berkas') is-invalid @enderror" placeholder="Contoh: 2025/001" value="{{ old('nomer_berkas') }}">
                        @error('nomer_berkas') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Kode Klien (Opsional - Auto-fill WA)</label>
                        <select id="kode_klien_select" name="klien_id" class="form-control @error('klien_id') is-invalid @enderror">
                            <option value="">-- Pilih Kode (untuk auto-fill) --</option>
                            @foreach($klienTersedia as $klien)
                                <option value="{{ $klien->id }}" 
                                        data-nomer-wa="{{ $klien->nomer_wa }}" 
                                        {{ old('klien_id') == $klien->id ? 'selected' : '' }}>
                                    {{ $klien->kode_klien }} ({{ $klien->nama_klien }})
                                </option>
                            @endforeach
                        </select>
                         @error('klien_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <label>Nama Pemohon (Wajib)</label>
                        <input type="text" name="nama_pemohon" class="form-control @error('nama_pemohon') is-invalid @enderror" placeholder="Masukkan nama pemohon" value="{{ old('nama_pemohon') }}" required>
                         @error('nama_pemohon') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Nomer WA Pemohon</label>
                        <input type="text" name="nomer_wa" id="nomer_wa" class="form-control @error('nomer_wa') is-invalid @enderror" placeholder="Cth: 628123456789" value="{{ old('nomer_wa') }}">
                        @error('nomer_wa') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Jenis Hak</label>
                        <select name="jenis_hak" class="form-control @error('jenis_hak') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Hak --</option>
                            <option value="SHM" {{ old('jenis_hak') == 'SHM' ? 'selected' : '' }}>SHM</option>
                            <option value="SHGB" {{ old('jenis_hak') == 'SHGB' ? 'selected' : '' }}>SHGB</option>
                            <option value="SHW" {{ old('jenis_hak') == 'SHW' ? 'selected' : '' }}>SHW</option>
                            <option value="SHP" {{ old('jenis_hak') == 'SHP' ? 'selected' : '' }}>SHP</option>
                            <option value="Leter C" {{ old('jenis_hak') == 'Leter C' ? 'selected' : '' }}>Leter C</option>
                        </select>
                         @error('jenis_hak') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Nomer Hak</label>
                        <input type="text" name="nomer_hak" class="form-control @error('nomer_hak') is-invalid @enderror" value="{{ old('nomer_hak') }}" required>
                         @error('nomer_hak') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- DROPDOWN KECAMATAN --}}
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <select name="kecamatan_id" id="kecamatan_id" class="form-control">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama ?? $kecamatan->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DROPDOWN DESA --}}
                    <div class="form-group">
                        <label>Desa</label>
                        <select name="desa_id" id="desa_id" class="form-control">
                            <option value="">Pilih Kecamatan Terlebih Dahulu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jenis Permohonan</label>
                        <select name="jenis_permohonan_id" class="form-control @error('jenis_permohonan_id') is-invalid @enderror" required>
                             <option value="">-- Pilih Jenis Permohonan --</option>
                             @foreach($jenisPermohonans as $jp)
                                <option value="{{ $jp->id }}" {{ old('jenis_permohonan_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_permohonan_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- INPUT BARU: KOREKTOR --}}
                    <div class="form-group">
                        <label>Korektor</label>
                        <select name="korektor" class="form-control @error('korektor') is-invalid @enderror">
                            <option value="">-- Pilih Korektor (User) --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}" {{ old('korektor') == $user->name ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('korektor') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- INPUT BARU: STATUS --}}
                    <div class="form-group">
                        <label>Status Berkas</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="Baru" {{ old('status') == 'Baru' ? 'selected' : '' }}>Baru</option>
                            <option value="Cetak SPS" {{ old('status') == 'Cetak SPS' ? 'selected' : '' }}>Cetak SPS</option>
                            <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Kembali" {{ old('status') == 'Kembali' ? 'selected' : '' }}>Kembali</option>
                            <option value="Entri Data" {{ old('status') == 'Entri Data' ? 'selected' : '' }}>Entri Data</option>
                            <option value="Dibatalkan" {{ old('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>SPA</label>
                        <textarea name="spa" class="form-control" rows="3">{{ old('spa') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Alih Media</label>
                        <textarea name="alih_media" class="form-control" rows="3">{{ old('alih_media') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="5">{{ old('keterangan') }}</textarea>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label>Kode Billing</label>
                        <input type="text" name="kode_biling" class="form-control @error('kode_biling') is-invalid @enderror" value="{{ old('kode_biling') }}">
                        @error('kode_biling') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Jumlah Bayar (Hanya Angka)</label>
                        <input type="number" name="jumlah_bayar" class="form-control @error('jumlah_bayar') is-invalid @enderror" value="{{ old('jumlah_bayar') }}" placeholder="Contoh: 500000">
                        @error('jumlah_bayar') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary">Simpan Registrasi</button>
        </form>
    </div>
</div>
@stop

@section('css')
<style>
    select.form-control option {
        color: #333 !important;
        background-color: #fff !important;
    }
    .dark-mode select.form-control option {
        color: #000 !important;
        background-color: #fff !important;
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
        if ($('#kode_klien_select').val() != "") { $('#kode_klien_select').trigger('change'); }

        // Dependent Dropdown
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
                            desaSelect.append('<option value="'+ value.id +'">'+ value.nama +'</option>');
                        });
                    },
                    error: function() { desaSelect.empty().append('<option value="">Gagal mengambil data</option>'); }
                });
            } else {
                desaSelect.empty().append('<option value="">-- Pilih Kecamatan Dulu --</option>');
            }
        });

        // Handle Old Value
        var oldKecamatanId = "{{ old('kecamatan_id') }}";
        var oldDesaId = "{{ old('desa_id') }}";
        if (oldKecamatanId) {
            $('#kecamatan_id').val(oldKecamatanId);
            var desaSelect = $('#desa_id');
            desaSelect.empty().append('<option value="">Loading...</option>');
            $.ajax({
                url: '/get-desa/' + oldKecamatanId,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    desaSelect.empty().append('<option value="">-- Pilih Desa --</option>');
                    $.each(data, function(key, value) {
                        var isSelected = (value.id == oldDesaId) ? 'selected' : '';
                        desaSelect.append('<option value="'+ value.id +'" '+ isSelected +'>'+ value.nama +'</option>');
                    });
                }
            });
        }
    });
</script>
@stop