<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Buyer;
use App\Models\Fabric;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Wajib import ini untuk Transaksi

class OrderController extends Controller
{
    // 1. DAFTAR ORDER
    public function index()
    {
        // Ambil order beserta data buyer & kain biar loadingnya cepat (Eager Loading)
        $orders = Order::with(['buyer', 'fabric'])->latest()->get();
        return view('orders.index', compact('orders'));
    }

    // 2. FORM BUAT ORDER BARU
    public function create()
    {
        // Kita butuh data ini untuk Dropdown (Pilihan)
        $buyers = Buyer::all();
        $fabrics = Fabric::all();
        $colors = Color::all();
        
        return view('orders.create', compact('buyers', 'fabrics', 'colors'));
    }

    // 3. SIMPAN DATA (HEADER + DETAIL)
    public function store(Request $request)
    {
        // Validasi Header
        $request->validate([
            'po_number' => 'required|unique:orders',
            'order_date' => 'required|date',
            'buyer_id' => 'required',
            'fabric_id' => 'required',
            // Validasi Array (Detail)
            'color_id' => 'required|array',
            'color_id.*' => 'required', // Tiap baris warna wajib diisi
        ]);

        $tahun = date('y');
        $full_mf_number = "OK/" . $tahun . "/" . $request->mf_suffix;
        // Gunakan DB Transaction biar kalau error, data tidak masuk setengah-setengah
        DB::transaction(function () use ($request,$full_mf_number) {
            
            // A. Simpan Header (Order)
            $order = Order::create([
                'po_number' => $request->po_number,
                'mf_number' => $full_mf_number,
                'order_date' => $request->order_date,
                'buyer_id' => $request->buyer_id,
                'fabric_id' => $request->fabric_id,
            ]);

            // B. Simpan Detail (Looping Array)
            // Kita ambil data dari array input
            $colors = $request->color_id;
            
            foreach ($colors as $key => $colorId) {
                OrderDetail::create([
                    'order_id' => $order->id, // Ambil ID dari order yang baru dibuat
                    'color_id' => $colorId,
                    'qty_om' => $request->qty_om[$key] ?? 0,
                    'batch_size' => $request->batch_size[$key] ?? 0,
                    'jml_batch' => $request->jml_batch[$key] ?? 0,
                    'jml_grey' => $request->jml_grey[$key] ?? 0,
                    'notes' => $request->notes[$key] ?? null,
                ]);
            }

        });

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat!');
    }

    // 4. LIHAT DETAIL ORDER
    public function show($id)
    {
        $order = Order::with(['buyer', 'fabric', 'details.color'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    // 5. HAPUS ORDER
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->details()->delete(); // Hapus anaknya dulu
        $order->delete(); // Baru hapus induknya
        return redirect()->route('orders.index')->with('success', 'Order dihapus!');
    }
    // 6. TAMPILKAN FORM EDIT
    public function edit($id)
    {
        $order = Order::with('details')->findOrFail($id);
        
        // Data Master untuk Dropdown
        $buyers = Buyer::all();
        $fabrics = Fabric::all();
        $colors = Color::all();

        return view('orders.edit', compact('order', 'buyers', 'fabrics', 'colors'));
    }

    // 7. PROSES UPDATE DATA
    public function update(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'po_number' => 'required', // Boleh sama kalau punya sendiri, jadi hapus unique
            'order_date' => 'required|date',
            'buyer_id' => 'required',
            'fabric_id' => 'required',
            'color_id' => 'required|array',
            'color_id.*' => 'required',
        ]);
        $tahun = date('y');
    
    // Cek: User isi buntutnya atau tidak?
    if ($request->mf_suffix) {
        $full_mf_number = "OK/" . $tahun . "/" . strtoupper($request->mf_suffix);
    } else {
        // Kalau kosong, biarkan null atau ambil data lama (sesuai kebutuhan)
        $full_mf_number = null; 
    }

        DB::transaction(function () use ($request, $id, $full_mf_number) {
            $order = Order::findOrFail($id);

            // 2. Update Header (Data Umum)
            $order->update([
                'po_number' => $request->po_number,
                'mf_number' => $full_mf_number,
                'order_date' => $request->order_date,
                'buyer_id' => $request->buyer_id,
                'fabric_id' => $request->fabric_id,
            ]);

            // 3. Update Detail (Cara: Hapus Lama -> Masukkan Baru)
            // Hapus semua rincian lama biar bersih
            $order->details()->delete();

            // Masukkan rincian baru dari form
            $colors = $request->color_id;
            foreach ($colors as $key => $colorId) {
                // Lewati jika baris kosong (jaga-jaga)
                if(!$colorId) continue;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'color_id' => $colorId,
                    'qty_om' => $request->qty_om[$key] ?? 0,
                    'batch_size' => $request->batch_size[$key] ?? 0,
                    'jml_batch' => $request->jml_batch[$key] ?? 0,
                    'jml_grey' => $request->jml_grey[$key] ?? 0,
                    'notes' => $request->notes[$key] ?? null,
                ]);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order berhasil diperbarui!');
    }
    // Fungsi Khusus Cetak Surat Jalan
    public function print($id)
    {
        // Ambil data order lengkap dengan relasinya
        $order = Order::with(['buyer', 'fabric', 'details.color'])->findOrFail($id);
        
        // Tampilkan file print.blade.php yang baru kita buat
        return view('orders.print', compact('order'));
    }
}