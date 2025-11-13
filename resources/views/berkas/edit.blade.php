@extends('adminlte::page')

@section('title', 'Edit Berkas')

@section('content_header')
    {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
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
        
        {{-- Form action sudah benar menggunakan $berka->id --}}
        <form action="{{ route('berkas.update', $berka->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Klien (Opsional)</label>
                        <select id="kode_klien_select" class="form-control">
                            <option value="">-- Pilih Kode --</option>
                            @foreach($klienTersedia as $klien)
                                <option value="{{ $klien->kode_klien }}" 
                                        data-id="{{ $klien->id }}"
                                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
                                        {{ $berka->klien_id == $klien->id ? 'selected' : '' }}>
                                    {{ $klien->kode_klien }} ({{ $klien->nama_klien }})
                                </option>
                            @endforeach
                        </select>
                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
                        <input type="hidden" name="klien_id" id="klien_id" value="{{ old('klien_id', $berka->klien_id) }}">
                    </div>

                    <div class="form-group">
                        <label>Nomer WA (Otomatis)</label>
                        {{-- Variabel $nomer_wa_saat_ini sudah benar dari controller --}}
                        <input type="text" id="nomer_wa" class="form-control" readonly 
                               value="{{ $nomer_wa_saat_ini }}">
                    </div>

                    <div class="form-group">
                        <label>Jenis Hak</label>
                        @php
                            $jenisHakOptions = ['SHGB', 'SHM', 'SHW', 'SHP', 'Leter C'];
                        @endphp
                        <select name="jenis_hak" class="form-control" required>
                            @foreach($jenisHakOptions as $option)
                                <option value="{{ $option }}" 
                                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
                                        {{ old('jenis_hak', $berka->jenis_hak) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Nomer Hak</label>
                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas (walaupun di file Anda sudah benar) --}}
                        <input type="text" name="nomer_hak" class="form-control" 
                               value="{{ old('nomer_hak', $berka->nomer_hak) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Kecamatan</label>
                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
                        <input type="text" name="kecamatan" class="form-control" 
                               value="{{ old('kecamatan', $berka->kecamatan) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Desa</label>
                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
                        <input type="text" name="desa" class="form-control" 
                               value="{{ old('desa', $berka->desa) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Permohonan</label>
                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
                        <input type="text" name="jenis_permohonan" class="form-control" 
                               value="{{ old('jenis_permohonan', $berka->jenis_permohonan) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>SPA</label>
                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
                        <textarea name="spa" class="form-control" rows="3">{{ old('spa', $berka->spa) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Alih Media</label>
                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
                        <textarea name="alih_media" class="form-control" rows="3">{{ old('alih_media', $berka->alih_media) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        {{-- PERBAIKAN: Menggunakan $berka, bukan $berkas --}}
                        <textarea name="keterangan" class="form-control" rows="8">{{ old('keterangan', $berka->keterangan) }}</textarea>
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
    // Logika JavaScript untuk auto-fill nomer WA (sama seperti di create.blade.php)
    $(document).ready(function() {
        $('#kode_klien_select').on('change', function() {
            var kode = $(this).val();
            
            var selectedOption = $(this).find('option:selected');
            var klienId = selectedOption.data('id');
            
            $('#klien_id').val(klienId);

            if (kode) {
                fetch(`/api/klien/get-by-kode/${kode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            $('#nomer_wa').val(data.nomer_wa);
                        } else {
                            $('#nomer_wa').val('Kode tidak ditemukan');
                            $('#klien_id').val(''); 
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        $('#nomer_wa').val('Gagal memuat');
                        $('#klien_id').val('');
                    });
            } else {
                $('#nomer_wa').val(''); 
                $('#klien_id').val(''); 
            }
        });
    });
</script>
@stop