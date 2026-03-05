@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Data Retur Gudang</h1></div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('returs.create') }}" class="btn btn-warning font-weight-bold">
                    <i class="fas fa-plus"></i> Tambah Retur
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card card-warning card-outline">
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">No. Retur</th>
                            <th width="15%">Tanggal</th>
                            <th width="20%">Corak Kain</th>
                            <th width="15%">Meter</th>
                            <th width="20%">Keterangan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returs as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge badge-danger">{{ $item->no_retur }}</span></td>
                            <td>{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                            <td class="text-left font-weight-bold">{{ $item->fabric->corak ?? '-' }}</td>
                            <td class="text-danger font-weight-bold">- {{ number_format($item->total_meter, 2) }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                <form action="{{ route('returs.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-xs" onclick="return confirm('Yakin hapus data retur ini?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-muted py-4">Belum ada data retur barang.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection