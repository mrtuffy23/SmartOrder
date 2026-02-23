@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Kelola Pengguna (Users)</h1>
            </div>
            <div class="col-sm-6 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                    <i class="fas fa-user-plus"></i> Tambah User Baru
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mt-1">Daftar Akun Sistem</h3>
                <div class="card-tools">
                    <form action="{{ route('users.index') }}" method="GET" class="m-0">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" 
                                   placeholder="Cari Nama / Email / Role..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered text-center">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px">No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Hak Akses (Role)</th>
                            <th style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ method_exists($users, 'firstItem') ? $users->firstItem() + $loop->index : $loop->iteration }}</td>
                            <td class="text-left font-weight-bold">{{ $user->name }}</td>
                            <td class="text-left">{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'superadmin')
                                    <span class="badge badge-danger">Superadmin</span>
                                @elseif($user->role == 'admin')
                                    <span class="badge badge-primary">Admin Transaksi</span>
                                @elseif($user->role == 'gudang')
                                    <span class="badge badge-success">Bagian Gudang</span>
                                @else
                                    <span class="badge badge-warning">Bagian Produksi</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth()->user()->id != $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus akun {{ $user->name }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-muted">Tidak ada data user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($users, 'links'))
            <div class="card-footer clearfix">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</section>

<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Input Akun Baru</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password Akun</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label>Pilih Hak Akses (Role)</label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="superadmin">Superadmin (Bisa akses semua)</option>
                            <option value="admin">Admin (Order & Transaksi)</option>
                            <option value="gudang">Gudang (Penerimaan & Stok)</option>
                            <option value="produksi">Produksi (Pemartaian & WIP)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection