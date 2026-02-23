<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QualityFinish;

class StokBarangJadiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data finish, dan jumlahkan total meter yang sudah dikirim (deliveryDetails)
        $query = QualityFinish::with(['pemartaianDetail.fabric', 'pemartaianDetail.pemartaian'])
                 ->withSum('deliveryDetails', 'total_meter')
                 ->orderBy('tanggal_finish', 'desc')
                 ->get(); // Kita pakai get() dulu untuk difilter menggunakan Collection

        // Filter pencarian jika ada
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query = $query->filter(function ($item) use ($search) {
                $no_order = strtolower($item->pemartaianDetail->no_order ?? '');
                $corak = strtolower($item->pemartaianDetail->fabric->corak ?? '');
                return str_contains($no_order, $search) || str_contains($corak, $search);
            });
        }

        // KUNCI UTAMA: Hanya tampilkan kain yang sisa meternya > 0 (Belum habis dikirim)
        $stok_barang = $query->filter(function ($item) {
            $terkirim = $item->delivery_details_sum_total_meter ?? 0;
            $sisa = $item->hasil_meter - $terkirim;
            
            // Simpan variabel sisa ke dalam object agar bisa dipanggil di View
            $item->sisa_meter_aktual = $sisa;
            $item->total_terkirim = $terkirim;

            return $sisa > 0;
        });

        return view('stok_barang_jadi.index', compact('stok_barang'));
    }
}