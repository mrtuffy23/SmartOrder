@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Laporan Detail Pemartaian (Barang Keluar)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-dark card-outline">
            <div class="card-header">
                <h3 class="card-title mt-2"><i class="fas fa-list-alt mr-1"></i> Rincian Kain Masuk Mesin</h3>
                
                <div class="card-tools">
                    <form action="{{ route('pemartaians.details') }}" method="GET" class="form-inline m-0">
                        <div class="input-group input-group-sm mr-2" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari Partai / Batch / Order..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        
                        <button type="submit" name="export" value="excel" class="btn btn-success btn-sm font-weight-bold" title="Download Excel">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                        
                        @if(request('search'))
                            <a href="{{ route('pemartaians.details') }}" class="btn btn-danger btn-sm ml-1" title="Reset Pencarian"><i class="fas fa-sync-alt"></i></a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped table-hover text-center table-sm">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="5%">No</th>
                            <th>No.Partai</th>
                            <th>Tanggal</th>
                            <th>Jenis / Ket</th>
                            <th>No.Order</th>
                            <th>Corak Kain</th>
                            <th>Warna</th>
                            <th class="text-warning">Batch</th>
                            <th>Gulung</th>
                            <th>Meter</th>
                            <th>Berat(Kg)</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle font-weight-bold">
                                <span class="badge badge">{{ $item->pemartaian->no_partai ?? '-' }}</span>
                            </td>
                            <td class="align-middle">{{ $item->pemartaian ? date('d M Y', strtotime($item->pemartaian->tanggal)) : '-' }}</td>
                            <td class="align-middle text-muted" style="font-size: 12px;">
                                {{ $item->pemartaian->jenis_pengeluaran ?? '-' }}<br>
                                ({{ $item->pemartaian->keterangan ?? '-' }})
                            </td>
                            <td class="align-middle text-info font-weight-bold">{{ $item->no_order ?? '-' }}</td>
                            <td class="align-middle font-weight-bold text-left">{{ $item->fabric->corak ?? '-' }}</td>
                            <td class="align-middle">{{ $item->warna ?? '-' }}</td>
                            <td class="align-middle font-weight-bold text-danger">{{ $item->no_batch ?? '-' }}</td>
                            <td class="align-middle">{{ $item->jml_gulung }}</td>
                            <td class="align-middle font-weight-bold">{{ number_format($item->total_meter) }}</td>
                            <td class="align-middle">{{ number_format($item->berat) }}</td>
                            <td class="align-middle text-danger" style="font-style: italic;">{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-3x mb-3 d-block text-secondary"></i>
                                Belum ada detail pemartaian barang.
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