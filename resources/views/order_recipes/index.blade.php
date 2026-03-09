@extends('layouts.laborat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            
            <div class="col-sm-6">
                <h1 class="m-0">Data Resep Original</h1>
            </div>
            
            <div class="col-sm-6">
                <div class="d-flex justify-content-end align-items-center">
                    
                    <form action="{{ route('order-recipes.index') }}" method="GET" class="m-0 mr-2">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Cari No Order / Warna..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('order-recipes.index') }}" class="btn btn-danger" title="Reset Pencarian">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <a href="{{ route('order-recipes.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                        <i class="fas fa-plus"></i> Tambah Resep Baru
                    </a>
                    
                </div>
            </div>

        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button class="close" data-dismiss="alert">&times;</button>{{ session('success') }}
            </div>
        @endif

        <div class="card card-dark card-outline">
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="align-middle">No</th>
                            <th width="15%" class="align-middle">No Order</th>
                            <th width="10%" class="align-middle">Warna</th>
                            <th width="30%" class="align-middle">Dyestuffs</th>
                            <th width="30%" class="align-middle">Chemicals</th>
                            <th width="10%" class="align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recipes as $index => $recipe)
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle font-weight-bold text-primary">{{ $recipe->order->mf_number ?? '-' }}</td>
                            <td class="align-middle font-weight-bold">{{ $recipe->color->name ?? '-' }}</td>
                            
                            <td class="p-0 align-middle">
                                <table class="table table-sm table-borderless mb-0 w-100">
                                    @foreach($recipe->dyestuffs as $dye)
                                    <tr style="border-bottom: 1px solid #f4f6f9;">
                                        <td class="text-left pl-3" style="color: black;">
                                            {{ $dye->dyestuff->active_code ?? '-' }}
                                        </td>
                                        <td class="text-right pr-3 font-weight-bold" style="color: black;" width="35%">
                                            {{ str_replace('.', ',', floatval($dye->concentration)) }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>

                            <td class="p-0 align-middle">
                                <table class="table table-sm table-borderless mb-0 w-100">
                                    @foreach($recipe->chemicals as $chem)
                                    <tr style="border-bottom: 1px solid #f4f6f9;">
                                        <td class="text-left pl-3" style="color: black;">
                                            {{ $chem->chemical->active_code ?? '-' }}
                                        </td>
                                        <td class="text-right pr-3 font-weight-bold" style="color: black;" width="40%">
                                            {{ str_replace('.', ',', floatval($chem->concentration)) }} g/L
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>

                            <td class="align-middle">
                                <form action="{{ route('order-recipes.destroy', $recipe->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus resep ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus Resep">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center font-italic text-muted py-4">Belum ada data resep yang tersimpan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $recipes->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection