@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Manajemen Tutup Buku</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card card-danger">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-lock"></i> Kunci Bulan Baru</h3></div>
                    <form action="{{ route('tutup_buku.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Pilih Bulan & Tahun</label>
                                <input type="month" name="bulan" class="form-control font-weight-bold" required>
                                <small class="text-muted">Transaksi pada bulan yang dipilih tidak akan bisa ditambah, diedit, atau dihapus.</small>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-danger font-weight-bold" onclick="return confirm('Yakin ingin menutup buku bulan ini?')">
                                <i class="fas fa-key"></i> Clossing
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-dark">
                    <div class="card-header"><h3 class="card-title">Status Bulan Transaksi</h3></div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th>Bulan</th>
                                    <th>Status Terkini</th>
                                    <th>Aksi (Buka Darurat)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tutup_bukus as $tb)
                                <tr>
                                    <td class="font-weight-bold" style="font-size: 18px;">{{ date('F Y', strtotime($tb->bulan . '-01')) }}</td>
                                    <td>
                                        @if($tb->status == 'closed')
                                            <span class="badge badge-danger p-2"><i class="fas fa-lock"></i> TERKUNCI (CLOSED)</span>
                                        @else
                                            <span class="badge badge-success p-2"><i class="fas fa-unlock"></i> TERBUKA (OPEN)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tb->status == 'closed')
                                            <form action="{{ route('tutup_buku.open', $tb->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-outline-success btn-sm font-weight-bold" onclick="return confirm('YAKIN BUKA BUKU? Admin akan bisa mengedit data di bulan ini lagi lho!')">
                                                    <i class="fas fa-unlock"></i> Buka Akses
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('tutup_buku.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="bulan" value="{{ $tb->bulan }}">
                                                <button class="btn btn-outline-danger btn-sm font-weight-bold">
                                                    <i class="fas fa-lock"></i> Kunci Kembali
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-muted py-4">Belum ada bulan yang dikunci.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection