<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data kain, lalu jumlahkan total dari penerimaan (Masuk) DAN pemartaian (Keluar)
        $query = Fabric::withSum('receiptDetails as total_masuk', 'total_meter')
                       ->withSum('pemartaianDetails as total_keluar', 'total_meter');

        // 2. Pencarian Corak / Kode
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('corak', 'like', "%{$search}%")
                  ->orWhere('code_kain', 'like', "%{$search}%");
        }

        $stocks = $query->orderBy('corak', 'asc')->paginate(20)->withQueryString();

        return view('stocks.index', compact('stocks'));
    }
}