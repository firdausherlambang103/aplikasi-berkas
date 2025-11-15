@extends('adminlte::page')

@section('title', 'Edit Berkas')

@section('content_header')
    <h1>Edit Registrasi Berkas: {{ $berka->nomer_hak }}</h1>
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
        
        <form action="{{ route('berkas.update', $berka->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomer Berkas (Internal)</label>
                        <input type="text" name="nomer_berkas" class="form-control" placeholder="Contoh: 2025/001" value="{{ old('nomer_berkas', $berka->nomer_berkas) }}">
                    </div>

                    <div class="form-group">
                        <label>Kode Klien (Opsional)</label>
                        <select id="kode_klien_select" name="klien_id" class="form-control">
                            <option value="">-- Pilih Kode --</option>
                            @foreach($klienTersedia as $klien)
                                <option value="{{ $klien->id }}" 
                                    {{ old('klien_id', $berka->klien_id) == $klien->id ? 'selected' : '' }}>
                                    {{ $klien->kode_klien }} ({{ $klien->nama_klien }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <label>Nama Pemohon (Wajib)</label>
                        <input type="text" name="nama_pemohon" class="form-control" placeholder="Masukkan nama pemohon" value="{{ old('nama_pemohon', $berka->nama_pemohon) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Nomer WA Pemohon (Manual)</label>
                        {{-- Hapus 'readonly' dan tambahkan 'name' --}}
                        <input type="text" name="nomer_wa" id="nomer_wa" class="form-control" placeholder="Cth: 628123456789" value="{{ old('nomer_wa', $berka->nomer_wa) }}">
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Jenis Hak</label>
                        @php
                            $jenisHakOptions = ['SHGB', 'SHM', 'SHW', 'SHP', 'Leter C'];
                        @endphp
                        <select name="jenis_hak" class="form-control" required>
                            @foreach($jenisHakOptions as $option)
                                <option value="{{ $option }}" 
                                        {{ old('jenis_hak', $berka->jenis_hak) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Nomer Hak</label>
                        <input type="text" name="nomer_hak" class="form-control" 
                               value="{{ old('nomer_hak', $berka->nomer_hak) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" 
                               value="{{ old('kecamatan', $berka->kecamatan) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Desa</label>
                        <input type="text" name="desa" class="form-control" 
                               value="{{ old('desa', $berka->desa) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Permohonan</label>
                        <input type="text" name="jenis_permohonan" class="form-control" 
                               value="{{ old('jenis_permohonan', $berka->jenis_permohonan) }}" required>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>SPA</label>
                        <textarea name="spa" class="form-control" rows="3">{{ old('spa', $berka->spa) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Alih Media</label>
                        <textarea name="alih_media" class="form-control" rows="3">{{ old('alih_media', $berka->alih_media) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="8">{{ old('keterangan', $berka->keterangan) }}</textarea>
                    </div>
                    
                    <hr>
                    {{-- FIELD BARU UNTUK PEMBAYARAN --}}
                    <div class="form-group">
                        <label>Kode Billing</label>
                        <input type="text" name="kode_biling" class="form-control" value="{{ old('kode_biling', $berka->kode_biling) }}">
                    </div>
                    <div class="form-group">
                        <label>Jumlah Bayar (Hanya Angka)</label>
                        <input type="number" name="jumlah_bayar" class="form-control" value="{{ old('jumlah_bayar', $berka->jumlah_bayar) }}" placeholder="Contoh: 500000">
                    </div>

                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-warning">Perbarui Berkas</button>
            <a href="{{ route('berkas.index') }}" class="btn btn-default">Kembali</a>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // --- LOGIKA FETCH OTOMATIS DIHAPUS ---
    });
</script>
@stop