@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Master Data Mesin (Machines)</h1>
            </div>
            <div class="col-sm-6 text-right">
                <button class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah Mesin
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
                            <th width="30%">Nama Mesin</th>
                            <th width="20%">Kode Mesin</th>
                            <th width="15%">Volume (Liter)</th>
                            <th width="15%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($machines as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-left font-weight-bold">{{ $item->name }}</td>
                            <td>{{ $item->machine_code ?? '-' }}</td>
                            <td class="text-primary font-weight-bold">{{ number_format($item->volume) }} L</td>
                            <td>
                                @if($item->is_active) <span class="badge badge-success">Aktif</span>
                                @else <span class="badge badge-danger">Non-Aktif</span> @endif
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <form action="{{ route('machines.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus mesin ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade text-left" id="modalEdit{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('machines.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title font-weight-bold">Edit Mesin</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama Mesin</label>
                                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Kode Mesin</label>
                                                <input type="text" name="machine_code" class="form-control" value="{{ $item->machine_code }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Volume Air (Liter)</label>
                                                <input type="number" name="volume" class="form-control" value="{{ $item->volume }}" required>
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
                            <td colspan="6" class="text-center text-muted">Belum ada data mesin.</td>
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
            <form action="{{ route('machines.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">Tambah Mesin Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Mesin</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: KUNNAN 6000" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Mesin</label>
                        <input type="text" name="machine_code" class="form-control" placeholder="Contoh: 2 KUNNAN">
                    </div>
                    <div class="form-group">
                        <label>Volume Air (Liter)</label>
                        <input type="number" name="volume" class="form-control" placeholder="Contoh: 6000" required>
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