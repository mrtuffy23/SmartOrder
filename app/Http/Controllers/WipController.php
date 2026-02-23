<?php

namespace App\Http\Controllers;

use App\Models\PemartaianDetail;
use Illuminate\Http\Request;

class WipController extends Controller
{
    public function index(Request $request)
    {
       $query = PemartaianDetail::with(['pemartaian', 'fabric'])
                 ->join('pemartaians', 'pemartaian_details.pemartaian_id', '=', 'pemartaians.id')
                 ->select('pemartaian_details.*')
                 // 👇 KUNCI UTAMA: Hanya tampilkan yang BELUM di-finish 👇
                 ->doesntHave('qualityFinish') 
                 ->orderBy('pemartaians.tanggal', 'desc'); 

        // Fitur Pencarian Dinamis
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pemartaian_details.no_order', 'like', "%{$search}%")
                  ->orWhere('pemartaian_details.no_batch', 'like', "%{$search}%")
                  ->orWhereHas('fabric', function($qFabric) use ($search) {
                      $qFabric->where('corak', 'like', "%{$search}%");
                  })
                  ->orWhereHas('pemartaian', function($qPartai) use ($search) {
                      $qPartai->where('no_partai', 'like', "%{$search}%");
                  });
            });
        }

        $wip_kain = $query->paginate(20)->withQueryString();

        return view('wip.index', compact('wip_kain'));
    }
}