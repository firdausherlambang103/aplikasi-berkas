@extends('adminlte::page')

@section('title', 'Pengaturan Template Pesan')

@section('content_header')
    <h1>Pengaturan Template Pesan WhatsApp</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $message }}
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Template Pesan</h3>
        </div>
        <form action="{{ route('pengaturan.update') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="template_wa">Template Pesan</label>
                    <textarea name="template_wa" id="template_wa" class="form-control" rows="12">{{ old('template_wa', $template->value) }}</textarea>
                </div>

                <div class="callout callout-info">
                    <h5>Placeholder yang Tersedia:</h5>
                    <p>Gunakan placeholder ini di template Anda. Sistem akan otomatis menggantinya saat pesan dikirim:</p>
                    <ul>
                        <li><code>[nama]</code> : Nama Klien (Contoh: Budi Santoso)</li>
                        <li><code>[kode_klien]</code> : Kode Klien (Contoh: K-001)</li>
                        <li><code>[nomer_hak]</code> : Nomer Hak Berkas</li>
                        <li><code>[jenis_hak]</code> : Jenis Hak (Contoh: SHM)</li>
                        <li><code>[kecamatan]</code> : Kecamatan</li>
                        <li><code>[desa]</code> : Desa</li>
                    </ul>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Template</button>
            </div>
        </form>
    </div>
@stop