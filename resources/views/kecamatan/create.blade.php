@extends('adminlte::page')
@section('title', 'Tambah Kecamatan')
@section('content_header')
    <h1>Tambah Kecamatan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('kecamatan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">Nama Kecamatan</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@stop