@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Edit Data Kain</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Form Edit</h3>
            </div>
            <form action="{{ route('fabrics.update', $fabrics->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="card-body">
                    <div class="form-group">
                        <label>Corak</label>
                        <input type="text" name="corak" class="form-control" value="{{ old('corak', $fabrics->corak) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Kain</label>
                        <input type="text" name="code_kain" class="form-control" value="{{ old('code_kain', $fabrics->code_kain) }}">
                    </div>
                    <div class="form-group">
                        <label>Kode Quality</label>
                        <input type="text" name="quality" class="form-control" value="{{ old('quality', $fabrics->quality) }}">
                    </div><div class="form-group">
                        <label>Kode Buyer</label>
                        <input type="text" name="buyer_code" class="form-control" value="{{ old('buyer_code', $fabrics->buyer_code) }}">
                    </div><div class="form-group">
                        <label>Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $fabrics->brand) }}">
                    </div><div class="form-group">
                        <label>Construction</label>
                        <input type="text" name="construction" class="form-control" value="{{ old('construction', $fabrics->construction) }}">
                    </div><div class="form-group">
                        <label>Density</label>
                        <input type="text" name="density" class="form-control" value="{{ old('density', $fabrics->density) }}">
                    </div>
                    <div class="form-group">
                        <label>Status Kain</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1" {{ $fabrics->is_active == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $fabrics->is_active == 0 ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Update Data</button>
                    <a href="{{ route('fabrics.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection