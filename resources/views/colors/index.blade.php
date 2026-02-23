@extends('layouts.main')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Master Data Warna</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Warna Baru</h3>
                    </div>
                    <form action="{{ route('colors.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Warna</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: NAVY BLUE" required>
                                @error('name')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mt-1">Daftar Warna Tersedia</h3>
                        <div class="card-tools">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('colors.export') }}" class="btn btn-success btn-sm mr-2">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                                
                                <form action="{{ route('colors.index') }}" method="GET" class="m-0">
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="text" name="search" class="form-control float-right" 
                                               placeholder="Cari Warna..." 
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
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>Nama Warna</th>
                                    <th style="width: 120px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($colors as $color)
                                <tr>
                                    <td>{{ method_exists($colors, 'firstItem') ? $colors->firstItem() + $loop->index : $loop->iteration }}</td>
                                    <td>
                                        <span class="badge badge-light border">{{ $color->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-warning btn-xs mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('colors.destroy', $color->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Yakin hapus warna {{ $color->name }}?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Data warna belum ada atau tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(method_exists($colors, 'links'))
                    <div class="card-footer clearfix">
                        {{ $colors->links('pagination::bootstrap-4') }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
@endsection