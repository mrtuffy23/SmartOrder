<?php

namespace App\Http\Controllers;

use App\Models\QualityFinish;
use Illuminate\Http\Request;

class QualityFinishController extends Controller
{
    // 1. TAMPILKAN DAFTAR BARANG JADI (QUALITY FINISH)
    public function index(Request $request)
    {
        // Ambil data finish beserta relasi ke detail WIP, induk partai, dan master kain
        $query = QualityFinish::with(['pemartaianDetail.fabric', 'pemartaianDetail.pemartaian'])
                 ->orderBy('tanggal_finish', 'desc');

        // Fitur Pencarian Dinamis
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pemartaianDetail', function($q) use ($search) {
                $q->where('no_order', 'like', "%{$search}%")
                  ->orWhere('no_batch', 'like', "%{$search}%")
                  ->orWhereHas('fabric', function($qFabric) use ($search) {
                      $qFabric->where('corak', 'like', "%{$search}%")
                             ->orWhere('code_kain', 'like', "%{$search}%");
                  });
            });
        }

        $finished_goods = $query->paginate(20)->withQueryString();

        return view('quality_finishes.index', compact('finished_goods'));
    }
    // MENYIMPAN DATA KAIN YANG SUDAH SELESAI DIPROSES
    public function store(Request $request)
    {
        $request->validate([
            'pemartaian_detail_id' => 'required|exists:pemartaian_details,id',
            'tanggal_finish' => 'required|date',
            'hasil_meter' => 'required|numeric',
            'hasil_berat' => 'nullable|numeric',
            'grade' => 'required|string',
        ]);

        QualityFinish::create([
            'pemartaian_detail_id' => $request->pemartaian_detail_id,
            'tanggal_finish' => $request->tanggal_finish,
            'hasil_meter' => $request->hasil_meter,
            'hasil_berat' => $request->hasil_berat,
            'grade' => $request->grade,
            'keterangan' => $request->keterangan,
        ]);

        // Setelah disimpan, kembalikan user ke halaman WIP
        return redirect()->route('wip.index')->with('success', 'Kain berhasil diselesaikan dan masuk ke Gudang Barang Jadi!');
    }
}