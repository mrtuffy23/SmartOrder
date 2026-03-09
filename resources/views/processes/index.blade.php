@extends('layouts.laborat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Data Proses & SOP Resep</h1></div>
            <div class="col-sm-6 text-right">
                <button class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah Proses
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button class="close" data-dismiss="alert">&times;</button>{{ session('success') }}
            </div>
        @endif

        <div class="card card-dark card-outline">
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-hover text-center">
                <thead class="bg-light">
                    <tr>
                        <th width="5%" class="align-middle">No</th>
                        <th width="25%" class="align-middle">Nama Proses</th>
                        <th width="40%" class="align-middle">Paket SOP (Obat & Konsentrasi)</th>
                        <th width="10%" class="align-middle">Status</th>
                        <th width="20%" class="align-middle">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($processes as $index => $process)
                    <tr>
                        <td class="align-middle">{{ $index + 1 }}</td>
                        <td class="align-middle font-weight-bold text-primary text-left pl-3">{{ $process->name }}</td>
                        
                        <td class="p-0 align-middle">
                            @if($process->chemicals->count() > 0)
                                <table class="table table-sm table-borderless mb-0 w-100">
                                    @foreach($process->chemicals as $chem)
                                    <tr style="border-bottom: 1px solid #f4f6f9;">
                                        <td class="text-left pl-3" style="color: black;">
                                            {{ $chem->active_code ?? '-' }}
                                        </td>
                                        <td class="text-right pr-3 font-weight-bold" style="color: black;" width="25%">
                                            {{ str_replace('.', ',', floatval($chem->pivot->concentration)) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            @else
                                <span class="text-muted font-italic">Belum ada resep obat</span>
                            @endif
                        </td>

                        <td class="align-middle">
                            @if($process->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('processes.edit', $process->id) }}" class="btn btn-warning btn-sm font-weight-bold">
                                <i class="fas fa-edit"></i> Edit Resep
                            </a>
                            <form action="{{ route('processes.destroy', $process->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus proses ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Proses">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center font-italic text-muted py-4">Belum ada data proses yang tersimpan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</section>

@foreach($processes as $item)
<div class="modal fade text-left" id="modalEdit{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <form action="{{ route('processes.update', $item->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title font-weight-bold">Edit Proses & SOP Obat</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nama Proses</label>
                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="is_active" class="form-control">
                                    <option value="1" {{ $item->is_active == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $item->is_active == 0 ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold"><i class="fas fa-flask"></i> SOP Obat Pembantu (Chemicals)</h6>
                    <table class="table table-sm table-bordered mt-2 tbl-resep">
                        <thead>
                            <tr>
                                <th width="65%">Pilih Obat / Chemical</th>
                                <th width="25%">Konsentrasi</th>
                                <th width="10%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->chemicals as $chem_pivot)
                            <tr>
                                <td>
                                    <select name="chemical_id[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach($chemicals as $chem)
                                            <option value="{{ $chem->id }}" {{ $chem_pivot->id == $chem->id ? 'selected' : '' }}>{{ $chem->active_code }} - {{ $chem->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="concentration[]" class="form-control form-control-sm" value="{{ $chem_pivot->pivot->concentration }}" required></td>
                                <td><button type="button" class="btn btn-danger btn-sm btn-hapus-baris"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-dark btn-sm btn-tambah-baris">
                        <i class="fas fa-plus"></i> Tambah Obat
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning font-weight-bold"><i class="fas fa-save"></i> Update Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('processes.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">Tambah Proses & SOP Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nama Proses</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: BLEACHING" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="is_active" class="form-control">
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold"><i class="fas fa-flask"></i> SOP Obat Pembantu (Chemicals)</h6>
                    <table class="table table-sm table-bordered mt-2 tbl-resep">
                        <thead>
                            <tr>
                                <th width="65%">Pilih Obat / Chemical</th>
                                <th width="25%">Konsentrasi</th>
                                <th width="10%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                    <button type="button" class="btn btn-dark btn-sm btn-tambah-baris">
                        <i class="fas fa-plus"></i> Tambah Obat
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save"></i> Simpan Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tombol Tambah Baris
    document.querySelectorAll('.btn-tambah-baris').forEach(button => {
        button.addEventListener('click', function() {
            // Cari tbody terdekat di dalam modal yang sama
            const tbody = this.closest('.modal-body').querySelector('.tbl-resep tbody');
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select name="chemical_id[]" class="form-control form-control-sm" required>
                        <option value="">-- Pilih Obat --</option>
                        @foreach($chemicals as $chem)
                            <option value="{{ $chem->id }}">{{ $chem->active_code }} - {{ $chem->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="concentration[]" class="form-control form-control-sm" placeholder="Contoh: 0.5" required></td>
                <td><button type="button" class="btn btn-danger btn-sm btn-hapus-baris"><i class="fas fa-times"></i></button></td>
            `;
            tbody.appendChild(tr);
        });
    });

    // Tombol Hapus Baris
    document.body.addEventListener('click', function(e) {
        if(e.target.closest('.btn-hapus-baris')) {
            e.target.closest('tr').remove();
        }
    });
});
</script>
@endsection