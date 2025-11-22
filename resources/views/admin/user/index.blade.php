@extends('adminlte::page')
@section('title', 'Manajemen User')
@section('content_header')
    <h1>Daftar User Sistem</h1>
@stop

@section('content')
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah User Baru</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            @if($user->id !== auth()->user()->id)
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            @else
                                <span class="badge badge-secondary">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop