@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Tambah Penerimaan (Gudang Greige)</h1>
    </div>
</div>

<section class="content">
    <form action="{{ route('receipts.store') }}" method="POST">
        @csrf
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Data Bukti Terima</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No. Bukti</label>
                                <input type="text" name="no_bukti" value="{{ $nextBukti }}" class="form-control text-danger font-weight-bold" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Terima (Asal Barang)</label>
                                <input type="text" name="terima" class="form-control" value="GUDANG GREIGE" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Detail Kain Greige</h3>
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
                            <tr>
                                <td>
                                    <select name="fabric_id[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Corak --</option>
                                        @foreach($fabrics as $fabric)
                                            <option value="{{ $fabric->id }}">{{ $fabric->corak }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="total_meter[]" class="form-control form-control-sm" step="0.01" required></td>
                                <td width="20%">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold bg-light">OK/{{ date('y') }}/</span>
                                        </div>
                                        <input type="text" name="no_order[]" class="form-control text-center" placeholder="0001">
                                    </div>
                                </td>
                                <td><input type="text" name="keterangan[]" class="form-control form-control-sm" placeholder="Baru / Retur / dll"></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
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
            
            // Kosongkan nilai input pada baris baru
            newRow.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
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