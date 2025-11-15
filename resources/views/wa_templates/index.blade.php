@extends('adminlte::page')

@section('title', 'Manajemen Template WA')

@section('content_header')
    <h1>Manajemen Template WA</h1>
@stop

@section('content')
    {{-- Notification Message --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $message }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('wa-templates.create') }}" class="btn btn-primary">Tambah Template Baru</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="template-table">
                <thead>
                    <tr>
                        <th style="width: 10px">ID</th>
                        <th>Nama Template</th>
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $template)
                    <tr>
                        <td>{{ $template->id }}</td>
                        <td>{{ $template->nama_template }}</td>
                        <td>
                            {{-- Action Buttons --}}
                            <form action="{{ route('wa-templates.destroy', $template->id) }}" method="POST" style="display:inline-block;">
                                <a href="{{ route('wa-templates.edit', $template->id) }}" class="btn btn-xs btn-warning">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus template ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada template yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
<script>
    // Initialize DataTables
    $(function () {
        $('#template-table').DataTable({
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