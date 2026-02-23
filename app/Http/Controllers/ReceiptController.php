<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptDetail;
use App\Models\Fabric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    // 1. TAMPILKAN DAFTAR PENERIMAAN
    public function index(Request $request)
    {
        $query = Receipt::with('details.fabric')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('no_bukti', 'like', "%{$search}%")
                  ->orWhere('terima_dari', 'like', "%{$search}%");
        }

        $receipts = $query->paginate(15)->withQueryString();
        return view('receipts.index', compact('receipts'));
    }

    // 2. TAMPILKAN FORM PENERIMAAN BARU
    public function create()
    {
        // Ambil data kain untuk dropdown
        $fabrics = Fabric::orderBy('corak', 'asc')->get();
        
        // Bikin nomor bukti otomatis (Format: TBM/Tahun/001 -> Terima Barang Masuk)
        $tahun = date('y');
        $prefix = "TBM/$tahun/";
        $lastReceipt = Receipt::where('no_bukti', 'like', $prefix . '%')->latest('id')->first();
        
        $nomorBaru = 1;
        if ($lastReceipt) {
            $pecah = explode('/', $lastReceipt->no_bukti);
            $nomorBaru = (int) end($pecah) + 1;
        }
        $auto_no_bukti = $prefix . sprintf('%04d', $nomorBaru);

        return view('receipts.create', compact('fabrics', 'auto_no_bukti'));
    }

    // 3. SIMPAN DATA KE DATABASE (Master-Detail)
    public function store(Request $request)
    {
        $request->validate([
            'no_bukti' => 'required|unique:receipts,no_bukti',
            'tgl_terima' => 'required|date',
            'fabric_id' => 'required|array',
            'fabric_id.*' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            // A. Simpan Header (Tabel receipts)
            $receipt = Receipt::create([
                'no_bukti' => $request->no_bukti,
                'tgl_terima' => $request->tgl_terima,
                'terima_dari' => $request->terima_dari,
                'keterangan' => $request->keterangan,
            ]);

            // B. Simpan Detail Kain (Tabel receipt_details)
            $fabrics = $request->fabric_id;
            foreach ($fabrics as $key => $fabricId) {
                if(!$fabricId) continue;
                
                ReceiptDetail::create([
                    'receipt_id' => $receipt->id,
                    'fabric_id' => $fabricId,
                    'total_meter' => $request->total_meter[$key] ?? 0,
                    'no_order' => $request->no_order[$key] ?? null,
                    'jml_batch' => $request->jml_batch[$key] ?? 0,
                ]);
            }
        });

        return redirect()->route('receipts.index')->with('success', 'Penerimaan kain berhasil disimpan!');
    }
    // 4. TAMPILKAN FORM EDIT
    public function edit($id)
    {
        // Ambil data Penerimaan beserta rincian kainnya
        $receipt = Receipt::with('details')->findOrFail($id);
        
        // Ambil data master kain untuk dropdown
        $fabrics = Fabric::orderBy('corak', 'asc')->get();

        return view('receipts.edit', compact('receipt', 'fabrics'));
    }

    // 5. UPDATE DATA KE DATABASE
    public function update(Request $request, $id)
    {
        $request->validate([
            // Validasi no_bukti boleh sama DENGAN DIRINYA SENDIRI (pakai id)
            'no_bukti' => 'required|unique:receipts,no_bukti,' . $id,
            'tgl_terima' => 'required|date',
            'fabric_id' => 'required|array',
            'fabric_id.*' => 'required',
        ]);

        DB::transaction(function () use ($request, $id) {
            $receipt = Receipt::findOrFail($id);

            // A. Update Data Header (Tabel receipts)
            $receipt->update([
                'no_bukti' => $request->no_bukti,
                'tgl_terima' => $request->tgl_terima,
                'terima_dari' => $request->terima_dari,
                'keterangan' => $request->keterangan,
            ]);

            // B. Hapus semua detail lama
            $receipt->details()->delete();

            // C. Masukkan detail yang baru (dari form edit)
            $fabrics = $request->fabric_id;
            foreach ($fabrics as $key => $fabricId) {
                if(!$fabricId) continue; // Skip jika baris kosong
                
                ReceiptDetail::create([
                    'receipt_id' => $receipt->id,
                    'fabric_id' => $fabricId,
                    'total_meter' => $request->total_meter[$key] ?? 0,
                    'no_order' => $request->no_order[$key] ?? null,
                    'jml_batch' => $request->jml_batch[$key] ?? 0,
                ]);
            }
        });

        return redirect()->route('receipts.index')->with('success', 'Data penerimaan kain berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $receipt = Receipt::findOrFail($id);
        
        // Cukup hapus induknya. 
        // Detail/rinciannya otomatis terhapus karena di database sudah kita set onDelete('cascade')
        $receipt->delete();

        return redirect()->route('receipts.index')->with('success', 'Data bukti penerimaan berhasil dihapus!');
    }
}