@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Laporan Semua Transaksi</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Detail Pesanan</h3>
                <div class="card-tools">
    <div class="d-flex align-items-center">
        
        <a href="{{ route('reports.export') }}" class="btn btn-success btn-sm mr-2">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>

        <form action="{{ route('reports.index') }}" method="GET" class="m-0">
            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" name="search" class="form-control float-right" 
                       placeholder="Cari PO, Buyer, Warna..." 
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
            </div>
            
            <div class="card-body p-0 table-responsive">
                <table class="table table-striped table-bordered text-sm">
                    <thead>
                        <tr>
                            <th>Tgl</th>
                            <th>NO Order</th>
                            <th>NO PO</th>
                            <th>Customer</th>
                            <th>Corak</th>
                            <th>Kode Kain</th>
                            <th>Kode Quality</th>
                            <th>Kode Buyer</th>
                            <th>Brand</th>
                            <th>Konstruksi</th>
                            <th>Density</th>
                            <th>Warna</th>
                            <th>Qty OM</th>
                            <th>Jml Grey</th>
                            <th>Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $item)
                        <tr>
                            <td>{{ date('d/m/y', strtotime($item->order->order_date)) }}</td>
                            <td>{{ $item->order->mf_number ?? '-' }}</td>
                            <td>{{ $item->order->po_number }}</td>
                            <td>{{ $item->order->buyer->name ?? '-' }}</td>
                            <td>{{ $item->order->fabric->corak ?? '-' }}</td>
                            <td>{{ $item->order->fabric->code_kain ?? '-' }}</td>
                            <td>{{ $item->order->fabric->quality ?? '-' }}</td>
                            <td>{{ $item->order->buyer->kode_buyer ?? '-' }}</td>
                            <td>{{ $item->order->fabric->brand ?? '-' }}</td>
                            <td>{{ $item->order->fabric->construction ?? '-' }}</td>
                            <td>{{ $item->order->fabric->density ?? '-' }}</td>
                            <td>
                                <span class="badge badge-light border">{{ $item->color->name ?? '-' }}</span>
                            </td>
                            <td class="text-right">{{ number_format($item->qty_om) }}</td>
                            <td class="text-right">{{ number_format($item->jml_grey) }}</td>
                            <td>{{ $item->notes }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="15" class="text-center">Belum ada data transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer clearfix">
                {{ $transactions->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection