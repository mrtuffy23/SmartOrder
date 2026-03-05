@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Edit Penerimaan (Gudang Greige)</h1>
    </div>
</div>

<section class="content">
    <form action="{{ route('receipts.update', $receipt->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="container-fluid">
            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">Edit Bukti Terima</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No. Bukti</label>
                                <input type="text" value="{{ $receipt->no_bukti }}" class="form-control text-danger font-weight-bold" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required value="{{ $receipt->tanggal }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Terima (Asal Barang)</label>
                                <input type="text" name="terima" class="form-control" value="{{ $receipt->terima }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Edit Detail Kain Greige</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-light" id="add-row">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-sm text-center" id="table-detail">
                        <thead class="bg-light">
                            <tr>
                                <th width="25%">Corak Kain</th>
                                <th width="20%">Meter</th>
                                <th width="25%">No. Order</th>
                                <th width="20%">Keterangan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipt->details as $detail)
                            <tr>
                                <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                
                                <td>
                                    <select name="fabric_id[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Corak --</option>
                                        @foreach($fabrics as $fabric)
                                            <option value="{{ $fabric->id }}" {{ $detail->fabric_id == $fabric->id ? 'selected' : '' }}>
                                                {{ $fabric->corak }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="total_meter[]" class="form-control form-control-sm" step="0.01" value="{{ $detail->total_meter }}" required></td>
                                <td width="20%">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold bg-light">OK/{{ date('y') }}/</span>
                                        </div>
                                        <input type="text" name="no_order[]" class="form-control text-center" value="{{ $detail->no_order ? Str::afterLast($detail->no_order, '/') : '' }}">
                                    </div>
                                </td>
                                <td><input type="text" name="keterangan[]" class="form-control form-control-sm" value="{{ $detail->keterangan }}"></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(session('error'))
                    <div class="alert alert-danger font-weight-bold alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-ban mr-2"></i> {{ session('error') }}
                    </div>
                @endif
                <div class="card-footer text-right">
                    <a href="{{ route('receipts.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update Data</button>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#table-detail tbody');
        
        // Fungsi Tambah Baris
        document.getElementById('add-row').addEventListener('click', function() {
            const firstRow = tableBody.rows[0];
            const newRow = firstRow.cloneNode(true);
            
            // Kosongkan semua input teks dan angka di baris baru
            newRow.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
            // Reset pilihan select
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            
            tableBody.appendChild(newRow);
        });

        // Fungsi Hapus Baris
        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                if (tableBody.rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Minimal harus ada 1 baris rincian!');
                }
            }
        });
    });
</script>
@endsection