@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Dashboard Sistem</h1>
        <p class="text-muted">PT Indotex Lasindo Jaya</p>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
            
            <h5 class="mb-2">Data Master & Transaksi Masuk</h5>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $total_buyer ?? 0 }}</h3>
                            <p>Data Buyers</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <a href="{{ route('buyers.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $total_kain ?? 0 }}</h3>
                            <p>Jenis Kain</p>
                        </div>
                        <div class="icon"><i class="fas fa-scroll"></i></div>
                        <a href="{{ route('fabrics.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $total_color ?? 0 }}</h3>
                            <p>Pilihan Warna</p>
                        </div>
                        <div class="icon"><i class="fas fa-palette"></i></div>
                        <a href="{{ route('colors.index') }}" class="small-box-footer" style="color:#fff !important;">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $total_order ?? 0 }}</h3>
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
                            <h3>{{ number_format($saldo_grey ?? 0, 0) }}<sup style="font-size: 20px"> Mtr</sup></h3>
                            <p>Stok Gudang Grey</p>
                        </div>
                        <div class="icon"><i class="fas fa-boxes"></i></div>
                        <a href="{{ route('stocks.index') }}" class="small-box-footer">Cek Gudang Grey <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ number_format($total_wip ?? 0, 0) }}<sup style="font-size: 20px"> Mtr</sup></h3>
                            <p>Kain WIP (Sedang Diproses)</p>
                        </div>
                        <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                        <a href="{{ route('wip.index') }}" class="small-box-footer">Pantau Produksi <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <h3><i class="fas fa-book mt-2 mb-2"></i></h3>
                            <p>Laporan WIP</p>
                        </div>
                        <div class="icon"><i class="fas fa-check-double"></i></div>
                        <a href="{{ route('wip.laporan') }}" class="small-box-footer">Lihat Laporan WIP <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>{{ number_format($total_pengiriman ?? 0, 0) }}<sup style="font-size: 20px"> Mtr</sup></h3>
                            <p>Total Pengiriman Bulan Ini</p>
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
                                Grafik Hasil Produksi 6 Bulan Terakhir
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="produksiChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var labels = {!! json_encode($bulan_labels ?? []) !!};
                    var dataProduksi = {!! json_encode($data_produksi ?? []) !!};

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


        @elseif(auth()->user()->role == 'gudang')
            
            <div class="alert alert-info">
                <h5><i class="icon fas fa-boxes"></i> Halo, Tim Gudang!</h5>
                Fokus hari ini: Pastikan penerimaan kain greige dari supplier tercatat dengan benar.
            </div>

            <div class="row">
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Penerimaan</h3>
                            <p>Catat kain masuk dari Supplier</p>
                        </div>
                        <div class="icon"><i class="fas fa-truck-loading"></i></div>
                        <a href="{{ route('receipts.index') }}" class="small-box-footer">Buka Penerimaan <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>Stok Saldo</h3>
                            <p>Cek total saldo kain di gudang</p>
                        </div>
                        <div class="icon"><i class="fas fa-box-open"></i></div>
                        <a href="{{ route('stocks.index') }}" class="small-box-footer">Lihat Stok Gudang <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Retur</h3>
                            <p>Kembalikan kain cacat ke supplier</p>
                        </div>
                        <div class="icon"><i class="fas fa-undo-alt"></i></div>
                        <a href="{{ route('returs.index') }}" class="small-box-footer">Proses Retur <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>


        @elseif(auth()->user()->role == 'produksi')
            
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-industry"></i> Halo, Tim Produksi!</h5>
                Silakan proses pemartaian ke mesin dan pantau sisa kain (WIP) hari ini.
            </div>

            <div class="row">
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <h3>Pemartaian</h3>
                            <p>Masukan kain ke Batch / Mesin</p>
                        </div>
                        <div class="icon"><i class="fas fa-cogs"></i></div>
                        <a href="{{ route('pemartaians.index') }}" class="small-box-footer">Proses Kain <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>WIP Area</h3>
                            <p>Pantau sisa kain belum terkirim</p>
                        </div>
                        <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                        <a href="{{ route('wip.index') }}" class="small-box-footer">Cek Area Mesin <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>Delivery</h3>
                            <p>Serah terima kain ke Buyer</p>
                        </div>
                        <div class="icon"><i class="fas fa-truck"></i></div>
                        <a href="{{ route('deliveries.index') }}" class="small-box-footer">Buat Surat Jalan <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

        @endif

    </div>
</section>
@endsection