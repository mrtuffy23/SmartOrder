<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemartaianDetail;
use App\Models\DeliveryDetail;
use App\Models\Fabric;
use App\Exports\StokWipExport;
use App\Exports\StokWipIndexExport;
use Maatwebsite\Excel\Facades\Excel;

class StokWipController extends Controller
{
    // ===============================================
    // 1. DATA STOK WIP BERJALAN (REAL-TIME)
    // ===============================================
    public function index(Request $request)
    {
        // KUNCI BARU: Cari kain Pemartaian yang BELUM PERNAH masuk tabel Delivery
        $query = PemartaianDetail::with(['fabric'])
                 ->doesntHave('deliveryDetails') // Fungsi sakti Laravel
                 ->orderBy('no_batch', 'asc');

        // Filter Pencarian (Order, Corak, atau Batch)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_order', 'LIKE', "%{$search}%")
                  ->orWhere('no_batch', 'LIKE', "%{$search}%")
                  ->orWhereHas('fabric', function($qFabric) use ($search) {
                      $qFabric->where('corak', 'LIKE', "%{$search}%");
                  });
            });
        }

        $stok_wip = $query->get();

        // Karena sifatnya "ikut batch" utuh, jika belum dikirim berarti sisa aktual = total meter awal
        foreach ($stok_wip as $item) {
            $item->sisa_meter_aktual = $item->total_meter;
        }

        // ⚡ TANGKAP TOMBOL EXPORT EXCEL ⚡
        if ($request->has('export') && $request->export == 'excel') {
            $nama_file = 'Stok_WIP_' . date('d_m_Y') . '.xlsx';
            return Excel::download(new StokWipIndexExport($stok_wip), $nama_file);
        }

        return view('wip.index', compact('stok_wip'));
    }


    // ===============================================
    // 2. LAPORAN BUKU BESAR WIP (BULANAN)
    // ===============================================
    public function laporan(Request $request)
    {
        $bulan_pilih = $request->bulan ?? date('Y-m'); 
        $start_date = $bulan_pilih . '-01';
        $end_date = date('Y-m-t', strtotime($start_date)); 

        $laporan_wip = [];
        $fabrics = Fabric::orderBy('corak', 'asc')->get();

        foreach ($fabrics as $fabric) {
            // A. HITUNG SALDO AWAL (Sebelum tanggal 1 bulan ini)
            $masuk_awal = PemartaianDetail::where('fabric_id', $fabric->id)
                ->whereHas('pemartaian', fn($q) => $q->where('tanggal', '<', $start_date))->sum('total_meter');
                
            // Ambil total meter dari tabel pemartaian, tapi di-join dengan tabel pengiriman
            $keluar_awal = DeliveryDetail::join('pemartaian_details', 'delivery_details.pemartaian_detail_id', '=', 'pemartaian_details.id')
                ->join('deliveries', 'delivery_details.delivery_id', '=', 'deliveries.id')
                ->where('pemartaian_details.fabric_id', $fabric->id)
                ->where('deliveries.tanggal', '<', $start_date)
                ->sum('pemartaian_details.total_meter');

            $saldo_awal = $masuk_awal - $keluar_awal;

            // B. HITUNG TRANSAKSI BULAN INI SAJA
            $terima_ini = PemartaianDetail::where('fabric_id', $fabric->id)
                ->whereHas('pemartaian', fn($q) => $q->whereBetween('tanggal', [$start_date, $end_date]))->sum('total_meter');

            $keluar_ini = DeliveryDetail::join('pemartaian_details', 'delivery_details.pemartaian_detail_id', '=', 'pemartaian_details.id')
                ->join('deliveries', 'delivery_details.delivery_id', '=', 'deliveries.id')
                ->where('pemartaian_details.fabric_id', $fabric->id)
                ->whereBetween('deliveries.tanggal', [$start_date, $end_date])
                ->sum('pemartaian_details.total_meter');

            // C. HITUNG SALDO AKHIR
            $saldo_akhir = $saldo_awal + $terima_ini - $keluar_ini;

            // D. TAMPILKAN JIKA ADA PERGERAKAN STOK
            if ($saldo_awal != 0 || $terima_ini != 0 || $keluar_ini != 0 || $saldo_akhir != 0) {
                $laporan_wip[] = (object)[
                    'corak'       => $fabric->corak,
                    'saldo_awal'  => $saldo_awal,
                    'terima'      => $terima_ini,
                    'keluar'      => $keluar_ini,
                    'saldo_akhir' => $saldo_akhir
                ];
            }
            // ⚡ TANGKAP TOMBOL EXPORT EXCEL ⚡
            if ($request->has('export') && $request->export == 'excel') {
                $nama_file = 'Laporan_WIP_' . $bulan_pilih . '.xlsx';
                // Lempar data WIP ke Class Export
                return Excel::download(new StokWipExport($laporan_wip), $nama_file);
            }
        }

        return view('wip.laporan', compact('laporan_wip', 'bulan_pilih'));
    }
}