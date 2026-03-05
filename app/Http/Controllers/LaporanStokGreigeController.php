<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\ReceiptDetail;
use App\Models\PemartaianDetail;
use App\Models\Retur;
use App\Exports\StokGreigeExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanStokGreigeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil inputan bulan (Default: Bulan ini)
        $bulan_pilih = $request->bulan ?? date('Y-m'); 
        
        // Tentukan tanggal awal (tanggal 1) dan tanggal akhir bulan tersebut
        $start_date = $bulan_pilih . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $laporan = [];
        $fabrics = Fabric::orderBy('corak', 'asc')->get();

        foreach ($fabrics as $fabric) {
            // ==========================================
            // A. HITUNG SALDO AWAL (Semua transaksi SEBELUM tanggal 1 bulan ini)
            // ==========================================
            $terima_awal = ReceiptDetail::where('fabric_id', $fabric->id)
                ->whereHas('receipt', function($q) use ($start_date) {
                    $q->where('tanggal', '<', $start_date);
                })->sum('total_meter');
                
            $pemartaian_awal = PemartaianDetail::where('fabric_id', $fabric->id)
                ->whereHas('pemartaian', function($q) use ($start_date) {
                    $q->where('tanggal', '<', $start_date);
                })->sum('total_meter');

            $retur_awal = Retur::where('fabric_id', $fabric->id)
                ->where('tanggal', '<', $start_date)
                ->sum('total_meter');

            $saldo_awal = $terima_awal - ($pemartaian_awal + $retur_awal);

            // ==========================================
            // B. HITUNG TRANSAKSI BULAN INI SAJA
            // ==========================================
            $terima_bulan_ini = ReceiptDetail::where('fabric_id', $fabric->id)
                ->whereHas('receipt', function($q) use ($start_date, $end_date) {
                    $q->whereBetween('tanggal', [$start_date, $end_date]);
                })->sum('total_meter');

            $pemartaian_bulan_ini = PemartaianDetail::where('fabric_id', $fabric->id)
                ->whereHas('pemartaian', function($q) use ($start_date, $end_date) {
                    $q->whereBetween('tanggal', [$start_date, $end_date]);
                })->sum('total_meter');

            $retur_bulan_ini = Retur::where('fabric_id', $fabric->id)
                ->whereBetween('tanggal', [$start_date, $end_date])
                ->sum('total_meter');

            // ==========================================
            // C. HITUNG SALDO AKHIR BULAN INI
            // ==========================================
            $saldo_akhir = $saldo_awal + $terima_bulan_ini - ($pemartaian_bulan_ini + $retur_bulan_ini);

            // ==========================================
            // D. FILTER: Jangan tampilkan corak yang kosong
            // ==========================================
            if ($saldo_awal != 0 || $terima_bulan_ini != 0 || $pemartaian_bulan_ini != 0 || $retur_bulan_ini != 0 || $saldo_akhir != 0) {
                $laporan[] = (object)[
                    'corak'       => $fabric->corak,
                    'saldo_awal'  => $saldo_awal,
                    'terima'      => $terima_bulan_ini,
                    'pemartaian'  => $pemartaian_bulan_ini,
                    'retur'       => $retur_bulan_ini,
                    'saldo_akhir' => $saldo_akhir
                ];
            }
        }

        // =======================================================
        // ⚡ 2. TANGKAP TOMBOL EXPORT EXCEL DI SINI ⚡
        // =======================================================
        if ($request->has('export') && $request->export == 'excel') {
            // Nama file menyesuaikan bulan yang dipilih di form
            $nama_file = 'Laporan_Stok_Greige_' . $bulan_pilih . '.xlsx';
            
            // Lempar array $laporan ke file Export (StokGreigeExport)
            return Excel::download(new StokGreigeExport($laporan), $nama_file);
        }

        // =======================================================
        // 3. JIKA BUKAN EXPORT, TAMPILKAN HALAMAN WEB BIASA
        // =======================================================
        return view('laporan.stok_greige', compact('laporan', 'bulan_pilih'));
    }
}