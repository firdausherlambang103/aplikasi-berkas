@extends('adminlte::page')
@section('title', 'Tambah Desa')
@section('content_header')
    <h1>Tambah Desa</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('desa.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="kecamatan_id">Kecamatan</label>
                <select name="kecamatan_id" class="form-control @error('kecamatan_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach($kecamatans as $kec)
                        <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
                    @endforeach
                </select>
                @error('kecamatan_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nama">Nama Desa</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@stop