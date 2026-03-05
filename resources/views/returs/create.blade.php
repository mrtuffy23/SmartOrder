@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Catat Retur Barang (Keluar)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning card-outline" style="max-width: 800px; margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Form Retur Gudang Greige</h3>
            </div>
            <form action="{{ route('returs.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>No. Retur</label>
                            <input type="text" name="no_retur" value="{{ $nextRetur }}" class="form-control text-danger font-weight-bold" readonly style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tanggal Retur</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Corak Kain</label>
                            <select name="fabric_id" class="form-control" required>
                                <option value="">-- Pilih Corak Kain --</option>
                                @foreach($fabrics as $fabric)
                                    <option value="{{ $fabric->id }}">{{ $fabric->corak }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Total Meter</label>
                            <div class="input-group">
                                <input type="number" name="total_meter" step="0.01" class="form-control" placeholder="0.00" required>
                                <div class="input-group-append"><span class="input-group-text">Mtr</span></div>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Keterangan / Alasan Retur</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Kain cacat dari supplier / Salah kirim" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('returs.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Simpan Retur</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection