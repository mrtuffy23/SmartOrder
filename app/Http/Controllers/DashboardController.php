<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Buyer;
use App\Models\Fabric;
use App\Models\Color; 
use App\Models\Order;
use App\Models\ReceiptDetail;
use App\Models\PemartaianDetail;
use App\Models\QualityFinish;
use App\Models\Delivery;
use App\Models\DeliveryDetail;

class DashboardController extends Controller
{
    public function index()
    {
        // ==========================================
        // 1. DATA MASTER & TRANSAKSI (Baris Atas)
        // ==========================================
        $total_buyer = Buyer::count();
        $total_kain = Fabric::count();
        $total_color = Color::count(); 
        $total_order = Order::count();

        // ==========================================
        // 2. DATA GUDANG & PRODUKSI (Baris Tengah)
        // ==========================================
        $total_masuk_grey = ReceiptDetail::sum('total_meter');
        $total_keluar_grey = PemartaianDetail::sum('total_meter');
        $saldo_grey = $total_masuk_grey - $total_keluar_grey;

        $total_wip = PemartaianDetail::doesntHave('qualityFinish')->count();
        
        $total_meter_finish = QualityFinish::sum('hasil_meter'); 
        $total_meter_dikirim = DeliveryDetail::sum('total_meter'); 
        $saldo_barang_jadi = $total_meter_finish - $total_meter_dikirim; 

        $delivery_bulan_ini = Delivery::whereMonth('tanggal_kirim', date('m'))
                                      ->whereYear('tanggal_kirim', date('Y'))
                                      ->count();

        // ==========================================
        // 3. DATA GRAFIK ANALITIK 6 BULAN (Bawah)
        // ==========================================
        $bulan_labels = [];
        $data_produksi = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $bulan_labels[] = $bulan->translatedFormat('M Y');
            
            $total_meter_bulan = QualityFinish::whereYear('tanggal_finish', $bulan->year)
                                              ->whereMonth('tanggal_finish', $bulan->month)
                                              ->sum('hasil_meter');
            $data_produksi[] = $total_meter_bulan;
        }

        
        return view('dashboard.index', compact(
            'total_buyer', 'total_kain', 'total_color', 'total_order',
            'saldo_grey', 'total_wip', 'saldo_barang_jadi', 'delivery_bulan_ini',
            'bulan_labels', 'data_produksi'
        ));
    }
}