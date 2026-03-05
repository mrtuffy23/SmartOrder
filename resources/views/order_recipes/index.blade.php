@extends('layouts.laborat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Buku Resep Original (Lab Dip)</h1></div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('order-recipes.create') }}" class="btn btn-primary font-weight-bold">
                    <i class="fas fa-plus"></i> Tambah Resep Baru
                </a>
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
                <table class="table table-bordered table-striped text-center">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">No Order</th>
                            <th width="15%">Warna</th>
                            <th width="30%">Resep Zat Warna (Dyes)</th>
                            <th width="25%">Resep Obat (Chemicals)</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recipes as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-weight-bold text-primary">{{ $item->order->mf_number ?? '-' }}</td>
                            <td class="font-weight-bold">{{ $item->color->name ?? '-' }}</td>
                            
                            <td class="text-left">
                                @forelse($item->dyestuffs as $dye)
                                    <span class="badge badge-info" style="font-size: 13px; margin:2px;">
                                        {{ $dye->dyestuff->active_code ?? '-' }} 
                                        <span class="text-warning">({{ rtrim(rtrim($dye->concentration, '0'), '.') }}%)</span>
                                    </span>
                                @empty
                                    <span class="text-muted text-sm"><i>-</i></span>
                                @endforelse
                            </td>
                            
                            <td class="text-left">
                                @forelse($item->chemicals as $chem)
                                    <span class="badge badge-warning" style="font-size: 13px; margin:2px;">
                                        {{ $chem->chemical->active_code ?? '-' }} 
                                        <span class="text-dark">({{ rtrim(rtrim($chem->concentration, '0'), '.') }} g/L)</span>
                                    </span>
                                @empty
                                    <span class="text-muted text-sm"><i>-</i></span>
                                @endforelse
                            </td>
                            
                            <td>
                                <form action="{{ route('order-recipes.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus resep ini?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">Belum ada Buku Resep Original.</td></tr>
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