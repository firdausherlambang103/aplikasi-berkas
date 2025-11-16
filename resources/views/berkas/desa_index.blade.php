@extends('adminlte::page')
@section('title', 'Data Desa')
@section('content_header')
    <h1>Data Desa</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('desa.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Desa</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Desa</th>
                    <th>Nama Kecamatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($desas as $desa)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $desa->nama }}</td>
                    <td>{{ $desa->kecamatan->nama ?? 'N/A' }}</td>
                    <td>
                        <form action="{{ route('desa.destroy', $desa->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                            <a href="{{ route('desa.edit', $desa->id) }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Data kosong</td>
                </tr>
                @endforelse
            </tbody>
        </table>
         <div class="mt-2">
            {{ $desas->links() }}
        </div>
    </div>
</div>
@stop