@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Data Kain (Fabrics)</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('fabrics.export') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </a>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                    <i class="fas fa-plus"></i> Tambah Kain
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            
            <div class="card-header">
                <h3 class="card-title mt-1">Daftar Kain</h3>
                <div class="card-tools">
                    <form action="{{ route('fabrics.index') }}" method="GET" class="m-0">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" 
                                   placeholder="Cari Corak, Kode Kain..." 
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
                            <th>Corak</th>
                            <th>Kode Kain</th>
                            <th>Quality</th>
                            <th>Kode Buyer</th>
                            <th>Brand</th>
                            <th>Konstruksi</th>
                            <th>Density</th>
                            <th style="width: 100px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fabrics as $kain)
                        <tr>
                            <td>{{ $kain->corak }}</td>
                            <td>{{ $kain->code_kain }}</td>
                            <td>{{ $kain->quality }}</td>
                            <td>{{ $kain->buyer_code }}</td>
                            <td>{{ $kain->brand }}</td>
                            <td>{{ $kain->construction }}</td>
                            <td>{{ $kain->density }}</td>
                            
                            <td class="text-center">
                                <a href="{{ route('fabrics.edit', $kain->id) }}" class="btn btn-warning btn-sm mr-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('fabrics.destroy', $kain->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data kain ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Data kain belum ada atau tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($fabrics, 'links'))
            <div class="card-footer clearfix">
                {{ $fabrics->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</section>

<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Data Kain Baru</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('fabrics.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Corak (Wajib)</label>
                                <input type="text" name="corak" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Kode Kain</label>
                                <input type="text" name="code_kain" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Quality</label>
                                <input type="text" name="quality" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" name="brand" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Buyer Code</label>
                                <input type="text" name="buyer_code" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Construction</label>
                                <input type="text" name="construction" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Density</label>
                                <input type="text" name="density" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection