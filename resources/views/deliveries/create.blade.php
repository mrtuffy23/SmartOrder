@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Tambah Data Pengiriman (Delivery)</h1>
    </div>
</div>

<section class="content">
    <form action="{{ route('deliveries.store') }}" method="POST">
        @csrf
        <div class="container-fluid">
            
            <div class="card card-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 form-group mb-0">
                            <label>Tanggal Pengiriman</label>
                            <input type="date" name="tanggal" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Rincian Kain & Buyer</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-light font-weight-bold" id="add-row">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-sm text-center align-middle" id="table-detail" style="min-width: 1200px;">
                        <thead class="bg-light">
                            <tr>
                                <th width="12%">Pilih Batch</th>
                                <th width="15%" class="text-primary">Buyer</th>
                                <th width="12%" class="text-primary">No. Order</th>
                                <th width="10%">Corak</th>
                                <th width="12%" class="text-primary">Warna</th>
                                <th width="6%">Gulung</th>
                                <th width="8%">Meter</th>
                                <th width="8%" class="text-primary">No. Roda</th>
                                <th width="12%" class="text-primary">Ket</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="pemartaian_detail_id[]" class="form-control form-control-sm select-batch" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($available_batches as $batch)
                                            <option value="{{ $batch->id }}" 
                                                data-corak="{{ $batch->fabric->corak ?? '' }}"
                                                data-gulung="{{ $batch->jml_gulung }}"
                                                data-meter="{{ $batch->total_meter }}">
                                                {{ $batch->no_batch }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <td>
                                    <select name="buyer_id[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Buyer --</option>
                                        @foreach($buyers as $buyer)
                                            <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
    
                                <td>
                                    <select name="mf_number[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih no order --</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->mf_number }}">{{ $order->mf_number }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <td><input type="text" class="form-control form-control-sm bg-light get-corak" readonly tabindex="-1"></td>
                                
                                <td>
                                    <select name="color_id[]" class="form-control form-control-sm" required>
                                        <option value="">-- Pilih Warna --</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <td><input type="text" class="form-control form-control-sm bg-light get-gulung text-center" readonly tabindex="-1"></td>
                                <td><input type="text" class="form-control form-control-sm bg-light get-meter text-center" readonly tabindex="-1"></td>
                                
                                <td><input type="text" name="no_roda[]" class="form-control form-control-sm" required></td>
                                <td><input type="text" name="keterangan[]" class="form-control form-control-sm"></td>
                                
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save"></i> Simpan Pengiriman</button>
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
            
            // Bersihkan value text dan reset semua dropdown (select)
            newRow.querySelectorAll('input:not([readonly])').forEach(input => input.value = '');
            newRow.querySelectorAll('.get-corak, .get-gulung, .get-meter').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            
            tableBody.appendChild(newRow);
        });

        // Fitur Hapus Baris
        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                if (tableBody.rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Minimal 1 baris rincian!');
                }
            }
        });

        // Fitur AUTO-FILL saat Batch dipilih
        tableBody.addEventListener('change', function(e) {
            if (e.target.classList.contains('select-batch')) {
                const tr = e.target.closest('tr');
                const selectedOption = e.target.options[e.target.selectedIndex];
                
                // Isi inputan readonly (Hanya Corak, Gulung, Meter)
                tr.querySelector('.get-corak').value = selectedOption.dataset.corak || '';
                tr.querySelector('.get-gulung').value = selectedOption.dataset.gulung || '';
                tr.querySelector('.get-meter').value = selectedOption.dataset.meter || '';
            }
        });
    });
</script>
@endsection