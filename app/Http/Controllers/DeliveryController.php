<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DeliveryDetail;
use App\Models\Buyer;
use App\Models\QualityFinish;
use App\Models\TutupBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\DeliveryExport;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryController extends Controller
{
    // 1. TAMPILKAN DAFTAR PENGIRIMAN
    public function index(Request $request)
    {
        $query = \App\Models\Delivery::with([
            'buyer',
            'details.buyer',
            'details.color', // 🔥 Tambahkan ini agar warna di Excel tidak kosong
            'details.pemartaianDetail.fabric'
        ]);

        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$request->bulan]);
        }

        // Search by buyer name or no_order
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('buyer', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhereHas('details', function($q2) use ($search) {
                    $q2->where('no_order', 'like', "%{$search}%");
                });
            });
        }

        // =======================================================
        // ⚡ TANGKAP TOMBOL EXPORT EXCEL ⚡
        // =======================================================
        if ($request->has('export') && $request->export == 'excel') {
            $fileName = 'Data_Delivery_' . date('d_m_Y') . '.xlsx';
            
            // 1. Ambil data Induk (Delivery)
            $deliveries = $query->get(); 
            
            // 2. 🔥 INI KUNCINYA: Pecah data Induk menjadi baris-baris Rincian (Detail)
            $data_export = collect();
            foreach ($deliveries as $delivery) {
                foreach ($delivery->details as $detail) {
                    // Sisipkan relasi induk agar tanggalnya bisa dibaca di Excel
                    $detail->setRelation('delivery', $delivery); 
                    $data_export->push($detail);
                }
            }
            
            // 3. Lempar data Rincian yang sudah dipecah ke class Export
            return Excel::download(new DeliveryExport($data_export), $fileName);
        }

        // Jika bukan export, tampilkan web biasa
        $deliveries = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        // Ambil daftar bulan yang sudah ditutup
        $closed_months = TutupBuku::where('status', 'closed')->pluck('bulan')->toArray();

        return view('deliveries.index', compact('deliveries', 'closed_months'));
    }

    // 2. TAMPILKAN FORM SURAT JALAN BARU
    public function create()
    {
        // Panggil Master Data untuk Dropdown di tabel
        $buyers = \App\Models\Buyer::orderBy('name', 'asc')->get();
        $colors = \App\Models\Color::orderBy('name', 'asc')->get();

        // Ambil Batch yang belum terkirim
        $available_batches = \App\Models\PemartaianDetail::with('fabric')
            ->doesntHave('deliveryDetails')
            ->get();

        $orders = \App\Models\Order::orderBy('mf_number', 'asc')->get();

        return view('deliveries.create', compact('available_batches', 'buyers', 'colors', 'orders'));
    }

    public function store(Request $request)
    {
        // Cek apakah bulan dari tanggal yang diinput sudah ditutup
        $bulan = date('Y-m', strtotime($request->tanggal));
        if (TutupBuku::where('bulan', $bulan)->where('status', 'closed')->exists()) {
            return back()->withInput()->with('error', 'Gagal! Bulan ' . date('F Y', strtotime($bulan . '-01')) . ' sudah ditutup. Tidak bisa menambah data delivery.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // 1. Simpan Induk (Sekarang cuma Tanggal saja)
            $delivery = \App\Models\Delivery::create([
                'tanggal' => $request->tanggal,
            ]);

            // 2. Simpan Rincian
            $batches = $request->pemartaian_detail_id;
            if ($batches) {
                foreach ($batches as $key => $batch_id) {
                    \App\Models\DeliveryDetail::create([
                        'delivery_id'          => $delivery->id,
                        'pemartaian_detail_id' => $batch_id,
                        
                        // 👇 Simpan data inputan per baris 👇
                        'buyer_id'             => $request->buyer_id[$key],
                        'no_order'             => $request->mf_number[$key],
                        'color_id'             => $request->color_id[$key],
                        'no_roda'              => $request->no_roda[$key],
                        'keterangan'           => $request->keterangan[$key],
                    ]);
                }
            }
        });

        return redirect()->route('deliveries.index')->with('success', 'Data Pengiriman berhasil disimpan!');
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

        // Cek apakah bulan delivery sudah ditutup
        $bulan = date('Y-m', strtotime($delivery->tanggal));
        if (TutupBuku::where('bulan', $bulan)->where('status', 'closed')->exists()) {
            return back()->with('error', 'Gagal! Bulan ' . date('F Y', strtotime($bulan . '-01')) . ' sudah ditutup. Tidak bisa menghapus data delivery.');
        }

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