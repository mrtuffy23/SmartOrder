@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Pemartaian (Produksi)</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('pemartaians.create') }}" class="btn btn-primary font-weight-bold">
                    <i class="fas fa-plus"></i> Tambah Data Pemartaian
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
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
        </div>
        @endif

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title mt-1"><i class="fas fa-list mr-1"></i> Partai Keluar Mesin (WIP)</h3>

                <div class="card-tools">
                    <form action="{{ route('pemartaians.index') }}" method="GET" class="form-inline m-0">
                        {{-- Filter Bulan --}}
                        <div class="input-group input-group-sm mr-2">
                            <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                        </div>

                        {{-- Search --}}
                        <div class="input-group input-group-sm mr-2" style="width: 240px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari No. Partai / Corak / Order..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>

                        {{-- Reset --}}
                        @if(request('search') || request('bulan'))
                            <a href="{{ route('pemartaians.index') }}" class="btn btn-danger btn-sm" title="Reset Filter">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-hover text-sm">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="4%">No</th>
                            <th width="8%">No. Partai</th>
                            <th width="8%">Tanggal</th>
                            <th width="12%">Jenis & Keterangan</th>
                            <th width="8%">No. Order</th>
                            <th width="10%">Corak</th>
                            <th width="7%">Warna</th>
                            <th width="7%">Batch</th>
                            <th width="5%">Rol</th>
                            <th width="8%">Meter</th>
                            <th width="12%">Keterangan</th>
                            <th width="7%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemartaians as $item)
                            @php
                                $detailCount = $item->details->count();
                                $rowBg = ($loop->iteration % 2 == 0) ? '#f2f2f2' : '#ffffff';
                            @endphp
                            @forelse($item->details as $detailIndex => $detail)
                                <tr style="background-color: {{ $rowBg }};">
                                    @if($detailIndex === 0)
                                    <td class="text-center align-middle" rowspan="{{ $detailCount }}" style="background-color: {{ $rowBg }};">
                                        {{ $loop->parent->iteration }}
                                    </td>
                                    <td class="text-center align-middle font-weight-bold" rowspan="{{ $detailCount }}" style="background-color: {{ $rowBg }};">
                                        {{ $item->no_partai }}
                                    </td>
                                    <td class="text-center align-middle font-weight-bold" rowspan="{{ $detailCount }}" style="background-color: {{ $rowBg }};">
                                        {{ date('d/m/Y', strtotime($item->tanggal)) }}
                                    </td>
                                    <td class="text-center align-middle" rowspan="{{ $detailCount }}" style="background-color: {{ $rowBg }};">
                                        <strong>{{ $item->jenis_pengeluaran }}</strong><br>
                                        <span class="text-muted" style="font-size: 12px;">{{ $item->keterangan }}</span>
                                    </td>
                                    @endif

                                    {{-- Kolom rincian (1 baris per detail) --}}
                                    <td class="text-center align-middle" style="background-color: {{ $rowBg }};">
                                        {{ $detail->no_order ?? '-' }}
                                    </td>
                                    <td class="text-center align-middle font-weight-bold text-primary" style="background-color: {{ $rowBg }};">
                                        {{ $detail->fabric->corak ?? 'Kain Dihapus' }}
                                    </td>
                                    <td class="text-center align-middle text-muted" style="background-color: {{ $rowBg }};">
                                        {{ $detail->warna ?? '-' }}
                                    </td>
                                    <td class="text-center align-middle" style="background-color: {{ $rowBg }};">
                                        {{ $detail->no_batch }}
                                    </td>
                                    <td class="text-center align-middle font-weight-bold" style="background-color: {{ $rowBg }};">
                                        {{ $detail->jml_gulung ?? 0 }}
                                    </td>
                                    <td class="text-center align-middle text-success font-weight-bold" style="background-color: {{ $rowBg }};">
                                        {{ number_format($detail->total_meter) }}
                                    </td>
                                    <td class="align-middle text-danger" style="background-color: {{ $rowBg }}; font-style: italic; font-size: 12px;">
                                        {{ $detail->keterangan ?? '-' }}
                                    </td>

                                    @if($detailIndex === 0)
                                    <td class="text-center align-middle" rowspan="{{ $detailCount }}" style="background-color: {{ $rowBg }};">
                                        <a href="{{ route('pemartaians.edit', $item->id) }}" class="btn btn-warning btn-xs btn-block mb-1">
                                            Edit
                                        </a>
                                        <form action="{{ route('pemartaians.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs btn-block" onclick="return confirm('Yakin ingin menghapus data dengan No Partai: {{ $item->no_partai }}?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr style="background-color: {{ $rowBg }};">
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    <td class="text-center align-middle font-weight-bold">{{ $item->no_partai }}</td>
                                    <td class="text-center align-middle font-weight-bold">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                                    <td class="text-center align-middle">
                                        <strong>{{ $item->jenis_pengeluaran }}</strong><br>
                                        <span class="text-muted" style="font-size: 12px;">{{ $item->keterangan }}</span>
                                    </td>
                                    <td colspan="7" class="text-center text-muted">Tidak ada rincian</td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('pemartaians.edit', $item->id) }}" class="btn btn-warning btn-xs btn-block mb-1">
                                            Edit
                                        </a>
                                        <form action="{{ route('pemartaians.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs btn-block" onclick="return confirm('Yakin ingin menghapus data dengan No Partai: {{ $item->no_partai }}?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforelse
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block text-secondary"></i>
                                Belum ada riwayat pengeluaran pemartaian.
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