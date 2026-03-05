@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Stok WIP Produksi (Berjalan)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title mt-1"><i class="fas fa-spinner fa-spin mr-1"></i> Data Kain Belum Terkirim</h3>
                <div class="card-tools">
                    <form action="{{ route('wip.index') }}" method="GET" class="form-inline m-0">
                        <div class="input-group input-group-sm mr-2" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right"
                                   placeholder="Cari Order / Corak / Batch..."
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Reset Filter --}}
                        @if(request('search'))
                            <a href="{{ route('wip.index') }}" class="btn btn-danger btn-sm mr-1" title="Reset Filter">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        @endif

                        {{-- Tombol Export Excel --}}
                        <button type="submit" name="export" value="excel" class="btn btn-success btn-sm font-weight-bold" title="Download Excel">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped table-hover text-center table-sm">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="20%">NO. ORDER</th>
                            <th width="25%">CORAK</th>
                            <th width="15%">BATCH</th>
                            <th width="20%">METER</th>
                            <th width="20%">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stok_wip as $item)
                        <tr>
                            <td class="align-middle text-info font-weight-bold">{{ $item->no_order ?? '-' }}</td>
                            <td class="align-middle text-center font-weight-bold">{{ $item->fabric->corak ?? '-' }}</td>
                            
                            <td class="align-middle font-weight-bold text-danger" style="font-size: 15px;">
                                {{ $item->no_batch }}
                            </td>
                            
                            <td class="align-middle font-weight-bold text-success" style="font-size: 16px;">
                                {{ number_format($item->sisa_meter_aktual) }}
                            </td>
                            
                            <td class="align-middle text-danger" style="font-style: italic;">
                                {{ $item->keterangan ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fa-3x mb-3 d-block text-success"></i>
                                Area mesin kosong. Tidak ada stok WIP saat ini (Semua sudah dikirim).
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