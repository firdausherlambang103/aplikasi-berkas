@extends('adminlte::page')

@section('title', 'Tambah Placeholder Baru')

@section('content_header')
    <h1>Tambah Placeholder Baru</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Formulir Placeholder</h3>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger mx-3 mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('wa-placeholders.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="placeholder_key">Placeholder Key</label>
                <input type="text" name="placeholder_key" id="placeholder_key" class="form-control" 
                       placeholder="Contoh: [nama] atau [nomer_hak]" value="{{ old('placeholder_key') }}" required>
                <small class="form-text text-muted">Harus diawali dengan '[' dan diakhiri dengan ']'.</small>
            </div>
            
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <input type="text" name="deskripsi" id="deskripsi" class="form-control" 
                       placeholder="Contoh: Nama Klien Pemohon" value="{{ old('deskripsi') }}" required>
            </div>

            <div class="form-group">
                <label for="data_source">Data Source (Sumber Data)</label>
                <input type="text" name="data_source" id="data_source" class="form-control" 
                       placeholder="Contoh: klien.nama_klien atau berkas.spa" value="{{ old('data_source') }}" required>
                <small class="form-text text-muted">Format: <strong>relasi.kolom</strong>. <br>
                    Nama relasi yang tersedia: <strong>berkas</strong> atau <strong>klien</strong>. <br>
                    Contoh: <code>berkas.nomer_hak</code>, <code>klien.nama_klien</code>, <code>berkas.spa</code>, <code>berkas.keterangan</code>.
                </small>
            </div>
            
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Placeholder</button>
            <a href="{{ route('wa-placeholders.index') }}" class="btn btn-default">Kembali</a>
        </div>
    </form>
</div>
@stop