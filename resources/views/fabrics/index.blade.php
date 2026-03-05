@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Data Kain (Fabrics)</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('fabrics.export') }}" class="btn btn-success font-weight-bold">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </a>
                <button type="button" class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#modal-tambah">
                    <i class="fas fa-plus"></i> Tambah Kain
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-dark card-outline">
            <div class="card-header">
                <h3 class="card-title mt-1"><i class="fas fa-scroll mr-1"></i> Daftar Kain</h3>
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
                <table class="table table-bordered table-hover text-center align-middle text-sm" style="border: 1px solid #dee2e6;">
                    <thead class="bg-light">
                        <tr>
                            <th class="align-middle py-3">Corak</th>
                            <th class="align-middle py-3">Kode Kain</th>
                            <th class="align-middle py-3">Quality</th>
                            <th class="align-middle py-3">Kode Buyer</th>
                            <th class="align-middle py-3">Brand</th>
                            <th class="align-middle py-3">Konstruksi</th>
                            <th class="align-middle py-3">Density</th>
                            <th class="align-middle py-3">Status</th>
                            <th class="align-middle py-3" width="8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody> 
                        @forelse($fabrics as $kain)
                        <tr>
                            <td class="align-middle">{{ $kain->corak }}</td>
                            <td class="align-middle">{{ $kain->code_kain }}</td>
                            <td class="align-middle">{{ $kain->quality }}</td>
                            <td class="align-middle">{{ $kain->buyer_code }}</td>
                            <td class="align-middle">{{ $kain->brand }}</td>
                            <td class="align-middle">{{ $kain->construction }}</td>
                            <td class="align-middle">{{ $kain->density }}</td>
                            <td class="align-middle">
                                @if($kain->is_active == 1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('fabrics.edit', $kain->id) }}" class="text-warning font-weight-bold" style="text-decoration: none; font-size: 14px;">
                                    Edit
                                </a>
                                <br>
                                <form action="{{ route('fabrics.destroy', $kain->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-danger font-weight-bold" style="background: transparent; border: none; padding: 0; font-size: 14px;" onclick="return confirm('Yakin ingin menghapus data kain ini?')" title="Hapus">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block text-secondary"></i>
                                Data kain belum ada atau tidak ditemukan.
                            </td>
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
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title"><i class="fas fa-plus-circle mr-1"></i> Input Data Kain Baru</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
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
                                <label>Kode Buyer</label>
                                <input type="text" name="buyer_code" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Konstruksi (Construction)</label>
                                <input type="text" name="construction" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Density</label>
                                <input type="text" name="density" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Status Kain</label>
                                <select name="is_active" class="form-control" required>
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default font-weight-bold" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection