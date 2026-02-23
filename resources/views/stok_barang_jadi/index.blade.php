@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Stok Gudang Barang Jadi</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-dark card-outline">
            <div class="card-header">
                <h3 class="card-title mt-1"><i class="fas fa-cubes mr-1"></i> Data Kain Siap Kirim (Tersedia)</h3>
                <div class="card-tools">
                    <form action="{{ route('stok_barang_jadi.index') }}" method="GET" class="m-0">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" 
                                   placeholder="Cari Order / Corak..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped table-hover text-center table-sm">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th style="width: 40px">No</th>
                            <th>No. Order</th>
                            <th>Corak Kain</th>
                            <th>Tgl Selesai (Finish)</th>
                            <th>Grade</th>
                            <th>Total Awal</th>
                            <th class="text-warning">Sudah Dikirim</th>
                            <th class="bg-success">SISA STOK (Meter)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stok_barang as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-weight-bold text-info">{{ $item->pemartaianDetail->no_order ?? '-' }}</td>
                            <td class="text-left font-weight-bold">{{ $item->pemartaianDetail->fabric->corak ?? '-' }}</td>
                            <td>{{ date('d M Y', strtotime($item->tanggal_finish)) }}</td>
                            <td>
                                <span class="badge badge-{{ $item->grade == 'A' ? 'success' : ($item->grade == 'B' ? 'warning' : 'danger') }}">
                                    Grade {{ $item->grade }}
                                </span>
                            </td>
                            
                            <td class="text-secondary">{{ number_format($item->hasil_meter, 2) }}</td>
                            <td class="text-danger">{{ number_format($item->total_terkirim, 2) }}</td>
                            
                            <td class="font-weight-bold text-success" style="font-size: 16px;">
                                {{ number_format($item->sisa_meter_aktual, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                Tidak ada stok barang jadi saat ini. (Semua sudah terkirim / Belum ada produksi).
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection