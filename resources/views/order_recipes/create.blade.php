@extends('layouts.laborat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Buat Data Resep Original (Lab Dip)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button class="close" data-dismiss="alert">&times;</button>{{ session('error') }}
            </div>
        @endif

        <form action="{{ route('order-recipes.store') }}" method="POST">
            @csrf
            
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title font-weight-bold">Pilih Order & Warna</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Order</label>
                                <select name="order_id" id="order_id" class="form-control" required>
                                    <option value="">-- Pilih Order --</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}">{{ $order->mf_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Target Warna</label>
                                <select name="color_id" id="color_id" class="form-control" required>
                                    <option value="">-- Pilih Order Terlebih Dahulu --</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header"><h3 class="card-title">Resep Zat Warna (Dyestuffs)</h3></div>
                        <div class="card-body p-0">
                            <table class="table table-bordered text-center" id="table-dyestuff">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="65%">Zat Warna (D/A Code)</th>
                                        <th width="25%">Konsentrasi (%)</th>
                                        <th width="10%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="dyestuff_id[]" class="form-control form-control-sm" required>
                                                <option value="">-- Pilih --</option>
                                                @foreach($dyestuffs as $dye)
                                                    <option value="{{ $dye->id }}">{{ $dye->active_code }} - {{ $dye->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="dye_concentration[]" class="form-control form-control-sm" step="0.00001" placeholder="Cth: 0.08" required></td>
                                        <td><button type="button" class="btn btn-danger btn-sm hapus-baris"><i class="fas fa-times"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-dark btn-sm m-2" id="btn-add-dye"><i class="fas fa-plus"></i> Tambah Warna</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header"><h3 class="card-title">Resep Bahan Kimia (Auxiliaries)</h3></div>
                        <div class="card-body p-0">
                            <table class="table table-bordered text-center" id="table-chemical">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="65%">Obat Pembantu</th>
                                        <th width="25%">Takaran (g/L)</th>
                                        <th width="10%">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="chemical_id[]" class="form-control form-control-sm">
                                                <option value="">-- Pilih --</option>
                                                @foreach($chemicals as $chem)
                                                    <option value="{{ $chem->id }}">{{ $chem->active_code }} - {{ $chem->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="chem_concentration[]" class="form-control form-control-sm" step="0.00001" placeholder="Cth: 0.5"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm hapus-baris"><i class="fas fa-times"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-dark btn-sm m-2" id="btn-add-chem"><i class="fas fa-plus"></i> Tambah Obat</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right mb-4">
                <button type="submit" class="btn btn-success btn-lg font-weight-bold"><i class="fas fa-save"></i> SIMPAN RESEP ORIGINAL</button>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Tarik Data Warna Otomatis saat Order Dipilih
    document.getElementById('order_id').addEventListener('change', function() {
        let orderId = this.value;
        let colorSelect = document.getElementById('color_id');
        
        // Munculkan teks loading agar Admin tahu sistem sedang bekerja
        colorSelect.innerHTML = '<option value="">-- Sedang mencari warna... --</option>';

        if(orderId) {
            // Gunakan helper url() bawaan Laravel agar jalurnya 100% akurat
            fetch(`{{ url('job-tickets/get-order') }}/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    colorSelect.innerHTML = '<option value="">-- Pilih Warna --</option>';
                    
                    // Jika order tersebut PUNYA warna
                    if(data.colors && data.colors.length > 0) {
                        data.colors.forEach(color => {
                            let option = document.createElement('option');
                            option.value = color.id;
                            option.text = color.name;
                            colorSelect.appendChild(option);
                        });
                    } else {
                        // Jika order tersebut KOSONG (tidak punya warna)
                        colorSelect.innerHTML = '<option value="">-- Order ini tidak memiliki warna! --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error AJAX:', error);
                    colorSelect.innerHTML = '<option value="">-- Gagal menarik data! Cek koneksi/route --</option>';
                });
        } else {
            colorSelect.innerHTML = '<option value="">-- Pilih Order Terlebih Dahulu --</option>';
        }
    });

    // 2. Tambah Baris Zat Warna
    document.getElementById('btn-add-dye').addEventListener('click', function() {
        let tbody = document.querySelector('#table-dyestuff tbody');
        let newRow = tbody.querySelector('tr').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        tbody.appendChild(newRow);
    });

    // 3. Tambah Baris Obat Kimia
    document.getElementById('btn-add-chem').addEventListener('click', function() {
        let tbody = document.querySelector('#table-chemical tbody');
        let newRow = tbody.querySelector('tr').cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        tbody.appendChild(newRow);
    });

    // 4. Hapus Baris
    document.body.addEventListener('click', function(e) {
        if(e.target.closest('.hapus-baris')) {
            let tr = e.target.closest('tr');
            let tbody = tr.parentElement;
            if(tbody.querySelectorAll('tr').length > 1) {
                tr.remove();
            } else {
                alert('Minimal harus ada 1 baris input!');
            }
        }
    });

});
</script>
@endsection