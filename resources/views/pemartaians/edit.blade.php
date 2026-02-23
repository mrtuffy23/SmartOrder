@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Edit Data Pemartaian (Barang Keluar)</h1>
    </div>
</div>

<section class="content">
    <form action="{{ route('pemartaians.update', $pemartaian->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="container-fluid">
            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">Edit Bukti Pemartaian</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Partai</label>
                                <input type="text" name="no_partai" class="form-control" value="{{ $pemartaian->no_partai }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Partai</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ $pemartaian->tanggal }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jenis Pengeluaran</label>
                                <select name="jenis_pengeluaran" class="form-control">
                                    <option value="PRODUKSI" {{ $pemartaian->jenis_pengeluaran == 'PRODUKSI' ? 'selected' : '' }}>PRODUKSI</option>
                                    <option value="ORDER KERJA" {{ $pemartaian->jenis_pengeluaran == 'ORDER KERJA' ? 'selected' : '' }}>ORDER KERJA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Keterangan Pengiriman</label>
                                <input type="text" name="keterangan" class="form-control" value="{{ $pemartaian->keterangan }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Rincian Kain yang Dikeluarkan</h3>
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
                                <th width="15%">No. Order</th>
                                <th width="20%">Kode / Corak</th>
                                <th width="15%">Warna</th>
                                <th width="10%">No. Batch</th>
                                <th width="10%">Jml Gulung</th>
                                <th width="12%">Total Meter</th>
                                <th width="10%">Berat (Kg)</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pemartaian->details as $detail)
                            <tr>
                                <td><input type="text" name="no_order[]" class="form-control form-control-sm" value="{{ $detail->no_order }}"></td>
                                <td>
                                    <select name="fabric_id[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($fabrics as $fabric)
                                            <option value="{{ $fabric->id }}" {{ $detail->fabric_id == $fabric->id ? 'selected' : '' }}>
                                                {{ $fabric->code_kain }} - {{ $fabric->corak }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="warna[]" class="form-control form-control-sm" value="{{ $detail->warna }}"></td>
                                <td><input type="text" name="no_batch[]" class="form-control form-control-sm" value="{{ $detail->no_batch }}"></td>
                                <td><input type="number" name="jml_gulung[]" class="form-control form-control-sm" step="1" value="{{ $detail->jml_gulung }}"></td>
                                <td><input type="number" name="total_meter[]" class="form-control form-control-sm" step="0.01" value="{{ $detail->total_meter }}" required></td>
                                <td><input type="number" name="berat[]" class="form-control form-control-sm" step="0.01" value="{{ $detail->berat }}"></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('pemartaians.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update Pemartaian</button>
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
            
            newRow.querySelectorAll('input').forEach(input => {
                if(input.type === 'number') { input.value = '0'; } 
                else { input.value = ''; }
            });
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            tableBody.appendChild(newRow);
        });

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