@extends('adminlte::page')
@section('title', 'Tambah User')
@section('content_header') <h1>Tambah User Baru</h1> @stop

@section('content')
<div class="card col-md-6">
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button class="btn btn-success">Simpan User</button>
        </form>
    </div>
</div>
@stop