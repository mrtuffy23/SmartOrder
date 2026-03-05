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
                                <div class="form-group">
                                <label>Customer Level</label>
                                <select name="customer_level" class="form-control">
                                    <option value="">-- Kosongkan --</option>
                                    <option value="Makloon">Makloon</option>
                                    <option value="Lokal">Lokal</option>
                                    <option value="Export">Export</option>
                                </select>
                            </div>
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

            <div class="card card-secondary">
                <div class="card-header"><h3 class="card-title">Target & Catatan</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Target Produksi</label>
                                <textarea name="target_produksi" class="form-control" rows="3" placeholder="Ketik target produksi di sini..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Target Packing</label>
                                <textarea name="target_packing" class="form-control" rows="3" placeholder="Ketik target packing di sini..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Rincian Warna & Kuantitas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-warning font-weight-bold" id="add-row">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered text-center" id="table-detail">
                        <thead class="bg-light">
                            <tr>
                                <th width="20%">Warna</th>
                                <th>Qty OM</th>
                                <th>Batch Size</th>
                                <th>Jml Batch</th>
                                <th class="text-primary">Jml Grey (Auto)</th>
                                <th>Notes</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="color_id[]" class="form-control form-control-sm" required>
                                        <option value="">Pilih Warna</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="qty_om[]" class="form-control form-control-sm" step="0.01"></td>
                                
                                <td><input type="number" name="batch_size[]" class="form-control form-control-sm input-batch-size" step="0.01"></td>
                                <td><input type="number" name="jml_batch[]" class="form-control form-control-sm input-jml-batch" step="1"></td>
                                
                                <td><input type="number" name="jml_grey[]" class="form-control form-control-sm input-jml-grey bg-light font-weight-bold text-primary" step="0.01" readonly tabindex="-1" placeholder="0.00"></td>
                                
                                <td><input type="text" name="notes[]" class="form-control form-control-sm"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-lg float-right font-weight-bold"><i class="fas fa-save"></i> SIMPAN ORDER</button>
                </div>
            </div>

        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#table-detail tbody');
        const addButton = document.getElementById('add-row');

        // --- 1. Fungsi Tambah Baris ---
        addButton.addEventListener('click', function() {
            const firstRow = tableBody.rows[0];
            const newRow = firstRow.cloneNode(true);
            
            // Bersihkan nilai input di baris baru
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.value = '');
            
            tableBody.appendChild(newRow);
        });

        // --- 2. Fungsi Hapus Baris ---
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

        // --- 3. Fungsi Kalkulasi Otomatis Jml Grey ---
        tableBody.addEventListener('input', function(e) {
            // Cek jika yang diketik adalah Batch Size atau Jml Batch
            if (e.target.classList.contains('input-batch-size') || e.target.classList.contains('input-jml-batch')) {
                const tr = e.target.closest('tr');
                
                // Ambil nilai angkanya (jika kosong / dihapus, jadikan 0)
                const batchSize = parseFloat(tr.querySelector('.input-batch-size').value) || 0;
                const jmlBatch = parseFloat(tr.querySelector('.input-jml-batch').value) || 0;
                
                // Hitung hasil dan masukkan ke Jml Grey (dibulatkan 2 desimal)
                tr.querySelector('.input-jml-grey').value = (batchSize * jmlBatch).toFixed(2);
            }
        });
    });
</script>
@endsection