@extends('layouts.main')

@section('content')
<div class="content-header">
    <h1>Edit Pesanan ({{ $order->po_number }})</h1>
</div>

<section class="content">
    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT') <div class="container-fluid">
            
            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">Edit Data Umum</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NO PO</label>
                                <input type="text" name="po_number" class="form-control" value="{{ $order->po_number }}" required>
                            </div>
                            <div class="form-group">
    <label>Nomor MF (No Order)</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text font-weight-bold">OK/{{ date('y') }}/</span>
        </div>
        
        <input type="text" name="mf_suffix" class="form-control" 
               value="{{ $order->mf_number ? Str::afterLast($order->mf_number, '/') : '' }}" 
               placeholder="Contoh: 0001">
    </div>
    <small class="text-muted">Masukkan nomor urutnya saja (Misal: 0001)</small>
</div>
                            <div class="form-group">
                                <label>Tanggal Order</label>
                                <input type="date" name="order_date" class="form-control" value="{{ $order->order_date }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Buyer</label>
                                <select name="buyer_id" class="form-control" required>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}" {{ $order->buyer_id == $buyer->id ? 'selected' : '' }}>
                                            {{ $buyer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pilih Kain</label>
                                <select name="fabric_id" class="form-control" required>
                                    @foreach($fabrics as $fabric)
                                        <option value="{{ $fabric->id }}" {{ $order->fabric_id == $fabric->id ? 'selected' : '' }}>
                                            {{ $fabric->corak }} - {{ $fabric->code_kain }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Edit Rincian Warna</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-dark" id="add-row">
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
                            @foreach($order->details as $detail)
                            <tr>
                                <td>
                                    <select name="color_id[]" class="form-control" required>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ $detail->color_id == $color->id ? 'selected' : '' }}>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="qty_om[]" class="form-control" step="0.01" value="{{ $detail->qty_om }}"></td>
                                <td><input type="number" name="batch_size[]" class="form-control" step="0.01" value="{{ $detail->batch_size }}"></td>
                                <td><input type="number" name="jml_batch[]" class="form-control" step="1" value="{{ $detail->jml_batch }}"></td>
                                <td><input type="number" name="jml_grey[]" class="form-control" step="0.01" value="{{ $detail->jml_grey }}"></td>
                                <td><input type="text" name="notes[]" class="form-control" value="{{ $detail->notes }}"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning btn-lg float-right">UPDATE ORDER</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-lg float-right mr-2">Batal</a>
                </div>
            </div>

        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#table-detail tbody');
        const addButton = document.getElementById('add-row');

        addButton.addEventListener('click', function() {
            // Ambil baris pertama (meskipun sudah diisi data) sebagai template
            // Atau lebih aman kita clone baris terakhir
            const rows = tableBody.rows;
            const lastRow = rows[rows.length - 1];
            const newRow = lastRow.cloneNode(true);
            
            // Kosongkan nilai input di baris baru
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            
            tableBody.appendChild(newRow);
        });

        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
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