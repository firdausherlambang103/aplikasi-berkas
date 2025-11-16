@extends('adminlte::page')
@section('title', 'Edit Jenis Permohonan')
@section('content_header')
    <h1>Edit Jenis Permohonan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('jenis-permohonan.update', $jenisPermohonan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama">Nama Jenis Permohonan</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $jenisPermohonan->nama) }}" required>
                @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@stop