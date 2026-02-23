<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
{
    // 1. Mulai Query Dasar
    $query = OrderDetail::with(['order.buyer', 'order.fabric', 'color']);

    // 2. Cek apakah user sedang mencari sesuatu?
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function($q) use ($search) {
            // A. Cari di tabel ORDER (PO Number atau MF Number)
            $q->whereHas('order', function($qOrder) use ($search) {
                $qOrder->where('po_number', 'like', "%{$search}%")
                       ->orWhere('mf_number', 'like', "%{$search}%")
                       // Cari Nama Buyer (Relasi dari Order ke Buyer)
                       ->orWhereHas('buyer', function($qBuyer) use ($search) {
                           $qBuyer->where('name', 'like', "%{$search}%");
                       })
                       // Cari Kode Kain (Relasi dari Order ke Fabric)
                       ->orWhereHas('fabric', function($qFabric) use ($search) {
                           $qFabric->where('code_kain', 'like', "%{$search}%")
                                   ->orWhere('corak', 'like', "%{$search}%");
                       });
            })
            // B. Cari di tabel COLOR (Nama Warna)
            ->orWhereHas('color', function($qColor) use ($search) {
                $qColor->where('name', 'like', "%{$search}%");
            });
        });
    }

    // 3. Ambil data + Pagination (PENTING: tambah withQueryString biar halaman 2 tetap tersaring)
    $transactions = $query->latest()->paginate(50)->withQueryString();

    return view('reports.index', compact('transactions'));
}

    public function export()
    {
        // Download file Excel
        return Excel::download(new TransactionExport, 'laporan-semua-transaksi.xlsx');
    }
}