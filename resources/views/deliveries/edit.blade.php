@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Edit Surat Jalan (Pengiriman)</h1>
    </div>
</div>

<section class="content">
    <form action="{{ route('deliveries.update', $delivery->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="container-fluid">
            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">Edit Data Pengiriman</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Surat Jalan</label>
                                <input type="text" name="no_surat_jalan" class="form-control" value="{{ $delivery->no_surat_jalan }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Kirim</label>
                                <input type="date" name="tanggal_kirim" class="form-control" value="{{ $delivery->tanggal_kirim }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tujuan Kirim (Customer/Buyer)</label>
                                <select name="buyer_id" class="form-control" required>
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}" {{ $delivery->buyer_id == $buyer->id ? 'selected' : '' }}>
                                            {{ $buyer->name }} ({{ $buyer->kode_buyer }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Plat Kendaraan</label>
                                <input type="text" name="no_kendaraan" class="form-control" value="{{ $delivery->no_kendaraan }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Supir</label>
                                <input type="text" name="nama_supir" class="form-control" value="{{ $delivery->nama_supir }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Keterangan Tambahan</label>
                                <input type="text" name="keterangan" class="form-control" value="{{ $delivery->keterangan }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Edit Muatan Kain</h3>
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
                                <th width="45%">Pilih Kain Siap Kirim</th>
                                <th width="15%">Jml Roll</th>
                                <th width="15%">Total Meter</th>
                                <th width="15%">Berat (Kg)</th>
                                <th width="10%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delivery->details as $detail)
                            <tr>
                                <td>
                                    <select name="quality_finish_id[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Kain --</option>
                                        @foreach($finished_goods as $fg)
                                            <option value="{{ $fg->id }}" {{ $detail->quality_finish_id == $fg->id ? 'selected' : '' }}>
                                                Order: {{ $fg->pemartaianDetail->no_order ?? '-' }} | Corak: {{ $fg->pemartaianDetail->fabric->corak ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="jml_roll[]" class="form-control form-control-sm" step="1" value="{{ $detail->jml_roll }}"></td>
                                <td><input type="number" name="total_meter[]" class="form-control form-control-sm" step="0.01" value="{{ $detail->total_meter }}" required></td>
                                <td><input type="number" name="total_berat[]" class="form-control form-control-sm" step="0.01" value="{{ $detail->total_berat }}"></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('deliveries.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update Surat Jalan</button>
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
                input.value = input.type === 'number' ? '0' : '';
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