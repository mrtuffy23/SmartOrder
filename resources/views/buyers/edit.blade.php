@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Edit Data Buyer</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Form Edit</h3>
            </div>
            <form action="{{ route('buyers.update', $buyer->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="card-body">
                    <div class="form-group">
                        <label>Nama Buyer</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $buyer->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Buyer</label>
                        <input type="text" name="kode_buyer" class="form-control" value="{{ old('kode_buyer', $buyer->kode_buyer) }}">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Update Data</button>
                    <a href="{{ route('buyers.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection