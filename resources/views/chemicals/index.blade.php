@extends('layouts.laborat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Chemicals</h1>
            </div>
            <div class="col-sm-6 text-right">
                <button class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah Obat / Kimia
                </button>
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

        <div class="card card-dark card-outline">
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped table-hover text-center">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Active Code</th>
                            <th width="40%">Nama Bahan Kimia (Auxiliaries)</th>
                            <th width="15%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chemicals as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-weight-bold text-left">{{ $item->active_code }}</td>
                            <td class="text-left">{{ $item->name }}</td>
                            <td>
                                @if($item->is_active) <span class="badge badge-success">Aktif</span>
                                @else <span class="badge badge-danger">Non-Aktif</span> @endif
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <form action="{{ route('chemicals.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade text-left" id="modalEdit{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('chemicals.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title font-weight-bold">Edit Bahan Kimia</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Active Code</label>
                                                <input type="text" name="active_code" class="form-control" value="{{ $item->active_code }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Bahan Kimia</label>
                                                <input type="text" name="name" class="form-control" value="{{ $item->name }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="is_active" class="form-control">
                                                    <option value="1" {{ $item->is_active == 1 ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ $item->is_active == 0 ? 'selected' : '' }}>Non-Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning font-weight-bold"><i class="fas fa-save"></i> Update Data</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data bahan kimia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('chemicals.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">Tambah Bahan Kimia Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Active Code</label>
                        <input type="text" name="active_code" class="form-control" placeholder="Contoh: ESKACID" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Bahan Kimia</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: ESKACID DA-BA 1">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" selected>Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection