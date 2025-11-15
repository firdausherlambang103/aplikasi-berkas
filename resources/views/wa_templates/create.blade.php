@php
    // Define available placeholders for the info box
    $placeholders = [
        '[nama]' => 'Nama Klien (Contoh: Budi Santoso)',
        '[nomer_hak]' => 'Nomer Hak Berkas',
        '[jenis_hak]' => 'Jenis Hak (Contoh: SHM)',
        '[kecamatan]' => 'Kecamatan',
        '[desa]' => 'Desa',
        // '[kode_klien]' => 'Kode Klien (Contoh: K-001)', // You can add this if needed
    ];
@endphp

@extends('adminlte::page')

@section('title', 'Tambah Template WA Baru')

@section('content_header')
    <h1>Tambah Template WA Baru</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Formulir Template Baru</h3>
    </div>
    
    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger mx-3 mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('wa-templates.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="nama_template">Nama Template</label>
                <input type="text" name="nama_template" id="nama_template" class="form-control" 
                       placeholder="Contoh: Notifikasi Registrasi Selesai" value="{{ old('nama_template') }}" required>
            </div>
            
            <div class="form-group">
                <label for="template_text">Isi Template Pesan</label>
                <textarea name="template_text" id="template_text" class="form-control" rows="10" 
                          placeholder="Tulis pesan Anda di sini, gunakan placeholder di bawah..." required>{{ old('template_text') }}</textarea>
            </div>
            
            {{-- Placeholder Info Box --}}
            <div class="callout callout-info">
                <h5>Placeholder yang Tersedia:</h5>
                <p>Gunakan placeholder ini di template Anda. Sistem akan otomatis menggantinya saat pesan dikirim:</p>
                <ul>
                    @foreach($placeholders as $var => $desc)
                        <li><code>{{ $var }}</code> : {{ $desc }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Template</button>
            <a href="{{ route('wa-templates.index') }}" class="btn btn-default">Kembali</a>
        </div>
    </form>
</div>
@stop