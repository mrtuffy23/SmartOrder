@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Data Buyers (Pemesan)</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Buyer Baru</h3>
                    </div>
                    <form action="{{ route('buyers.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Buyer</label>
                                <input type="text" name="name" class="form-control" placeholder="PT. Indotex..." required>
                            </div>
                            <div class="form-group">
                                <label>Kode Buyer</label>
                                <input type="text" name="kode_buyer" class="form-control" placeholder="Contoh: BUY-001">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    
                    <div class="card-header">
                        <h3 class="card-title mt-1">Daftar Buyers</h3>
                        <div class="card-tools">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('buyers.export') }}" class="btn btn-success btn-sm mr-2">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>

                                <form action="{{ route('buyers.index') }}" method="GET" class="m-0">
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="text" name="search" class="form-control float-right" 
                                               placeholder="Cari Nama / Kode..." 
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
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">NO</th>
                                    <th>Kode</th>
                                    <th>Nama Buyer</th>
                                    <th style="width: 120px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($buyers as $buyer)
                                <tr>
                                    <td>{{ method_exists($buyers, 'firstItem') ? $buyers->firstItem() + $loop->index : $loop->iteration }}</td>
                                    <td><span class="badge bg-info">{{ $buyer->kode_buyer ?? '-' }}</span></td>
                                    <td>{{ $buyer->name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('buyers.edit', $buyer->id) }}" class="btn btn-warning btn-xs mr-1">
                                            <i class="fas fa-edit"></i> 
                                        </a>

                                        <form action="{{ route('buyers.destroy', $buyer->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Hapus buyer ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Data buyer belum ada atau tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($buyers, 'links'))
                    <div class="card-footer clearfix">
                        {{ $buyers->links('pagination::bootstrap-4') }}
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>
@endsection