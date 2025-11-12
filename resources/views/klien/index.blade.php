@extends('adminlte::page')

@section('title', 'Daftar Klien dan Kode')

@section('content_header')
    <h1>Manajemen Klien dan Kode</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $message }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('klien.create') }}" class="btn btn-primary">Tambah Klien Baru</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="klien-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Klien</th>
                        <th>Nama Klien</th>
                        <th>Nomer WA</th>
                        <th width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($klien as $data)
                    <tr>
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->kode_klien }}</td>
                        <td>{{ $data->nama_klien }}</td>
                        <td>{{ $data->nomer_wa }}</td>
                        <td>
                            <form action="{{ route('klien.destroy', $data->id) }}" method="POST">
                                <a href="{{ route('klien.edit', $data->id) }}" class="btn btn-xs btn-warning">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

{{-- Tambahkan script DataTables jika Anda ingin tabelnya interaktif --}}
@section('js')
    {{-- Anda perlu menginstal DataTables secara terpisah atau menggunakan fitur yang disediakan AdminLTE --}}
    <script>
        $(function () {
            $('#klien-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@stop