@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Tambah Data Pemartaian (Produksi)</h1>
    </div>
</div>

<section class="content">
    <form action="{{ route('pemartaians.store') }}" method="POST">
        @csrf
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Bukti Keluar Pemartaian</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Partai</label>
                                <input type="text" name="no_partai" value="{{ $nextPartai }}" class="form-control text-danger font-weight-bold" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jenis Pengeluaran</label>
                                <select name="jenis_pengeluaran" class="form-control" required>
                                    <option value="ORDER KERJA">ORDER KERJA</option>
                                    <option value="LAINNYA">LAINNYA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ket. Pengiriman</label>
                                <input type="text" name="ket_induk" class="form-control" value="PRODUKSI" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Rincian Kain yang Dikeluarkan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-light font-weight-bold" id="add-row">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-sm text-center" id="table-detail">
                        <thead class="bg-light">
                            <tr>
                                <th width="12%">No. Order</th>
                                <th width="20%">Corak</th>
                                <th width="10%">Warna</th>
                                <th width="8%">Batch</th>
                                <th width="8%">Gulung</th>
                                <th width="10%">Meter</th>
                                <th width="8%">Berat(Kg)</th>
                                <th width="14%">Keterangan</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="15%">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold bg-light">OK/{{ date('y') }}/</span>
                                        </div>
                                        <input type="text" name="no_order[]" class="form-control text-center" placeholder="0001" required>
                                    </div>
                                </td>
                                <td>
                                    <select name="fabric_id[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Corak --</option>
                                        @foreach($fabrics as $fabric)
                                            <option value="{{ $fabric->id }}">{{ $fabric->corak }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                <input type="text" name="warna[]" class="form-control form-control-sm" placeholder="Kosongkan jika tidak ada">
                                </td>

                                <td>
                                    <input type="text" name="no_batch[]" class="form-control form-control-sm text-center font-weight-bold" placeholder="Ketik Batch" required>
                                </td>
                                
                                <td><input type="number" name="jml_gulung[]" class="form-control form-control-sm" required></td>
                                <td><input type="number" name="total_meter[]" class="form-control form-control-sm" step="0.01" required></td>
                                <td><input type="number" name="berat[]" class="form-control form-control-sm" step="0.01" required></td>
                                
                                <td><input type="text" name="keterangan[]" class="form-control form-control-sm" placeholder="Contoh: EX"></td>
                                
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('pemartaians.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#table-detail tbody');
        
        // Fitur Tambah Baris
        document.getElementById('add-row').addEventListener('click', function() {
            const firstRow = tableBody.rows[0];
            const newRow = firstRow.cloneNode(true);
            
            // Kosongkan semua input KECUALI yang readonly (tulisan AUTO)
            newRow.querySelectorAll('input:not([readonly])').forEach(input => {
                input.value = '';
            });
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            
            tableBody.appendChild(newRow);
        });

        // Fitur Hapus Baris
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