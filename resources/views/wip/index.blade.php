@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Data Kain WIP (Work In Progress)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title mt-1">Laporan Kain Sedang Diproses (Dyeing Finishing)</h3>
                <div class="card-tools">
                    <form action="{{ route('wip.index') }}" method="GET" class="m-0">
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
                            <th>Tanggal Masuk</th>
                            <th>No. Partai</th>
                            <th class="bg-info text-white">No. Order</th>
                            <th>Corak</th>
                            <th>No. Batch</th>
                            <th>Jml Gulung</th>
                            <th class="text-danger">Meter</th>
                            <th>Berat (Kg)</th>
                            <th style="width: 120px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wip_kain as $item)
                        <tr>
                            <td>{{ method_exists($wip_kain, 'firstItem') ? $wip_kain->firstItem() + $loop->index : $loop->iteration }}</td>
                            
                            <td>{{ date('d M Y', strtotime($item->pemartaian->tanggal ?? now())) }}</td>
                            <td><span class="badge badge-dark">{{ $item->pemartaian->no_partai ?? '-' }}</span></td>
                            
                            <td class="font-weight-bold text-info">{{ $item->no_order ?? '-' }}</td>
                            <td class="text-left font-weight-bold">{{ $item->fabric->corak ?? '-' }}</td>
                            <td>{{ $item->no_batch ?? '-' }}</td>
                            <td>{{ $item->jml_gulung }}</td>
                            <td class="text-danger font-weight-bold">{{ number_format($item->total_meter, 2) }}</td>
                            <td>{{ number_format($item->berat, 2) }}</td>
                            
                            <td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-finish-{{ $item->id }}" title="Selesaikan Kain">
                                        <i class="fas fa-check-circle"></i> Selesaikan
                                    </button>

                                    <div class="modal fade text-left" id="modal-finish-{{ $item->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h4 class="modal-title">Input Hasil Quality Finish</h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                </div>
                                                <form action="{{ route('quality_finishes.store') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <input type="hidden" name="pemartaian_detail_id" value="{{ $item->id }}">
                                                        
                                                        <div class="alert alert-info py-2">
                                                            <strong>Corak:</strong> {{ $item->fabric->corak ?? '-' }} <br>
                                                            <strong>Target Meter Awal:</strong> {{ number_format($item->total_meter, 2) }} Mtr
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Tanggal Selesai (Finish)</label>
                                                            <input type="date" name="tanggal_finish" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label>Hasil Akhir (Meter)</label>
                                                                    <input type="number" name="hasil_meter" class="form-control" step="0.01" value="{{ $item->total_meter }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label>Hasil Berat (Kg)</label>
                                                                    <input type="number" name="hasil_berat" class="form-control" step="0.01" value="{{ $item->berat }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Grade / Kualitas</label>
                                                            <select name="grade" class="form-control" required>
                                                                <option value="A">Grade A (Bagus / Lolos QC)</option>
                                                                <option value="B">Grade B (Ada Cacat Minor)</option>
                                                                <option value="C">Grade C (Afkir / BS)</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Keterangan Tambahan</label>
                                                            <input type="text" name="keterangan" class="form-control" placeholder="Opsional...">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan ke Barang Jadi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                Tidak ada data Kain WIP saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($wip_kain, 'links'))
            <div class="card-footer clearfix">
                {{ $wip_kain->links('pagination::bootstrap-4') }}
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection