@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Laporan Stok Greig (Bulanan)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-dark card-outline">
            <div class="card-header">
                <h3 class="card-title mt-2"><i class="fas fa-book mr-1"></i> Buku Besar Stok Gudang</h3>
                
                <div class="card-tools">
                    <form action="{{ route('laporan.stok_greige') }}" method="GET" class="form-inline m-0">
                        <div class="form-group mr-2">
                            <label class="mr-2">Periode Bulan:</label>
                            <input type="month" name="bulan" class="form-control form-control-sm font-weight-bold" value="{{ $bulan_pilih }}" onchange="this.form.submit()">
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm mr-1">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        
                        <button type="submit" name="export" value="excel" class="btn btn-success btn-sm font-weight-bold" title="Download Excel">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                        
                    </form>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped table-hover text-center table-sm">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th rowspan="2" class="align-middle" width="5%">No</th>
                            <th rowspan="2" class="align-middle" width="25%">Corak Kain</th>
                            <th rowspan="2" class="align-middle" width="14%">Saldo Awal</th>
                            <th rowspan="2" class="align-middle" width="14%">Terima (Masuk)</th>
                            <th colspan="2" class="border-bottom-0 ">Keluar</th>
                            <th rowspan="2" class="align-middle" width="14%">Saldo Akhir</th>
                        </tr>
                        <tr>
                            <th class="text-light" width="14%">Pemartaian</th>
                            <th class="text-light" width="14%">Retur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $total_awal = 0; $total_terima = 0; 
                            $total_pemartaian = 0; $total_retur = 0; $total_akhir = 0; 
                        @endphp

                        @forelse($laporan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-center font-weight-bold">{{ $item->corak }}</td>
                            
                            <td class="bg-light font-weight-bold">{{ number_format($item->saldo_awal) }}</td>
                            <td class="text-success font-weight-bold">{{ number_format($item->terima) }}</td>
                            <td class="text-danger">{{ number_format($item->pemartaian) }}</td>
                            <td class="text-danger">{{ number_format($item->retur) }}</td>
                            <td class="bg-light font-weight-bold text-primary" style="font-size: 16px;">
                                {{ number_format($item->saldo_akhir) }}
                            </td>
                        </tr>

                        @php
                            $total_awal += $item->saldo_awal;
                            $total_terima += $item->terima;
                            $total_pemartaian += $item->pemartaian;
                            $total_retur += $item->retur;
                            $total_akhir += $item->saldo_akhir;
                        @endphp

                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada pergerakan stok pada bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    
                    @if(count($laporan) > 0)
                    <tfoot class=" text-dark font-weight-bold">
                        <tr>
                            <td colspan="2" class="text-right">TOTAL KESELURUHAN:</td>
                            <td>{{ number_format($total_awal) }}</td>
                            <td class="text-success"> {{ number_format($total_terima) }}</td>
                            <td class="text-danger">{{ number_format($total_pemartaian) }}</td>
                            <td class="text-danger">{{ number_format($total_retur) }}</td>
                            <td class="text-primary" style="font-size: 18px;">{{ number_format($total_akhir) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</section>
@endsection