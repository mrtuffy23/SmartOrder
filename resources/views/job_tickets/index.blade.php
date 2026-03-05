@extends('layouts.laborat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Data Riwayat Job Ticket</h1></div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('job-tickets.create') }}" class="btn btn-primary font-weight-bold">
                    <i class="fas fa-plus"></i> Buat Ticket Baru
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-dark card-outline">
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Kode Tiket</th>
                            <th>No Order</th>
                            <th>Warna</th>
                            <th>Mesin</th>
                            <th>Proses</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-weight-bold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                            <td class="text-primary font-weight-bold">{{ $item->ticket_code }}</td>
                            <td>{{ $item->order->mf_number ?? '-' }}</td>
                            <td>{{ $item->color->name ?? '-' }}</td>
                            <td>{{ $item->machine->name ?? '-' }}</td>
                            <td>{{ $item->process->name ?? '-' }}</td>
                           <td>
                                <a href="{{ route('job-tickets.print', $item->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Print/Cetak Tiket">
                                    <i class="fas fa-print"></i> Cetak
                                </a>

                                <form action="{{ route('job-tickets.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus Job Ticket ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted">Belum ada riwayat Job Ticket.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection