@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Laporan Stok WIP (Bulanan)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-dark card-outline">
            <div class="card-header">
                <h3 class="card-title mt-2"><i class="fas fa-book-open mr-1"></i> Buku Besar Mesin (WIP)</h3>
                
                <div class="card-tools">
                    <form action="{{ route('wip.laporan') }}" method="GET" class="form-inline m-0">
                        <div class="form-group mr-2">
                            <label class="mr-2">Periode Bulan:</label>
                            <input type="month" name="bulan" class="form-control form-control-sm font-weight-bold" value="{{ $bulan_pilih }}" onchange="this.form.submit()">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mr-1"><i class="fas fa-filter"></i> Filter</button>
                        
                        <button type="submit" name="export" value="excel" class="btn btn-success btn-sm font-weight-bold" title="Download Excel">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-hover text-center table-sm">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="align-middle" width="5%">No</th>
                            <th class="align-middle" width="25%">Corak Kain</th>
                            <th class="align-middle" width="17%">Saldo Awal</th>
                            <th class="align-middle" width="17%">Terima (Dari Greige)</th>
                            <th class="align-middle" width="17%">Keluar</th>
                            <th class="align-middle" width="19%">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $total_awal = 0; $total_terima = 0; 
                            $total_keluar = 0; $total_akhir = 0; 
                        @endphp

                        @forelse($laporan_wip as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-center font-weight-bold">{{ $item->corak }}</td>
                            
                            <td class="font-weight-bold">{{ number_format($item->saldo_awal) }}</td>
                            <td class="text-success font-weight-bold"> {{ number_format($item->terima) }}</td>
                            <td class="text-danger font-weight-bold">{{ number_format($item->keluar) }}</td>
                            <td class="font-weight-bold text-primary" style="font-size: 16px;">
                                {{ number_format($item->saldo_akhir) }}
                            </td>
                        </tr>

                        @php
                            $total_awal += $item->saldo_awal;
                            $total_terima += $item->terima;
                            $total_keluar += $item->keluar;
                            $total_akhir += $item->saldo_akhir;
                        @endphp

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada pergerakan stok mesin pada bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    
                    @if(count($laporan_wip) > 0)
                    <tfoot class=" text-dark font-weight-bold">
                        <tr>
                            <td colspan="2" class="text-right">TOTAL KESELURUHAN:</td>
                            <td>{{ number_format($total_awal) }}</td>
                            <td class="text-success">{{ number_format($total_terima) }}</td>
                            <td class="text-danger">{{ number_format($total_keluar) }}</td>
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