@extends('adminlte::page')

@section('title', 'Tambah Klien Baru')

@section('content_header')
    <h1>Tambah Klien Baru</h1>
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
        
        <form action="{{ route('klien.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="kode_klien">Kode Klien</label>
                <input type="text" name="kode_klien" id="kode_klien" class="form-control" placeholder="Contoh: K-001" value="{{ old('kode_klien') }}" required>
            </div>
            
            <div class="form-group">
                <label for="nama_klien">Nama Klien</label>
                <input type="text" name="nama_klien" id="nama_klien" class="form-control" placeholder="Masukkan Nama Pemohon/Klien" value="{{ old('nama_klien') }}" required>
            </div>

            <div class="form-group">
                <label for="nomer_wa">Nomer WhatsApp (Format: 628xxxx)</label>
                <input type="text" name="nomer_wa" id="nomer_wa" class="form-control" placeholder="Contoh: 6281234567890" value="{{ old('nomer_wa') }}" required>
                <small class="form-text text-muted">Pastikan menggunakan format kode negara (62) di depan.</small>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Klien</button>
            <a href="{{ route('klien.index') }}" class="btn btn-default">Kembali</a>
        </form>
    </div>
</div>
@stop