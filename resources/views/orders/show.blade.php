@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Pesanan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Kembali</a></li>
                    <li class="breadcrumb-item active">{{ $order->po_number }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <div class="invoice p-3 mb-3">
            <div class="row">
                <div class="col-12">
                    <h4>
                        <i class="fas fa-globe"></i> Smart Order - PT. Indotex
                        <small class="float-right">Tanggal: {{ date('d/m/Y', strtotime($order->order_date)) }}</small>
                    </h4>
                </div>
            </div>
            
            <div class="row invoice-info mt-4">
                <div class="col-sm-4 invoice-col">
                    <strong>Data Pesanan:</strong>
                    <address>
                        <b>NO PO:</b> {{ $order->po_number }}<br>
                        <b>NO Order:</b> {{ $order->mf_number ?? '-' }}<br>
                        <b>Dibuat:</b> {{ $order->created_at->format('d M Y') }}
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    <strong>Buyer (Pemesan):</strong>
                    <address>
                        <strong>{{ $order->buyer->name }}</strong><br>
                        Kode: {{ $order->buyer->kode_buyer ?? '-' }}<br>
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    <strong>Spesifikasi Kain:</strong>
                    <address>
                        <b>Corak:</b> {{ $order->fabric->corak }}<br>
                        <b>Kode Kain:</b> {{ $order->fabric->code_kain }}<br>
                        <b>Konstruksi:</b> {{ $order->fabric->construction }}
                    </address>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Warna</th>
                                <th>Qty OM</th>
                                <th>Batch Size</th>
                                <th>Jml Batch</th>
                                <th>Jml Grey</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $detail->color->name }}</td>
                                <td>{{ $detail->qty_om }}</td>
                                <td>{{ $detail->batch_size }}</td>
                                <td>{{ $detail->jml_batch }}</td>
                                <td>{{ $detail->jml_grey }}</td>
                                <td>{{ $detail->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row no-print mt-4">
                <div class="col-12">
                    <a href="{{ route('orders.print', $order->id) }}" target="_blank" class="btn btn-default">
                        <i class="fas fa-print"></i> Cetak Surat Jalan
                    </a>
                    
                    <a href="{{ route('orders.index') }}" class="btn btn-primary float-right">
                        <i class="fas fa-arrow-left"></i> Kembali ke List
                    </a>
                </div>
            </div>
        </div>
        </div>
</section>
@endsection