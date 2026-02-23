@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Pengiriman (Surat Jalan)</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('deliveries.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Surat Jalan Baru
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

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title mt-1">Riwayat Pengiriman Barang Jadi</h3>
                <div class="card-tools">
                    <form action="{{ route('deliveries.index') }}" method="GET" class="m-0">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" 
                                   placeholder="Cari No SJ / Nama Buyer..." 
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
                <table class="table table-bordered table-striped table-hover">
                    <thead class="bg-light text-center">
                        <tr>
                            <th style="width: 40px">No</th>
                            <th style="width: 120px">No. SJ</th>
                            <th style="width: 100px">Tanggal</th>
                            <th>Tujuan (Buyer)</th>
                            <th>Kendaraan & Supir</th>
                            <th width="35%">Rincian Muatan Kain</th>
                            <th style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $item)
                        <tr>
                            <td class="text-center">{{ method_exists($deliveries, 'firstItem') ? $deliveries->firstItem() + $loop->index : $loop->iteration }}</td>
                            <td class="text-center"><span class="badge badge-dark" style="font-size: 14px;">{{ $item->no_surat_jalan }}</span></td>
                            <td class="text-center">{{ date('d M Y', strtotime($item->tanggal_kirim)) }}</td>
                            
                            <td class="font-weight-bold text-primary">
                                {{ $item->buyer->name ?? 'Buyer Dihapus' }}
                            </td>
                            
                            <td>
                                <i class="fas fa-truck text-secondary"></i> {{ $item->no_kendaraan ?? '-' }} <br>
                                <i class="fas fa-user text-secondary"></i> {{ $item->nama_supir ?? '-' }}
                            </td>
                            
                            <td>
                                <ul class="mb-0 pl-3">
                                    @foreach($item->details as $detail)
                                        @php
                                            // Mengambil relasi panjang dengan aman
                                            $no_order = $detail->qualityFinish->pemartaianDetail->no_order ?? '-';
                                            $corak = $detail->qualityFinish->pemartaianDetail->fabric->corak ?? 'Kain Dihapus';
                                        @endphp
                                        <li>
                                            <span class="badge badge-info">{{ $no_order }}</span>
                                            <strong>{{ $corak }}</strong> 
                                            <span class="text-success font-weight-bold">
                                                ( {{ $detail->jml_roll }} Roll / {{ number_format($detail->total_meter, 2) }} Mtr )
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            
                            <td class="text-center">
                                <a href="{{ route('deliveries.print', $item->id) }}" class="btn btn-secondary btn-xs mb-1 w-100" title="Cetak Surat Jalan">
                                    <i class="fas fa-print"></i> Cetak SJ
                                </a>

                                <a href="{{ route('deliveries.edit', $item->id) }}" class="btn btn-warning btn-xs mr-1" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('deliveries.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menghapus Surat Jalan {{ $item->no_surat_jalan }}?')" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-truck-loading fa-3x mb-3 d-block text-secondary"></i>
                                Belum ada riwayat pengiriman / surat jalan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($deliveries, 'links'))
            <div class="card-footer clearfix">
                {{ $deliveries->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection