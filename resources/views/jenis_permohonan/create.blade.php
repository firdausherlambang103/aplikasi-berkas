@extends('adminlte::page')
@section('title', 'Tambah Jenis Permohonan')
@section('content_header')
    <h1>Tambah Jenis Permohonan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('jenis-permohonan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">Nama Jenis Permohonan</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@stop