@extends('adminlte::page')
@section('title', 'Riwayat Aktivitas')
@section('content_header') <h1>Riwayat Aktivitas Berkas</h1> @stop

@section('content')
<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>No. Berkas</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayats as $log)
                <tr>
                    <td><small>{{ $log->created_at->format('d-m-Y H:i') }}</small></td>
                    <td class="font-weight-bold">{{ $log->user->name ?? 'Sistem' }}</td>
                    <td>
                        <span class="badge {{ $log->aksi == 'MEMBUAT' ? 'badge-success' : ($log->aksi == 'MENGUBAH' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $log->aksi }}
                        </span>
                    </td>
                    <td>
                        @if($log->berkas)
                            <a href="{{ route('berkas.edit', $log->berkas_id) }}">{{ $log->berkas->nomer_berkas }}</a>
                        @else
                            <span class="text-muted">Berkas Terhapus</span>
                        @endif
                    </td>
                    <td>{{ $log->keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $riwayats->links() }}
    </div>
</div>
@stop