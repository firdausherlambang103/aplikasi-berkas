@extends('adminlte::page')
@section('title', 'Edit Kecamatan')
@section('content_header')
    <h1>Edit Kecamatan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('kecamatan.update', $kecamatan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama">Nama Kecamatan</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $kecamatan->nama) }}" required>
                @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@stop