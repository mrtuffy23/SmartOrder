@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Data Saldo / Stok Kain</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title mt-1">Laporan Stok Kain (Berjalan)</h3>
                <div class="card-tools">
                    <form action="{{ route('stocks.index') }}" method="GET" class="m-0">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" 
                                   placeholder="Cari Corak / Kode Kain..." 
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
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px">No</th>
                            <th>Kode Kain</th>
                            <th>Corak</th>
                            <th class="text-success">Total Masuk (Mtr)</th>
                            <th class="text-danger">Total Keluar (Mtr)</th>
                            <th class="bg-info">Saldo Akhir (Mtr)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                        
                        @php
                            $masuk = $stock->total_masuk ?? 0;
                            $keluar = $stock->total_keluar ?? 0;
                            $saldo_akhir = $masuk - $keluar;
                        @endphp

                        <tr>
                            <td>{{ method_exists($stocks, 'firstItem') ? $stocks->firstItem() + $loop->index : $loop->iteration }}</td>
                            <td><span class="badge badge-secondary">{{ $stock->code_kain }}</span></td>
                            <td class="text-left font-weight-bold">{{ $stock->corak }}</td>
                            
                            <td class="text-success font-weight-bold">
                                {{ number_format($masuk, 2) }}
                            </td>
                            
                            <td class="text-danger font-weight-bold">
                                {{ number_format($keluar, 2) }}
                            </td>
                            
                            <td class="font-weight-bold" style="background-color: #e8f4f8;">
                                {{ number_format($saldo_akhir, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data kain.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($stocks, 'links'))
            <div class="card-footer clearfix">
                {{ $stocks->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection