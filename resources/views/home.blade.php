@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalBerkas }}</h3>
                        <p>Total Berkas Terdaftar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <a href="{{ route('berkas.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalKlien }}</h3>
                        <p>Total Klien (Kode)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('klien.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Berkas Masuk (Tahun {{ date('Y') }})</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="berkasChart" style="min-height: 250px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                    </div>
                </div>
        </div>

    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(function () {
            // --- Logika untuk Chart.js ---

            // Ambil data dari controller yang sudah di-pass ke Blade
            var chartLabels = @json($bulanLabels);
            var chartData = @json($chartData);
            var tahunIni = new Date().getFullYear();

            var ctx = document.getElementById('berkasChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar', // Tipe grafik: 'bar', 'line', 'pie', dll.
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Berkas Masuk ' + tahunIni,
                        data: chartData,
                        backgroundColor: 'rgba(0, 123, 255, 0.7)', // Biru
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { // Opsi untuk sumbu Y (Chart.js v3+)
                            beginAtZero: true,
                            ticks: {
                                // Memastikan angka di sumbu Y adalah bilangan bulat
                                precision: 0 
                            }
                        }
                    }
                }
            });
        });
    </script>
@stop