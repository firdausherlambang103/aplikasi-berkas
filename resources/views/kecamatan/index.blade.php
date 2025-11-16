
@extends('adminlte::page')
@section('title', 'Data Kecamatan')
@section('content_header')
    <h1>Data Kecamatan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('kecamatan.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Kecamatan</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kecamatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kecamatans as $kec)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $kec->nama }}</td>
                    <td>
                        <form action="{{ route('kecamatan.destroy', $kec->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                            <a href="{{ route('kecamatan.edit', $kec->id) }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Data kosong</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $kecamatans->links() }}
        </div>
    </div>
</div>
@stop