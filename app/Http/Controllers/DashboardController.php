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

        $total_masuk_wip = PemartaianDetail::sum('total_meter');
        $total_keluar_wip = DeliveryDetail::join('pemartaian_details', 'delivery_details.pemartaian_detail_id', '=', 'pemartaian_details.id')
            ->sum('pemartaian_details.total_meter');
        $total_wip = $total_masuk_wip - $total_keluar_wip;

        $total_pengiriman = DeliveryDetail::join('pemartaian_details', 'delivery_details.pemartaian_detail_id', '=', 'pemartaian_details.id')
            ->join('deliveries', 'delivery_details.delivery_id', '=', 'deliveries.id')
            ->whereRaw("DATE_FORMAT(deliveries.tanggal, '%Y-%m') = ?", [Carbon::now()->format('Y-m')])
            ->sum('pemartaian_details.total_meter');

        

        // ==========================================
        // 3. DATA GRAFIK ANALITIK 6 BULAN (Bawah)
        // ==========================================
        $bulan_labels = [];
        $data_produksi = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $bulan_labels[] = $bulan->translatedFormat('M Y');

            $data_produksi[] = PemartaianDetail::join('pemartaians', 'pemartaian_details.pemartaian_id', '=', 'pemartaians.id')
                ->whereRaw("DATE_FORMAT(pemartaians.tanggal, '%Y-%m') = ?", [$bulan->format('Y-m')])
                ->sum('pemartaian_details.total_meter');
        }

        
        return view('dashboard.index', compact(
            'total_buyer', 'total_kain', 'total_color', 'total_order',
            'saldo_grey', 'total_wip', 'total_pengiriman',
            'bulan_labels', 'data_produksi'
        ));
    }
}