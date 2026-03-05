@extends('layouts.laborat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Job Ticket Laborat</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button class="close" data-dismiss="alert">&times;</button>{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('job-tickets.store') }}" method="POST">
            @csrf
            
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <table class="table table-borderless table-sm m-0">
                                <tr>
                                    <td width="25%" class="font-weight-bold align-middle">Order</td>
                                    <td>
                                        <select name="order_id" id="order_id" class="form-control form-control-sm" required>
                                            <option value="">-- Pilih Order --</option>
                                            @foreach($orders as $order)
                                                <option value="{{ $order->id }}">{{ $order->mf_number }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold align-middle">Warna</td>
                                    <td>
                                        <select name="color_id" id="color_id" class="form-control form-control-sm" required>
                                            <option value="">-- Pilih Order Dulu --</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold align-middle">Mesin</td>
                                    <td>
                                        <select name="machine_id" id="machine_id" class="form-control form-control-sm" required>
                                            <option value="">-- Pilih Mesin --</option>
                                            @foreach($machines as $mc)
                                                <option value="{{ $mc->id }}" data-volume="{{ $mc->volume }}">{{ $mc->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold align-middle">Proses</td>
                                    <td>
                                        <select name="process_id" id="process_id" class="form-control form-control-sm" required>
                                            <option value="">-- Pilih Proses --</option>
                                            @foreach($processes as $pr)
                                                <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-4 offset-md-3">
                            <div class="info-box bg-light shadow-sm mb-2">
                                <span class="info-box-icon bg-info"><i class="fas fa-weight-hanging"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Fabric Weight (Kg)</span>
                                    <input type="number" name="fabric_weight" id="fabric_weight" class="form-control form-control-sm font-weight-bold input-trigger" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="info-box bg-light shadow-sm">
                                <span class="info-box-icon bg-primary"><i class="fas fa-water"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Volume Mesin (Liter)</span>
                                    <input type="number" name="volume" id="volume" class="form-control form-control-sm font-weight-bold input-trigger" readonly tabindex="-1">
                                </div>
                            </div>
                            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-info">
                <div class="card-header"><h3 class="card-title">Resep Zat Warna (Dyestuffs)</h3></div>
                <div class="card-body p-0">
                    <table class="table table-bordered text-center" id="table-dyestuff">
                        <thead class="bg-light">
                            <tr>
                                <th width="50%">D/A Code (Pilih Zat Warna)</th>
                                <th width="20%">Konsentrasi (%)</th>
                                <th width="25%">Timbangan (Gram)</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="dyestuff_id[]" class="form-control form-control-sm">
                                        <option value="">-- Pilih Zat Warna --</option>
                                        @foreach($dyestuffs as $dye)
                                            <option value="{{ $dye->id }}">{{ $dye->active_code }} - {{ $dye->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="dye_concentration[]" class="form-control form-control-sm dye-conc input-trigger" step="0.00001"></td>
                                <td><input type="number" name="dye_gram[]" class="form-control form-control-sm dye-gram bg-light font-weight-bold text-success" readonly tabindex="-1"></td>
                                <td><button type="button" class="btn btn-danger btn-sm hapus-baris"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-dark btn-sm m-2" id="tambah-dyestuff"><i class="fas fa-plus"></i> Tambah Warna</button>
                </div>
            </div>

            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">Resep Bahan Pembantu (Chemicals)</h3></div>
                <div class="card-body p-0">
                    <table class="table table-bordered text-center" id="table-chemical">
                        <thead class="bg-light">
                            <tr>
                                <th width="50%">Pilih Bahan Kimia (Auxiliaries)</th>
                                <th width="20%">Konsentrasi (g/L)</th>
                                <th width="25%">Timbangan (Gram)</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                    <button type="button" class="btn btn-dark btn-sm m-2" id="tambah-chemical"><i class="fas fa-plus"></i> Tambah Obat Ekstra</button>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-lg float-right font-weight-bold"><i class="fas fa-save"></i> SIMPAN JOB TICKET</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const masterProcesses = @json($processes->keyBy('id'));
    const masterDyestuffs = @json($dyestuffs);
    const masterChemicals = @json($chemicals);

    let orderSelect = document.getElementById('order_id');
    let colorSelect = document.getElementById('color_id');
    let weightInput = document.getElementById('fabric_weight');

    // ==========================================
    // 1. JIKA ORDER DIPILIH -> Tarik Daftar Warna
    // ==========================================
    orderSelect.addEventListener('change', function() {
        let orderId = this.value;
        colorSelect.innerHTML = '<option value="">-- Sedang mencari warna... --</option>';
        weightInput.value = '';
        bersihkanTabelResep();

        if(orderId) {
            fetch(`{{ url('job-tickets/get-order') }}/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    colorSelect.innerHTML = '<option value="">-- Pilih Warna --</option>';
                    if(data.colors && data.colors.length > 0) {
                        data.colors.forEach(color => {
                            let option = document.createElement('option');
                            option.value = color.id;
                            option.text = color.name;
                            option.setAttribute('data-weight', color.weight); 
                            colorSelect.appendChild(option);
                        });
                    } else {
                        colorSelect.innerHTML = '<option value="">-- Order ini tidak memiliki warna! --</option>';
                    }
                });
        } else {
            colorSelect.innerHTML = '<option value="">-- Pilih Order Terlebih Dahulu --</option>';
        }
    });

    // ==========================================
    // 2. JIKA WARNA DIPILIH -> Sedot Buku Resep! ⚡
    // ==========================================
    colorSelect.addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        weightInput.value = selectedOption.getAttribute('data-weight') || ''; // Isi Berat Kain
        
        let orderId = orderSelect.value;
        let colorId = this.value;

        bersihkanTabelResep();

        if(orderId && colorId) {
            fetch(`{{ url('job-tickets/get-recipe') }}/${orderId}/${colorId}`)
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        // A. Masukkan Zat Warna (Dyestuffs) dari Resep
                        data.dyestuffs.forEach(dye => {
                            tambahBarisDyestuff(dye.dyestuff_id, dye.concentration);
                        });

                        // B. Masukkan Bahan Kimia (Auxiliaries) dari Resep
                        data.chemicals.forEach(chem => {
                            tambahBarisChemical(chem.chemical_id, chem.concentration);
                        });
                        
                        // C. Trigger Proses (Siapa tau proses sudah dipilih duluan)
                        document.getElementById('process_id').dispatchEvent(new Event('change'));
                        
                        // Hitung Gram otomatis
                        calculateSemua(); 
                    } else {
                        alert('⚠️ Resep untuk warna ini belum ada di "Buku Resep Original"! Silakan ketik manual atau buat resepnya dulu.');
                        tambahBarisDyestuff(); // Kasih 1 baris kosong
                    }
                });
        }
    });

    // ==========================================
    // 3. JIKA MESIN & PROSES DIPILIH
    // ==========================================
    document.getElementById('machine_id').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        document.getElementById('volume').value = selectedOption.getAttribute('data-volume') || '';
        calculateSemua();
    });

    document.getElementById('process_id').addEventListener('change', function() {
        let processId = this.value;
        
        // Hapus obat kimia bawaan proses (SOP) yang lama (kita tandai dengan class 'sop-proses')
        document.querySelectorAll('.sop-proses').forEach(e => e.remove());

        // Tambahkan obat kimia SOP sesuai proses yang dipilih
        if(processId && masterProcesses[processId]) {
            let sops = masterProcesses[processId].chemicals;
            sops.forEach(chem => {
                tambahBarisChemical(chem.id, chem.pivot.concentration, true); // true = tandai sebagai SOP
            });
        }
        calculateSemua();
    });

    // ==========================================
    // 4. KALKULATOR AJAIB (RUMUS GRAM) 🧮
    // ==========================================
    function calculateSemua() {
        let weight = parseFloat(document.getElementById('fabric_weight').value) || 0;
        let volume = parseFloat(document.getElementById('volume').value) || 0;
        
        let processSelect = document.getElementById('process_id');
        let processName = processSelect.options[processSelect.selectedIndex]?.text.toUpperCase() || "";
        let isCPB = processName.includes("CPB");

        // Hitung Zat Warna (Dye)
        document.querySelectorAll('#table-dyestuff tbody tr').forEach(row => {
            let concInput = row.querySelector('.dye-conc');
            let gramInput = row.querySelector('.dye-gram');
            if(concInput && gramInput) {
                let conc = parseFloat(concInput.value) || 0;
                let gram = isCPB ? (conc * volume) : ((conc / 100) * weight * 1000);
                gramInput.value = gram.toFixed(2);
            }
        });

        // Hitung Obat Kimia (Chem)
        document.querySelectorAll('#table-chemical tbody tr').forEach(row => {
            let concInput = row.querySelector('.chem-conc');
            let gramInput = row.querySelector('.chem-gram');
            if(concInput && gramInput) {
                let conc = parseFloat(concInput.value) || 0;
                gramInput.value = (conc * volume).toFixed(2);
            }
        });
    }

    // Pemicu Hitung Real-time saat angkanya diketik manual
    document.body.addEventListener('input', function(e) {
        if(e.target.classList.contains('input-trigger') || e.target.classList.contains('dye-conc') || e.target.classList.contains('chem-conc')) {
            calculateSemua();
        }
    });

    // ==========================================
    // 5. FUNGSI TAMBAH/HAPUS BARIS
    // ==========================================
    function bersihkanTabelResep() {
        document.querySelector('#table-dyestuff tbody').innerHTML = '';
        document.querySelector('#table-chemical tbody').innerHTML = '';
    }

    function tambahBarisDyestuff(selectedDyeId = '', concentrationVal = '') {
        let tr = document.createElement('tr');
        let optionsHTML = '<option value="">-- Pilih Zat Warna --</option>';
        masterDyestuffs.forEach(dye => {
            let isSelected = (dye.id == selectedDyeId) ? 'selected' : '';
            optionsHTML += `<option value="${dye.id}" ${isSelected}>${dye.active_code} - ${dye.name}</option>`;
        });

        tr.innerHTML = `
            <td><select name="dyestuff_id[]" class="form-control form-control-sm" required>${optionsHTML}</select></td>
            <td><input type="number" name="dye_concentration[]" class="form-control form-control-sm dye-conc" step="0.00001" value="${concentrationVal}" required></td>
            <td><input type="number" name="dye_gram[]" class="form-control form-control-sm dye-gram bg-light font-weight-bold text-success" readonly tabindex="-1"></td>
            <td><button type="button" class="btn btn-danger btn-sm hapus-baris"><i class="fas fa-times"></i></button></td>
        `;
        document.querySelector('#table-dyestuff tbody').appendChild(tr);
    }

    function tambahBarisChemical(selectedChemId = '', concentrationVal = '', isSOP = false) {
        let tr = document.createElement('tr');
        if(isSOP) tr.classList.add('sop-proses'); // Beri tanda jika ini obat bawaan Proses
        
        let optionsHTML = '<option value="">-- Pilih Bahan Kimia --</option>';
        masterChemicals.forEach(chem => {
            let isSelected = (chem.id == selectedChemId) ? 'selected' : '';
            optionsHTML += `<option value="${chem.id}" ${isSelected}>${chem.active_code} - ${chem.name}</option>`;
        });

        tr.innerHTML = `
            <td><select name="chemical_id[]" class="form-control form-control-sm">${optionsHTML}</select></td>
            <td><input type="number" name="chem_concentration[]" class="form-control form-control-sm chem-conc" step="0.00001" value="${concentrationVal}"></td>
            <td><input type="number" name="chem_gram[]" class="form-control form-control-sm chem-gram bg-light font-weight-bold text-success" readonly tabindex="-1"></td>
            <td><button type="button" class="btn btn-danger btn-sm hapus-baris"><i class="fas fa-times"></i></button></td>
        `;
        document.querySelector('#table-chemical tbody').appendChild(tr);
    }

    document.getElementById('tambah-dyestuff').addEventListener('click', () => tambahBarisDyestuff());
    document.getElementById('tambah-chemical').addEventListener('click', () => tambahBarisChemical());
    
    document.body.addEventListener('click', function(e) {
        if(e.target.closest('.hapus-baris')) {
            e.target.closest('tr').remove();
            calculateSemua(); 
        }
    });

});
</script>
@endsection