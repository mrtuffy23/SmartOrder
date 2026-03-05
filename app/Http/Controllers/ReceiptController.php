<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptDetail;
use App\Models\Fabric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ReceiptDetailExport;
use Maatwebsite\Excel\Facades\Excel;

class ReceiptController extends Controller
{
    // 1. TAMPILKAN DAFTAR PENERIMAAN
    public function index(Request $request)
    {
        $query = Receipt::with(['details.fabric']);

        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$request->bulan]);
        }

        // Search by no_bukti, terima, corak kain, atau no_order
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_bukti', 'like', "%{$search}%")
                  ->orWhere('terima', 'like', "%{$search}%")
                  ->orWhereHas('details.fabric', function($q2) use ($search) {
                      $q2->where('corak', 'like', "%{$search}%");
                  })
                  ->orWhereHas('details', function($q2) use ($search) {
                      $q2->where('no_order', 'like', "%{$search}%");
                  });
            });
        }

        $receipts = $query->orderBy('tanggal', 'desc')->get();
        return view('receipts.index', compact('receipts'));
    }

    // 2. TAMPILKAN FORM PENERIMAAN BARU
    public function create()
    {
        // 1. Logika Auto-Numbering No Bukti
        $lastReceipt = Receipt::orderBy('id', 'desc')->first();
        
        if (!$lastReceipt) {
            $nextBukti = '00001'; // Jika database masih kosong
        } else {
            // Ambil nomor sebelumnya, jadikan angka, tambah 1, lalu cetak dengan format 5 digit (00002)
            $nextBukti = str_pad((int)$lastReceipt->no_bukti + 1, 5, '0', STR_PAD_LEFT);
        }

        // 2. Ambil data master untuk dropdown
        $fabrics = Fabric::orderBy('corak', 'asc')->get();

        // 3. Lempar variabel $nextBukti ke halaman form Create
        return view('receipts.create', compact('fabrics', 'nextBukti'));
    }

    // 3. SIMPAN DATA KE DATABASE (Master-Detail)
    public function store(Request $request)
    {
        // 👇 KODE PENGECEKAN TUTUP BUKU 👇
        $bulan_transaksi = date('Y-m', strtotime($request->tanggal));
        $cek_tutup = \App\Models\TutupBuku::where('bulan', $bulan_transaksi)->where('status', 'closed')->first();
        if ($cek_tutup) {
            return redirect()->back()->with('error', 'AKSES DITOLAK! Data pada bulan ini sudah Tutup Buku.');
        }

        DB::transaction(function () use ($request) {
            $tahun = date('Y', strtotime($request->tanggal));

            // 1. Simpan Induk
            $receipt = Receipt::create([
                'no_bukti' => $request->no_bukti,
                'tanggal'  => $request->tanggal,
                'terima'   => $request->terima,
            ]);

            // 2. Simpan Rincian
            $fabrics = $request->fabric_id;
            if ($fabrics) {
                foreach ($fabrics as $key => $fabric) {
                    $input_order = $request->no_order[$key] ?? null;
                    $full_no_order= $input_order ? "OK/" . $tahun . "/" . $input_order : null;
                    ReceiptDetail::create([
                        'receipt_id'  => $receipt->id,
                        'fabric_id'   => $request->fabric_id[$key],
                        'total_meter' => $request->total_meter[$key],
                        'no_order'    => $full_no_order,
                        'keterangan'  => $request->keterangan[$key],
                    ]);
                }
            }
        });

        return redirect()->route('receipts.index')->with('success', 'Data Penerimaan berhasil disimpan!');
    }
    // TAMPILKAN DETAIL PENERIMAAN
    public function show($id)
    {
        $receipt = Receipt::with(['details.fabric'])->findOrFail($id);
        return view('receipts.show', compact('receipt'));
    }

    // TAMPILKAN FORM EDIT
    public function edit($id)
    {
        $receipt = Receipt::with('details')->findOrFail($id);
        $fabrics = Fabric::orderBy('corak', 'asc')->get();
        
        return view('receipts.edit', compact('receipt', 'fabrics'));
    }

    // SIMPAN PERUBAHAN EDIT
    public function update(Request $request, $id)
    {
        // 👇 KODE PENGECEKAN TUTUP BUKU 👇
        $receipt_lama = Receipt::findOrFail($id);

        // Cek bulan LAMA (data asli) apakah sudah ditutup
        $bulan_lama = date('Y-m', strtotime($receipt_lama->tanggal));
        $cek_lama = \App\Models\TutupBuku::where('bulan', $bulan_lama)->where('status', 'closed')->first();
        if ($cek_lama) {
            return redirect()->back()->with('error', 'AKSES DITOLAK! Data pada bulan ini sudah Tutup Buku.');
        }

        // Cek bulan BARU (jika admin memindah tanggal ke bulan lain yang sudah dikunci)
        $bulan_baru = date('Y-m', strtotime($request->tanggal));
        if ($bulan_baru !== $bulan_lama) {
            $cek_baru = \App\Models\TutupBuku::where('bulan', $bulan_baru)->where('status', 'closed')->exists();
            if ($cek_baru) {
                return redirect()->back()->with('error', 'AKSES DITOLAK! Anda tidak bisa memindahkan data ke bulan yang sudah Tutup Buku.');
            }
        }

        DB::transaction(function () use ($request, $id) {

            $tahun = date('Y', strtotime($request->tanggal));
            $receipt = Receipt::findOrFail($id);

            // 1. Update Induk Penerimaan (No Bukti sengaja tidak di-update karena itu patokan permanen)
            $receipt->update([
                'tanggal' => $request->tanggal,
                'terima'  => $request->terima,
            ]);

            // Ambil array detail_id dari form
            $detail_ids = $request->detail_id ?? [];

            // 2. HAPUS rincian di database yang ID-nya TIDAK ADA di form (karena diklik tong sampah)
            $receipt->details()->whereNotIn('id', array_filter($detail_ids))->delete();

            // 3. UPDATE data rincian lama atau CREATE rincian baru
            $fabrics = $request->fabric_id;
            if ($fabrics) {
                foreach ($fabrics as $key => $fabricId) {
                    $input_order = $request->no_order[$key] ?? null;
                    $full_no_order= $input_order ? "OK/" . $tahun . "/" . $input_order : null;
                    if (!empty($detail_ids[$key])) {
                        ReceiptDetail::where('id', $detail_ids[$key])->update([
                            'fabric_id'   => $request->fabric_id[$key],
                            'total_meter' => $request->total_meter[$key],
                            'no_order'    => $full_no_order,
                            'keterangan'  => $request->keterangan[$key],
                        ]);
                    } 
                    // Jika tidak punya ID, berarti baris baru (Tambah Baris) -> CREATE
                    else {
                        ReceiptDetail::create([
                            'receipt_id'  => $receipt->id,
                            'fabric_id'   => $request->fabric_id[$key],
                            'total_meter' => $request->total_meter[$key],
                            'no_order'    => $full_no_order,
                            'keterangan'  => $request->keterangan[$key],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('receipts.index')->with('success', 'Data Penerimaan berhasil diupdate!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $receipt = Receipt::findOrFail($id);
        
        // 👇 KODE PENGECEKAN TUTUP BUKU 👇
        $bulan_transaksi = date('Y-m', strtotime($receipt->tanggal));
        $cek_tutup = \App\Models\TutupBuku::where('bulan', $bulan_transaksi)->where('status', 'closed')->first();
        if ($cek_tutup) {
            return redirect()->back()->with('error', 'AKSES DITOLAK! Data pada bulan ini sudah Tutup Buku.');
        }

        // Cukup hapus induknya.
        // Detail/rinciannya otomatis terhapus karena di database sudah kita set onDelete('cascade')
        $receipt->delete();

        return redirect()->route('receipts.index')->with('success', 'Data bukti penerimaan berhasil dihapus!');
    }
    // MENAMPILKAN LAPORAN DETAIL PENERIMAAN (Dengan Search & Export Excel)
    // MENAMPILKAN LAPORAN DETAIL PENERIMAAN (Dengan Search & Export Excel)
    public function details(Request $request)
    {
        // 1. Query Dasar (Gunakan Join agar bisa dicari berdasarkan tabel lain)
        $query = \App\Models\ReceiptDetail::with(['receipt', 'fabric'])
            ->select('receipt_details.*')
            ->join('receipts', 'receipts.id', '=', 'receipt_details.receipt_id')
            ->join('fabrics', 'fabrics.id', '=', 'receipt_details.fabric_id');

        // 2. FITUR PENCARIAN (Berdasarkan No Bukti, Corak, atau No Order)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('receipts.no_bukti', 'LIKE', "%{$search}%")
                  ->orWhere('fabrics.corak', 'LIKE', "%{$search}%")
                  ->orWhere('receipt_details.no_order', 'LIKE', "%{$search}%")
                  ->orWhere('receipts.terima', 'LIKE', "%{$search}%");
            });
        }

        // Urutkan data dari yang terbaru
        $query->orderBy('receipts.tanggal', 'desc')->orderBy('receipts.no_bukti', 'desc');

        // 3. ⚡ TANGKAP TOMBOL EXPORT EXCEL ⚡
        if ($request->has('export') && $request->export == 'excel') {
            $fileName = 'Laporan_Detail_Penerimaan_' . date('d_m_Y') . '.xlsx';
            return Excel::download(new ReceiptDetailExport, $fileName);
        }

        // 4. Tampilkan ke Halaman Web (menggunakan Paginate agar tidak berat)
        $details = $query->paginate(20);

        return view('receipts.details', compact('details'));
    }
}