@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard Statistik</h1>
@stop

@section('content')
    {{-- ================================================= --}}
    {{-- BAGIAN 1: TOTAL BERKAS TAHUNAN (SEMUA BULAN)      --}}
    {{-- ================================================= --}}
    <h5 class="mb-2">Total Berkas Tahun {{ $year }} (Keseluruhan)</h5>
    <div class="row">
        @php
            // Pilihan warna background acak untuk info-box
            $bgColors = ['bg-info', 'bg-success', 'bg-warning', 'bg-danger', 'bg-primary', 'bg-secondary', 'bg-indigo', 'bg-navy'];
        @endphp

        @foreach($totalPerKlien as $index => $klien)
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon {{ $bgColors[$index % count($bgColors)] }} elevation-1">
                        <i class="fas fa-folder-open"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{ $klien->kode_klien }}</span>
                        <span class="info-box-text small text-muted">{{ $klien->nama_klien }}</span>
                        <span class="info-box-number">
                            {{ $klien->berkas_count }} <small>Berkas</small>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ================================================= --}}
    {{-- BAGIAN 2: STATISTIK DETAIL PER BULAN              --}}
    {{-- ================================================= --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Statistik Bulanan: <b>{{ $bulanList[$selectedMonth] }} {{ $year }}</b>
                    </h3>

                    {{-- FORM FILTER BULAN (Dropdown) --}}
                    <div class="card-tools">
                        <form action="{{ route('home') }}" method="GET" id="form-filter-bulan">
                            <div class="input-group input-group-sm" style="width: 200px;">
                                <select name="bulan" class="form-control float-right" onchange="document.getElementById('form-filter-bulan').submit()">
                                    @foreach($bulanList as $key => $namaBulan)
                                        <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                            {{ $namaBulan }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Kode Klien</th>
                                <th>Nama Klien</th>
                                <th class="text-center" style="width: 200px">Jumlah Berkas</th>
                                <th style="width: 40px">%</th> {{-- Persentase sederhana --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalBulanIni = $statsBulanan->sum('jumlah_berkas'); @endphp
                            
                            @forelse($statsBulanan as $stat)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge badge-info">{{ $stat->kode_klien }}</span></td>
                                    <td>{{ $stat->nama_klien }}</td>
                                    <td class="text-center">
                                        <h5 class="text-primary font-weight-bold mb-0">
                                            {{ $stat->jumlah_berkas }}
                                        </h5>
                                    </td>
                                    <td>
                                        @php
                                            $persen = $totalBulanIni > 0 ? ($stat->jumlah_berkas / $totalBulanIni) * 100 : 0;
                                            $warnaBar = $persen > 50 ? 'bg-success' : ($persen > 20 ? 'bg-warning' : 'bg-danger');
                                        @endphp
                                        <div class="progress progress-xs">
                                            <div class="progress-bar {{ $warnaBar }}" style="width: {{ $persen }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data klien.</td>
                                </tr>
                            @endforelse
                            
                            {{-- Baris Total --}}
                            <tr class="bg-light font-weight-bold">
                                <td colspan="3" class="text-right">TOTAL KESELURUHAN BULAN INI:</td>
                                <td class="text-center" style="font-size: 1.2em;">{{ $totalBulanIni }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop