@extends('adminlte::page')

@section('title', 'Edit Klien: ' . $klien->nama_klien)

@section('content_header')
    <h1>Edit Klien: {{ $klien->nama_klien }}</h1>
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
        
        <form action="{{ route('klien.update', $klien->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="kode_klien">Kode Klien</label>
                <input type="text" name="kode_klien" id="kode_klien" class="form-control" value="{{ old('kode_klien', $klien->kode_klien) }}" required>
            </div>
            
            <div class="form-group">
                <label for="nama_klien">Nama Klien</label>
                <input type="text" name="nama_klien" id="nama_klien" class="form-control" value="{{ old('nama_klien', $klien->nama_klien) }}" required>
            </div>

            <div class="form-group">
                <label for="nomer_wa">Nomer WhatsApp (Format: 628xxxx)</label>
                <input type="text" name="nomer_wa" id="nomer_wa" class="form-control" value="{{ old('nomer_wa', $klien->nomer_wa) }}" required>
                <small class="form-text text-muted">Pastikan menggunakan format kode negara (62) di depan.</small>
            </div>

            <button type="submit" class="btn btn-warning">Perbarui Klien</button>
            <a href="{{ route('klien.index') }}" class="btn btn-default">Batal</a>
        </form>
    </div>
</div>
@stop