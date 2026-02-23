<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DeliveryDetail;
use App\Models\Buyer;
use App\Models\QualityFinish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    // 1. TAMPILKAN DAFTAR PENGIRIMAN
    public function index(Request $request)
    {
        $query = Delivery::with(['buyer', 'details.qualityFinish.pemartaianDetail.fabric'])->latest('tanggal_kirim');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('no_surat_jalan', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $deliveries = $query->paginate(15)->withQueryString();
        return view('deliveries.index', compact('deliveries'));
    }

    // 2. TAMPILKAN FORM SURAT JALAN BARU
    public function create()
    {
        // Ambil data Buyer
        $buyers = Buyer::orderBy('name', 'asc')->get();
        
        // Ambil data Barang Jadi yang siap kirim
        $finished_goods = QualityFinish::with(['pemartaianDetail.fabric'])->orderBy('tanggal_finish', 'desc')->get();
        
        // Bikin nomor Surat Jalan otomatis (Format: SJ/Tahun/001)
        $tahun = date('y');
        $prefix = "SJ/$tahun/";
        $lastDelivery = Delivery::where('no_surat_jalan', 'like', $prefix . '%')->latest('id')->first();
        
        $nomorBaru = 1;
        if ($lastDelivery) {
            $pecah = explode('/', $lastDelivery->no_surat_jalan);
            $nomorBaru = (int) end($pecah) + 1;
        }
        $auto_no_sj = $prefix . sprintf('%04d', $nomorBaru);

        return view('deliveries.create', compact('buyers', 'finished_goods', 'auto_no_sj'));
    }

    // 3. SIMPAN DATA PENGIRIMAN KE DATABASE
    public function store(Request $request)
    {
        $request->validate([
            'no_surat_jalan' => 'required|unique:deliveries,no_surat_jalan',
            'tanggal_kirim' => 'required|date',
            'buyer_id' => 'required|exists:buyers,id',
            'quality_finish_id' => 'required|array',
            'quality_finish_id.*' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            // A. Simpan Header Surat Jalan
            $delivery = Delivery::create([
                'no_surat_jalan' => $request->no_surat_jalan,
                'tanggal_kirim' => $request->tanggal_kirim,
                'buyer_id' => $request->buyer_id,
                'no_kendaraan' => $request->no_kendaraan,
                'nama_supir' => $request->nama_supir,
                'keterangan' => $request->keterangan,
            ]);

            // B. Simpan Detail Kain yang Dikirim
            $finishes = $request->quality_finish_id;
            foreach ($finishes as $key => $finishId) {
                if(!$finishId) continue;
                
                DeliveryDetail::create([
                    'delivery_id' => $delivery->id,
                    'quality_finish_id' => $finishId,
                    'jml_roll' => $request->jml_roll[$key] ?? 0,
                    'total_meter' => $request->total_meter[$key] ?? 0,
                    'total_berat' => $request->total_berat[$key] ?? 0,
                ]);
            }
        });

        return redirect()->route('deliveries.index')->with('success', 'Surat Jalan berhasil dibuat dan disimpan!');
    }
    // 4. TAMPILKAN FORM EDIT
    public function edit($id)
    {
        $delivery = Delivery::with('details')->findOrFail($id);
        $buyers = Buyer::orderBy('name', 'asc')->get();
        $finished_goods = QualityFinish::with(['pemartaianDetail.fabric'])->orderBy('tanggal_finish', 'desc')->get();

        return view('deliveries.edit', compact('delivery', 'buyers', 'finished_goods'));
    }

    // 5. UPDATE DATA PENGIRIMAN
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_surat_jalan' => 'required|unique:deliveries,no_surat_jalan,' . $id,
            'tanggal_kirim' => 'required|date',
            'buyer_id' => 'required|exists:buyers,id',
            'quality_finish_id' => 'required|array',
            'quality_finish_id.*' => 'required',
        ]);

        DB::transaction(function () use ($request, $id) {
            $delivery = Delivery::findOrFail($id);

            // A. Update Header Surat Jalan
            $delivery->update([
                'no_surat_jalan' => $request->no_surat_jalan,
                'tanggal_kirim' => $request->tanggal_kirim,
                'buyer_id' => $request->buyer_id,
                'no_kendaraan' => $request->no_kendaraan,
                'nama_supir' => $request->nama_supir,
                'keterangan' => $request->keterangan,
            ]);

            // B. Hapus detail muatan lama
            $delivery->details()->delete();

            // C. Masukkan detail muatan baru
            $finishes = $request->quality_finish_id;
            foreach ($finishes as $key => $finishId) {
                if(!$finishId) continue;
                
                DeliveryDetail::create([
                    'delivery_id' => $delivery->id,
                    'quality_finish_id' => $finishId,
                    'jml_roll' => $request->jml_roll[$key] ?? 0,
                    'total_meter' => $request->total_meter[$key] ?? 0,
                    'total_berat' => $request->total_berat[$key] ?? 0,
                ]);
            }
        });

        return redirect()->route('deliveries.index')->with('success', 'Data Surat Jalan berhasil diperbarui!');
    }

    // 6. HAPUS DATA PENGIRIMAN
    public function destroy($id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->delete();

        return redirect()->route('deliveries.index')->with('success', 'Surat Jalan berhasil dihapus!');
    }

    // 7. CETAK SURAT JALAN (PRINT / PDF)
    public function print($id)
    {
        // Ambil data lengkap sampai ke akar relasinya
        $delivery = Delivery::with(['buyer', 'details.qualityFinish.pemartaianDetail.fabric'])->findOrFail($id);
        
        // Kita return ke view khusus untuk print (tanpa sidebar & navbar)
        return view('deliveries.print', compact('delivery'));
    }
}