@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Edit Data Pengguna</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Form Edit Akun</h3>
                    </div>
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="form-group">
                                <label>Alamat Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            <div class="form-group">
                                <label>Password Baru <span class="text-danger text-sm">*Kosongkan jika tidak ingin mengubah password</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Ketik password baru...">
                            </div>
                            <div class="form-group">
                                <label>Hak Akses (Role)</label>
                                <select name="role" class="form-control" required>
                                    <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin Transaksi</option>
                                    <option value="gudang" {{ $user->role == 'gudang' ? 'selected' : '' }}>Bagian Gudang</option>
                                    <option value="produksi" {{ $user->role == 'produksi' ? 'selected' : '' }}>Bagian Produksi</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">Kembali</a>
                            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection