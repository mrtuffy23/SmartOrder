@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Penerimaan Kain</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('receipts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Penerimaan
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mt-1">Daftar Bukti Penerimaan Masuk</h3>
                <div class="card-tools">
                    <form action="{{ route('receipts.index') }}" method="GET" class="m-0">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" 
                                   placeholder="Cari No Bukti / Gudang..." 
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
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">No</th>
                            <th>No Bukti</th>
                            <th>Tgl Terima</th>
                            <th>Terima Dari</th>
                            <th width="35%">Rincian Kain (Corak & Meter)</th>
                            <th>Keterangan</th>
                            <th style="width: 120px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receipts as $item)
                        <tr>
                            <td>{{ method_exists($receipts, 'firstItem') ? $receipts->firstItem() + $loop->index : $loop->iteration }}</td>
                            <td><span class="badge badge-primary" style="font-size: 14px;">{{ $item->no_bukti }}</span></td>
                            <td>{{ date('d M Y', strtotime($item->tgl_terima)) }}</td>
                            <td>{{ $item->terima_dari ?? '-' }}</td>
                            <td>
                                <ul class="mb-0 pl-3">
                                    @foreach($item->details as $detail)
                                        <li>
                                            <strong>{{ $detail->fabric->corak ?? 'Kain Dihapus' }}</strong> 
                                            <span class="text-muted">({{ number_format($detail->total_meter, 2) }} Meter)</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('receipts.edit', $item->id) }}" class="btn btn-warning btn-xs mr-1" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('receipts.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menghapus No Bukti {{ $item->no_bukti }} beserta rincian kainnya?')" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data penerimaan kain.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($receipts, 'links'))
            <div class="card-footer clearfix">
                {{ $receipts->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection