@extends('layouts.laborat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Dyestuffs</h1>
            </div>
            <div class="col-sm-6 text-right">
                <button class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah Zat Warna
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
                            <th width="20%">Active Code</th>
                            <th width="35%">Nama Zat Warna (Dyestuff)</th>
                            <th width="15%">Tipe</th>
                            <th width="10%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dyestuffs as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-weight-bold">{{ $item->active_code }}</td>
                            <td class="text-left">{{ $item->name }}</td>
                            <td>
                                @if($item->type == 'D') <span class="badge badge-info">Disperse (D)</span>
                                @elseif($item->type == 'R') <span class="badge badge-warning">Reactive (R)</span>
                                @else - @endif
                            </td>
                            <td>
                                @if($item->is_active) <span class="badge badge-success">Aktif</span>
                                @else <span class="badge badge-danger">Non-Aktif</span> @endif
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <form action="{{ route('dyestuffs.destroy', $item->id) }}" method="POST" class="d-inline">
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
                                    <form action="{{ route('dyestuffs.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title font-weight-bold">Edit Zat Warna</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Active Code</label>
                                                <input type="text" name="active_code" class="form-control" value="{{ $item->active_code }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Dyestuff</label>
                                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Tipe Warna</label>
                                                <select name="type" class="form-control">
                                                    <option value="D" {{ $item->type == 'D' ? 'selected' : '' }}>Disperse (D)</option>
                                                    <option value="R" {{ $item->type == 'R' ? 'selected' : '' }}>Reactive (R)</option>
                                                </select>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dyestuffs.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">Tambah Zat Warna Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Active Code</label>
                        <input type="text" name="active_code" class="form-control" placeholder="Contoh: BLACK CA" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Dyestuff</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: DOMACRON BLACK CA" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe Warna</label>
                        <select name="type" class="form-control">
                            <option value="D">Disperse (D)</option>
                            <option value="R">Reactive (R)</option>
                        </select>
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