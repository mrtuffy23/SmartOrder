@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Input Penerimaan Kain</h1>
    </div>
</div>

<section class="content">
    <form action="{{ route('receipts.store') }}" method="POST">
        @csrf
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Data Bukti Penerimaan</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No Bukti</label>
                                <input type="text" name="no_bukti" class="form-control" value="{{ $auto_no_bukti }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Terima</label>
                                <input type="date" name="tgl_terima" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Terima Kain Dari</label>
                                <input type="text" name="terima_dari" class="form-control" placeholder="Contoh: Gudang Grey" value="Gudang Grey">
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" placeholder="Opsional...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Rincian Kain yang Diterima</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-light" id="add-row">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered" id="table-detail">
                        <thead class="bg-light">
                            <tr>
                                <th width="35%">Pilih Corak & Kode Kain</th>
                                <th width="20%">Total Meter</th>
                                <th width="20%">Order (Opsional)</th>
                                <th width="15%">Jml Batch</th>
                                <th width="5%" class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="fabric_id[]" class="form-control" required>
                                        <option value="">-- Pilih Kain --</option>
                                        @foreach($fabrics as $fabric)
                                            <option value="{{ $fabric->id }}">{{ $fabric->corak }} - {{ $fabric->code_kain }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="total_meter[]" class="form-control" step="0.01" placeholder="0.00" required></td>
                                <td><input type="text" name="no_order[]" class="form-control" placeholder="OK/22/10173"></td>
                                <td><input type="number" name="jml_batch[]" class="form-control" step="1" value="0"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('receipts.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Penerimaan</button>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#table-detail tbody');
        document.getElementById('add-row').addEventListener('click', function() {
            const firstRow = tableBody.rows[0];
            const newRow = firstRow.cloneNode(true);
            
            // Kosongkan nilai input di baris baru
            newRow.querySelectorAll('input').forEach(input => {
                input.value = input.name === 'jml_batch[]' ? '0' : '';
            });
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            tableBody.appendChild(newRow);
        });

        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                if (tableBody.rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Minimal harus ada 1 baris kain!');
                }
            }
        });
    });
</script>
@endsection