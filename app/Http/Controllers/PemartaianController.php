<?php

namespace App\Http\Controllers;

use App\Models\Pemartaian;
use App\Models\PemartaianDetail;
use App\Models\Fabric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\PemartaianDetailExport;
use Maatwebsite\Excel\Facades\Excel;

class PemartaianController extends Controller
{
    // 1. TAMPILKAN DAFTAR PEMARTAIAN
    public function index(Request $request)
    {
        $query = Pemartaian::with(['details.fabric']);

        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$request->bulan]);
        }

        // Search by no_partai, jenis_pengeluaran, atau corak kain
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_partai', 'like', "%{$search}%")
                  ->orWhere('jenis_pengeluaran', 'like', "%{$search}%")
                  ->orWhereHas('details.fabric', function($q2) use ($search) {
                      $q2->where('corak', 'like', "%{$search}%");
                  })
                  ->orWhereHas('details', function($q2) use ($search) {
                      $q2->where('no_order', 'like', "%{$search}%");
                  });
            });
        }

        $pemartaians = $query->orderBy('tanggal', 'desc')->get();

        return view('pemartaians.index', compact('pemartaians'));
    }

    // 2. TAMPILKAN FORM INPUT PEMARTAIAN BARU
    public function create()
    {
        // 1. Logika Auto-Numbering No Partai
        $lastPemartaian = Pemartaian::orderBy('id', 'desc')->first();
        
        if (!$lastPemartaian) {
            $nextPartai = '00001';
        } else {
            $nextPartai = str_pad((int)$lastPemartaian->no_partai + 1, 5, '0', STR_PAD_LEFT);
        }

        // 2. Ambil data master
        $fabrics = Fabric::orderBy('corak', 'asc')->get();
        // $orders = Order::all(); // Sesuaikan jika kamu punya relasi order

        return view('pemartaians.create', compact('fabrics', 'nextPartai'));
    }

    // 3. SIMPAN DATA KE DATABASE (Master-Detail)
    public function store(Request $request)
    {
        $request->validate([
            'no_partai' => 'required|unique:pemartaians,no_partai',
            'tanggal' => 'required|date',
            'fabric_id' => 'required|array',
            'fabric_id.*' => 'required',
        ]);

        // 👇 KODE PENGECEKAN TUTUP BUKU 👇
        $bulan_transaksi = date('Y-m', strtotime($request->tanggal));
        $cek_tutup = \App\Models\TutupBuku::where('bulan', $bulan_transaksi)->where('status', 'closed')->first();
        if ($cek_tutup) {
            return redirect()->back()->with('error', 'AKSES DITOLAK! Data pada bulan ini sudah Tutup Buku.');
        }

        DB::transaction(function () use ($request) {
            // 1. Simpan data Induk (Pemartaian)
            $pemartaian = Pemartaian::create([
                'no_partai' => $request->no_partai, // Ini dapat dari auto-number form tadi
                'tanggal' => $request->tanggal,
                'jenis_pengeluaran' => $request->jenis_pengeluaran,
                'keterangan' => $request->ket_induk,
            ]);

            // 2. Logika Auto-Numbering BATCH (Ambil batch terakhir dari database)
            $lastBatchData = PemartaianDetail::orderBy('id', 'desc')->first();
            $currentBatchNumber = $lastBatchData ? (int)$lastBatchData->no_batch : 0;

            $tahun=date('y', strtotime($request->tanggal)); // Ambil tahun dari tanggal inputan
            // 3. Simpan Detail / Rincian Kain
            $orders = $request->no_order;
            if ($orders) {
                foreach ($orders as $key => $order) {
                    
                    $input_order = $request->no_order[$key];
                    $full_order = "OK/{$tahun}/" . $input_order;

                    PemartaianDetail::create([
                        'pemartaian_id' => $pemartaian->id,
                        'no_order'      => $full_order,
                        'fabric_id'     => $request->fabric_id[$key],
                        'warna'         => $request->warna[$key]?? null,
                        'no_batch'      => $request->no_batch[$key], 
                        'jml_gulung'    => $request->jml_gulung[$key],
                        'total_meter'   => $request->total_meter[$key],
                        'berat'         => $request->berat[$key],
                        'keterangan'    => $request->keterangan[$key] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('pemartaians.index')->with('success', 'Data Pemartaian berhasil disimpan!');
    }
    // 4. TAMPILKAN FORM EDIT
    public function edit($id)
    {
        $pemartaian = Pemartaian::with('details')->findOrFail($id);
        $fabrics = Fabric::orderBy('corak', 'asc')->get();
        
        return view('pemartaians.edit', compact('pemartaian', 'fabrics'));
    }

    // MENYIMPAN PERUBAHAN EDIT
    public function update(Request $request, $id)
    {
        // 👇 KODE PENGECEKAN TUTUP BUKU 👇
        $pemartaian_lama = Pemartaian::findOrFail($id);

        // Cek bulan LAMA (data asli) apakah sudah ditutup
        $bulan_lama = date('Y-m', strtotime($pemartaian_lama->tanggal));
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
            $pemartaian = Pemartaian::findOrFail($id);
            
            // 1. Update Induk Pemartaian (No Partai dikunci, tidak diupdate)
            $pemartaian->update([
                'tanggal' => $request->tanggal,
                'jenis_pengeluaran' => $request->jenis_pengeluaran,
                'keterangan' => $request->keterangan, // Keterangan Induk
            ]);

            $detail_ids = $request->detail_id ?? [];

            // 2. HAPUS rincian kain yang diklik tong sampah di form Edit
            $pemartaian->details()->whereNotIn('id', array_filter($detail_ids))->delete();

            // 3. UPDATE Rincian Lama ATAU CREATE Rincian Baru
            $orders = $request->no_order;
            if ($orders) {
                foreach ($orders as $key => $order) {
                    
                    if (!empty($detail_ids[$key])) {
                        // JIKA ADA ID -> UPDATE BARIS LAMA
                        PemartaianDetail::where('id', $detail_ids[$key])->update([
                            'no_order'   => $request->no_order[$key],
                            'fabric_id'  => $request->fabric_id[$key],
                            'warna'      => $request->warna[$key]?? null,
                            'no_batch'      => $request->no_batch[$key],
                            'jml_gulung' => $request->jml_gulung[$key],
                            'total_meter'=> $request->total_meter[$key],
                            'berat'      => $request->berat[$key],
                            'keterangan' => $request->ket_detail[$key] ?? null, // Menggunakan ket_detail
                        ]);
                    } else {
                        // JIKA TIDAK ADA ID -> TAMBAH BARIS BARU (CREATE)
                

                        PemartaianDetail::create([
                            'pemartaian_id' => $pemartaian->id,
                            'no_order'   => $request->no_order[$key],
                            'fabric_id'  => $request->fabric_id[$key],
                            'warna'      => $request->warna[$key],
                            'no_batch'      => $request->no_batch[$key], // Dibuatkan Batch baru otomatis
                            'jml_gulung' => $request->jml_gulung[$key],
                            'total_meter'=> $request->total_meter[$key],
                            'berat'      => $request->berat[$key],
                            'keterangan' => $request->ket_detail[$key] ?? null, // Menggunakan ket_detail
                        ]);
                    }
                }
            }
        });

        return redirect()->route('pemartaians.index')->with('success', 'Data Pemartaian berhasil diupdate!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $pemartaian = Pemartaian::findOrFail($id);

        // 👇 KODE PENGECEKAN TUTUP BUKU 👇
        $bulan_transaksi = date('Y-m', strtotime($pemartaian->tanggal));
        $cek_tutup = \App\Models\TutupBuku::where('bulan', $bulan_transaksi)->where('status', 'closed')->first();
        if ($cek_tutup) {
            return redirect()->back()->with('error', 'AKSES DITOLAK! Data pada bulan ini sudah Tutup Buku.');
        }

        $pemartaian->delete(); // Detail otomatis terhapus karena onDelete('cascade')

        return redirect()->route('pemartaians.index')->with('success', 'Data pemartaian berhasil dihapus!');
    }

    // MENAMPILKAN LAPORAN DETAIL PEMARTAIAN (Dengan Search & Export Excel)
    public function details(Request $request)
    {
        // 1. Query Dasar (Join agar bisa dicari berdasarkan tabel lain)
        $query = \App\Models\PemartaianDetail::with(['pemartaian', 'fabric'])
            ->select('pemartaian_details.*')
            ->join('pemartaians', 'pemartaians.id', '=', 'pemartaian_details.pemartaian_id')
            ->join('fabrics', 'fabrics.id', '=', 'pemartaian_details.fabric_id');

        // 2. FITUR PENCARIAN (Berdasarkan Partai, Corak, Order, atau Batch)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pemartaians.no_partai', 'LIKE', "%{$search}%")
                  ->orWhere('fabrics.corak', 'LIKE', "%{$search}%")
                  ->orWhere('pemartaian_details.no_order', 'LIKE', "%{$search}%")
                  ->orWhere('pemartaian_details.no_batch', 'LIKE', "%{$search}%");
            });
        }

        // Urutkan data dari yang terbaru lalu berdasarkan nomor batch
        $query->orderBy('pemartaians.tanggal', 'desc')
              ->orderBy('pemartaians.no_partai', 'desc')
              ->orderBy('pemartaian_details.no_batch', 'asc');
              
        // 3. ⚡ TANGKAP TOMBOL EXPORT DI SINI ⚡
        // Jika parameter 'export' nilainya 'excel', gunakan Laravel Excel (Maatwebsite)
        if ($request->has('export') && $request->export == 'excel') {
            // Karena kita menggunakan file PemartaianDetailExport.php yang terpisah, 
            // kita panggil Class-nya untuk mendownload file .xlsx
            $fileName = 'Laporan_Detail_Pemartaian_' . date('d_m_Y') . '.xlsx';
            return Excel::download(new PemartaianDetailExport, $fileName);
        }

        // 4. Jika bukan export, maka tampilkan ke halaman website (View)
        $details = $query->paginate(20); // Gunakan paginate agar halaman tidak berat jika data ribuan

        return view('pemartaians.details', compact('details'));
    }

    // FUNGSI exportExcel() YANG LAMA BOLEH DIHAPUS SAJA KARENA SUDAH DIGABUNG DI ATAS
    
    // Fungsi pencegah error Route Show
    public function show($id)
    {
        return redirect()->route('pemartaians.index');
    }

}