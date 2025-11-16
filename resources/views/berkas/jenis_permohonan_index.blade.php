@extends('adminlte::page')
@section('title', 'Data Jenis Permohonan')
@section('content_header')
    <h1>Data Jenis Permohonan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('jenis-permohonan.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Jenis Permohonan</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Jenis Permohonan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permohonans as $jp)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jp->nama }}</td>
                    <td>
                        <form action="{{ route('jenis-permohonan.destroy', $jp->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                            <a href="{{ route('jenis-permohonan.edit', $jp->id) }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
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
            {{ $permohonans->links() }}
        </div>
    </div>
</div>
@stop