@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Delivery </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('deliveries.create') }}" class="btn btn-primary font-weight-bold">
                    <i class="fas fa-plus"></i> Tambah Pengiriman
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-lock mr-1"></i> {{ session('error') }}
        </div>
        @endif

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title mt-1"><i class="fas fa-truck-loading mr-1"></i> Daftar Terima </h3>

                <div class="card-tools">
                    <form action="{{ route('deliveries.index') }}" method="GET" class="form-inline m-0">
                        {{-- Filter Bulan --}}
                        <div class="input-group input-group-sm mr-2">
                            <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                        </div>

                        {{-- Search --}}
                        <div class="input-group input-group-sm mr-2" style="width: 220px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari Buyer / No. Order..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>

                        {{-- Reset Filter --}}
                        @if(request('search') || request('bulan'))
                            <a href="{{ route('deliveries.index') }}" class="btn btn-danger btn-sm mr-1" title="Reset Filter">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        @endif

                        {{-- 👇 TOMBOL EXPORT EXCEL BARU 👇 --}}
                        <button type="submit" name="export" value="excel" class="btn btn-success btn-sm font-weight-bold" title="Download Excel">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-hover text-sm">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="3%">No</th>
                            <th width="8%">Tanggal</th>
                            <th width="10%">Buyer</th>
                            <th width="8%">No.Order</th>
                            <th width="12%">Corak</th>
                            <th width="8%">Warna</th>
                            <th width="7%">Batch</th>
                            <th width="6%">Rol</th>
                            <th width="8%">Meter</th>
                            <th width="7%">Roda</th>
                            <th width="13%">Keterangan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $item)
                            @php
                                $detailCount = $item->details->count();
                                $rowBg = ($loop->iteration % 2 == 0) ? '#f2f2f2' : '#ffffff';
                                $is_locked = in_array(date('Y-m', strtotime($item->tanggal)), $closed_months);
                            @endphp
                            @forelse($item->details as $detailIndex => $detail)
                                @php $batch = $detail->pemartaianDetail; @endphp
                                <tr style="background-color: {{ $rowBg }};">
                                    {{-- Kolom yang di-rowspan hanya muncul di baris pertama --}}
                                    @if($detailIndex === 0)
                                    <td class="text-center align-middle" rowspan="{{ $detailCount }}" style="background-color: {{ $rowBg }};">
                                        {{ $loop->parent->iteration }}
                                    </td>
                                    <td class="text-center align-middle font-weight-bold text-danger" rowspan="{{ $detailCount }}" style="background-color: {{ $rowBg }};">
                                        {{ date('d/m/Y', strtotime($item->tanggal)) }}
                                        @if($is_locked)
                                            <br><span class="badge badge-secondary mt-1" title="Bulan ini sudah ditutup"><i class="fas fa-lock"></i> Closed</span>
                                        @endif
                                    </td>
                                    @endif

                                    {{-- Buyer per baris --}}
                                    <td class="text-center align-middle font-weight-bold" style="font-size: 14px;">
                                        {{ $detail->buyer->name ?? '-' }}
                                    </td>

                                    {{-- Kolom rincian (1 baris per detail) --}}
                                    <td class="text-center align-middle">
                                        @if($detail->no_order)
                                            <span class="text-bold">{{ $detail->no_order }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="align-middle font-weight-bold text-primary">
                                        {{ $batch->fabric->corak ?? '-' }}
                                    </td>
                                    <td class="text-center align-middle text-muted">
                                        {{ $batch->warna ?? '-' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class=" text-dark">{{ $batch->no_batch ?? '-' }}</span>
                                    </td>
                                    <td class="text-center align-middle font-weight-bold">
                                        {{ $batch->jml_gulung ?? 0 }} Rol
                                    </td>
                                    <td class="text-center align-middle text-success font-weight-bold">
                                        {{ number_format($batch->total_meter ?? 0) }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-dark">{{ $detail->no_roda }}</span>
                                    </td>
                                    <td class="align-middle text-danger" style="font-style: italic; font-size: 12px;">
                                        {{ $detail->keterangan ?? '-' }}
                                    </td>

                                    @if($detailIndex === 0)
                                    <td class="text-center align-middle" rowspan="{{ $detailCount }}" style="background-color: {{ $rowBg }};">
                                        @if($is_locked)
                                            <button class="btn btn-secondary btn-sm" disabled title="Bulan ini sudah ditutup (Closed)">
                                                <i class="fas fa-lock"></i> Terkunci
                                            </button>
                                        @else
                                            <form action="{{ route('deliveries.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data pengiriman ini? Stok WIP akan kembali bertambah.')" title="Hapus Data">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr style="background-color: {{ $rowBg }};">
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="text-center align-middle font-weight-bold text-danger">
                                        {{ date('d/m/Y', strtotime($item->tanggal)) }}
                                        @if($is_locked)
                                            <br><span class="badge badge-secondary mt-1"><i class="fas fa-lock"></i> Closed</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle font-weight-bold">
                                        -
                                    </td>
                                    <td colspan="8" class="text-center text-muted">Tidak ada rincian</td>
                                    <td class="text-center align-middle">
                                        @if($is_locked)
                                            <button class="btn btn-secondary btn-sm" disabled title="Bulan ini sudah ditutup (Closed)">
                                                <i class="fas fa-lock"></i> Terkunci
                                            </button>
                                        @else
                                            <form action="{{ route('deliveries.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data pengiriman ini?')" title="Hapus Data">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-5">
                                <i class="fas fa-truck fa-3x mb-3 d-block text-secondary"></i>
                                Belum ada riwayat pengiriman barang.
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
