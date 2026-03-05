@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Laporan Detail Penerimaan</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('receipts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Data Induk
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title mt-2"><i class="fas fa-list-alt mr-1"></i> Rincian Barang Masuk (Gudang Greige)</h3>
                
                <div class="card-tools">
                    <form action="{{ route('receipts.details') }}" method="GET" class="form-inline m-0">
                        <div class="input-group input-group-sm mr-2" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Cari No. Bukti / Corak / Order..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        
                        <button type="submit" name="export" value="excel" class="btn btn-success btn-sm font-weight-bold" title="Download Excel">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                        
                        @if(request('search'))
                            <a href="{{ route('receipts.details') }}" class="btn btn-danger btn-sm ml-1" title="Reset Pencarian"><i class="fas fa-sync-alt"></i></a>
                        @endif
                    </form>
                </div>
                </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped table-hover text-center table-sm">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">No. Bukti</th>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Terima </th>
                            <th width="15%">Corak Kain</th>
                            <th width="10%">Meter</th>
                            <th width="15%">No. Order</th>
                            <th width="16%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle font-weight-bold text-danger">{{ $item->receipt->no_bukti ?? '-' }}</td>
                            <td class="align-middle">{{ $item->receipt ? date('d M Y', strtotime($item->receipt->tanggal)) : '-' }}</td>
                            <td class="align-middle">{{ $item->receipt->terima ?? '-' }}</td>
                            <td class="align-middle font-weight-bold text-primary">{{ $item->fabric->corak ?? '-' }}</td>
                            <td class="align-middle font-weight-bold">{{ number_format($item->total_meter) }}</td>
                            <td class="align-middle">{{ $item->no_order ?? '-' }}</td>
                            <td class="align-middle text-muted">{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-3x mb-3 d-block text-secondary"></i>
                                Belum ada detail penerimaan barang.
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