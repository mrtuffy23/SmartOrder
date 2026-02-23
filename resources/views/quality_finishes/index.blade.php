@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Data Quality Finish (Gudang Barang Jadi)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title mt-1">Laporan Hasil Produksi Kain Siap Kirim</h3>
                <div class="card-tools">
                    <form action="{{ route('quality_finishes.index') }}" method="GET" class="m-0">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" 
                                   placeholder="Cari Order / Corak / Batch..." 
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
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 40px">No</th>
                            <th>Tgl Finish</th>
                            <th class="bg-info text-white">No. Order</th>
                            <th>Corak</th>
                            <th>No. Batch</th>
                            <th>Grade</th>
                            <th>Meter Awal</th>
                            <th class="text-success">Hasil Akhir</th>
                            <th class="bg-warning">Susut (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($finished_goods as $item)
                        
                        @php
                            $awal = $item->pemartaianDetail->total_meter ?? 0;
                            $akhir = $item->hasil_meter ?? 0;
                            $susut_meter = $awal - $akhir;
                            $susut_persen = $awal > 0 ? ($susut_meter / $awal) * 100 : 0;
                        @endphp

                        <tr>
                            <td>{{ method_exists($finished_goods, 'firstItem') ? $finished_goods->firstItem() + $loop->index : $loop->iteration }}</td>
                            <td>{{ date('d M Y', strtotime($item->tanggal_finish)) }}</td>
                            
                            <td class="font-weight-bold text-info">{{ $item->pemartaianDetail->no_order ?? '-' }}</td>
                            <td class="text-left font-weight-bold">{{ $item->pemartaianDetail->fabric->corak ?? '-' }}</td>
                            <td>{{ $item->pemartaianDetail->no_batch ?? '-' }}</td>
                            
                            <td>
                                @if($item->grade == 'A')
                                    <span class="badge badge-success">Grade A</span>
                                @elseif($item->grade == 'B')
                                    <span class="badge badge-warning">Grade B</span>
                                @else
                                    <span class="badge badge-danger">Grade C (BS)</span>
                                @endif
                            </td>

                            <td class="text-secondary">{{ number_format($awal, 2) }}</td>
                            <td class="text-success font-weight-bold">{{ number_format($akhir, 2) }}</td>
                            
                            <td class="font-weight-bold {{ $susut_persen > 5 ? 'text-danger' : 'text-dark' }}">
                                @if($susut_persen > 0)
                                    <i class="fas fa-arrow-down text-danger text-sm"></i> {{ number_format($susut_persen, 2) }}%
                                @elseif($susut_persen < 0)
                                    <i class="fas fa-arrow-up text-success text-sm"></i> +{{ number_format(abs($susut_persen), 2) }}% (Melar)
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-check-double fa-3x mb-3 d-block text-success"></i>
                                Belum ada data kain jadi (Quality Finish).
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($finished_goods, 'links'))
            <div class="card-footer clearfix">
                {{ $finished_goods->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection