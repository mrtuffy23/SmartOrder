@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Dashboard Analytics</h1>
        <p class="text-muted">Ringkasan Sistem Informasi PT Indotex Lasindo Jaya</p>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <h5 class="mb-2">Data Master & Transaksi Masuk</h5>
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $total_buyer }}</h3>
                        <p>Data Buyers</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <a href="{{ route('buyers.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $total_kain }}</h3>
                        <p>Jenis Kain</p>
                    </div>
                    <div class="icon"><i class="fas fa-scroll"></i></div>
                    <a href="{{ route('fabrics.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $total_color }}</h3>
                        <p>Pilihan color</p>
                    </div>
                    <div class="icon"><i class="fas fa-palette"></i></div>
                    <a href="{{ route('colors.index') }}" class="small-box-footer" style="color:#fff !important;">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $total_order }}</h3>
                        <p>Total Order</p>
                    </div>
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    <a href="{{ route('orders.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <h5 class="mb-2 mt-4">Pantauan Produksi & Gudang</h5>
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ number_format($saldo_grey, 0) }}<sup style="font-size: 20px"> Mtr</sup></h3>
                        <p>Stok Gudang Grey</p>
                    </div>
                    <div class="icon"><i class="fas fa-boxes"></i></div>
                    <a href="{{ route('stocks.index') }}" class="small-box-footer">Cek Gudang Grey <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $total_wip }}<sup style="font-size: 20px"> Partai</sup></h3>
                        <p>Kain WIP (Sedang Diproses)</p>
                    </div>
                    <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                    <a href="{{ route('wip.index') }}" class="small-box-footer">Pantau Produksi <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-dark">
                    <div class="inner">
                        <h3>{{ number_format($saldo_barang_jadi, 0) }}<sup style="font-size: 20px"> Mtr</sup></h3>
                        <p>Gudang Barang Jadi</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-double"></i></div>
                    <a href="{{ route('quality_finishes.index') }}" class="small-box-footer">Lihat Barang Siap Kirim <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h3>{{ $delivery_bulan_ini }}<sup style="font-size: 20px"> Truk</sup></h3>
                        <p>Pengiriman Bulan Ini</p>
                    </div>
                    <div class="icon"><i class="fas fa-truck-loading"></i></div>
                    <a href="{{ route('deliveries.index') }}" class="small-box-footer">Riwayat Surat Jalan <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i>
                            Grafik Hasil Produksi (Barang Jadi) 6 Bulan Terakhir
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="produksiChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var labels = {!! json_encode($bulan_labels) !!};
        var dataProduksi = {!! json_encode($data_produksi) !!};

        var ctx = document.getElementById('produksiChart').getContext('2d');
        var produksiChart = new Chart(ctx, {
            type: 'bar', 
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Kain Jadi (Meter)',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    data: dataProduksi
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Total Meter' }
                    }
                }
            }
        });
    });
</script>
@endsection