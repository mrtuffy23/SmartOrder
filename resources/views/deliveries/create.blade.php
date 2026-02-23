@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Buat Surat Jalan (Pengiriman)</h1>
    </div>
</div>

<section class="content">
    <form action="{{ route('deliveries.store') }}" method="POST">
        @csrf
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Data Pengiriman</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Surat Jalan</label>
                                <input type="text" name="no_surat_jalan" class="form-control" value="{{ $auto_no_sj }}" required readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Kirim</label>
                                <input type="date" name="tanggal_kirim" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tujuan Kirim (Customer/Buyer)</label>
                                <select name="buyer_id" class="form-control" required>
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}">{{ $buyer->name }} ({{ $buyer->kode_buyer }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Plat Kendaraan (Truk/Mobil)</label>
                                <input type="text" name="no_kendaraan" class="form-control" placeholder="Contoh: B 1234 CD">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Supir</label>
                                <input type="text" name="nama_supir" class="form-control" placeholder="Nama Supir...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Keterangan Tambahan</label>
                                <input type="text" name="keterangan" class="form-control" placeholder="Opsional...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Muatan Kain (Barang Jadi)</h3>
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
                                <th width="45%">Pilih Kain (Dari Gudang Barang Jadi)</th>
                                <th width="15%">Jml Roll</th>
                                <th width="15%">Total Meter</th>
                                <th width="15%">Berat (Kg)</th>
                                <th width="10%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="quality_finish_id[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Kain Siap Kirim --</option>
                                        @foreach($finished_goods as $fg)
                                            <option value="{{ $fg->id }}">
                                                Order: {{ $fg->pemartaianDetail->no_order ?? '-' }} | Corak: {{ $fg->pemartaianDetail->fabric->corak ?? '-' }} (Tersedia: {{ number_format($fg->hasil_meter, 2) }} Mtr)
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="jml_roll[]" class="form-control form-control-sm" step="1" value="0"></td>
                                <td><input type="number" name="total_meter[]" class="form-control form-control-sm" step="0.01" value="0" required></td>
                                <td><input type="number" name="total_berat[]" class="form-control form-control-sm" step="0.01" value="0"></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('deliveries.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-truck"></i> Proses Pengiriman</button>
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