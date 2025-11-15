@extends('adminlte::page')

@section('title', 'Manajemen Placeholder WA')

@section('content_header')
    <h1>Manajemen Placeholder WA</h1>
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
            <a href="{{ route('wa-placeholders.create') }}" class="btn btn-primary">Tambah Placeholder Baru</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="placeholder-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Placeholder Key</th>
                        <th>Deskripsi</th>
                        <th>Data Source (Relasi.Kolom)</th>
                        <th width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($placeholders as $placeholder)
                    <tr>
                        <td>{{ $placeholder->id }}</td>
                        <td><code>{{ $placeholder->placeholder_key }}</code></td>
                        <td>{{ $placeholder->deskripsi }}</td>
                        <td><code>{{ $placeholder->data_source }}</code></td>
                        <td>
                            <form action="{{ route('wa-placeholders.destroy', $placeholder->id) }}" method="POST" style="display:inline-block;">
                                <a href="{{ route('wa-placeholders.edit', $placeholder->id) }}" class="btn btn-xs btn-warning">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus placeholder ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada placeholder yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
<script>
    $(function () {
        $('#placeholder-table').DataTable({
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