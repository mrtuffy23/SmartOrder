@extends('layouts.main')

@section('content')
<div class="content-header">
    <h1>Buat Pesanan Baru</h1>
</div>

<section class="content">
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="container-fluid">
            
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Data Umum</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NO PO</label>
                                <input type="text" name="po_number" class="form-control" required>
                            </div>
                            <div class="form-group">
    <label>Nomor MF (No Order)</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text font-weight-bold">OK/{{ date('y') }}/</span>
        </div>
        
        <input type="text" name="mf_suffix" class="form-control" placeholder="Contoh: 0001" required>
    </div>
    <small class="text-muted">Masukkan nomor urutnya saja (Misal: 0001)</small>
</div>
                            <div class="form-group">
                                <label>Tanggal Order</label>
                                <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Buyer</label>
                                <select name="buyer_id" class="form-control" required>
                                    <option value="">-- Pilih Buyer --</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pilih Kain</label>
                                <select name="fabric_id" class="form-control" required>
                                    <option value="">-- Pilih Kain --</option>
                                    @foreach($fabrics as $fabric)
                                        <option value="{{ $fabric->id }}">{{ $fabric->corak }} - {{ $fabric->code_kain }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Rincian Warna & Kuantitas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-warning" id="add-row">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered text-center" id="table-detail">
                        <thead>
                            <tr>
                                <th width="20%">Warna</th>
                                <th>Qty OM</th>
                                <th>Batch Size</th>
                                <th>Jml Batch</th>
                                <th>Jml Grey</th>
                                <th>Notes</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="color_id[]" class="form-control" required>
                                        <option value="">Pilih Warna</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="qty_om[]" class="form-control" step="0.01"></td>
                                <td><input type="number" name="batch_size[]" class="form-control" step="0.01"></td>
                                <td><input type="number" name="jml_batch[]" class="form-control" step="1"></td>
                                <td><input type="number" name="jml_grey[]" class="form-control" step="0.01"></td>
                                <td><input type="text" name="notes[]" class="form-control"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-lg float-right">SIMPAN ORDER</button>
                </div>
            </div>

        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#table-detail tbody');
        const addButton = document.getElementById('add-row');

        // Fungsi Tambah Baris
        addButton.addEventListener('click', function() {
            const firstRow = tableBody.rows[0];
            const newRow = firstRow.cloneNode(true);
            
            // Bersihkan nilai input di baris baru
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.value = '');
            
            tableBody.appendChild(newRow);
        });

        // Fungsi Hapus Baris (Delegasi Event)
        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                // Sisakan minimal 1 baris
                if (tableBody.rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Minimal harus ada 1 baris warna!');
                }
            }
        });
    });
</script>
@endsection