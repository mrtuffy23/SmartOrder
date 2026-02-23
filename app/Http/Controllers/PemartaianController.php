<?php

namespace App\Http\Controllers;

use App\Models\Pemartaian;
use App\Models\PemartaianDetail;
use App\Models\Fabric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemartaianController extends Controller
{
    // 1. TAMPILKAN DAFTAR PEMARTAIAN
    public function index(Request $request)
    {
        $query = Pemartaian::with('details.fabric')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('no_partai', 'like', "%{$search}%");
        }

        $pemartaians = $query->paginate(15)->withQueryString();
        return view('pemartaians.index', compact('pemartaians'));
    }

    // 2. TAMPILKAN FORM INPUT PEMARTAIAN BARU
    public function create()
    {
        // Ambil data kain untuk dropdown (Bisa dicari berdasarkan corak/kode)
        $fabrics = Fabric::orderBy('corak', 'asc')->get();
        
        // Bikin nomor partai otomatis (Format: PRT/Tahun/001)
        $tahun = date('y');
        $prefix = "PRT/$tahun/";
        $lastPartai = Pemartaian::where('no_partai', 'like', $prefix . '%')->latest('id')->first();
        
        $nomorBaru = 1;
        if ($lastPartai) {
            $pecah = explode('/', $lastPartai->no_partai);
            $nomorBaru = (int) end($pecah) + 1;
        }
        $auto_no_partai = $prefix . sprintf('%04d', $nomorBaru);

        return view('pemartaians.create', compact('fabrics', 'auto_no_partai'));
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

        DB::transaction(function () use ($request) {
            // A. Simpan Header Bukti Pemartaian
            $pemartaian = Pemartaian::create([
                'no_partai' => $request->no_partai,
                'tanggal' => $request->tanggal,
                'jenis_pengeluaran' => $request->jenis_pengeluaran,
                'keterangan' => $request->keterangan,
            ]);

            // B. Simpan Detail Kain yang Dikeluarkan
            $fabrics = $request->fabric_id;
            foreach ($fabrics as $key => $fabricId) {
                if(!$fabricId) continue;
                
                PemartaianDetail::create([
                    'pemartaian_id' => $pemartaian->id,
                    'no_order' => $request->no_order[$key] ?? null,
                    'fabric_id' => $fabricId,
                    'warna' => $request->warna[$key] ?? null,
                    'no_batch' => $request->no_batch[$key] ?? null,
                    'jml_gulung' => $request->jml_gulung[$key] ?? 0,
                    'total_meter' => $request->total_meter[$key] ?? 0,
                    'berat' => $request->berat[$key] ?? 0,
                ]);
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

    // 5. UPDATE DATA KE DATABASE
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_partai' => 'required|unique:pemartaians,no_partai,' . $id,
            'tanggal' => 'required|date',
            'fabric_id' => 'required|array',
            'fabric_id.*' => 'required',
        ]);

        DB::transaction(function () use ($request, $id) {
            $pemartaian = Pemartaian::findOrFail($id);

            // A. Update Header Bukti
            $pemartaian->update([
                'no_partai' => $request->no_partai,
                'tanggal' => $request->tanggal,
                'jenis_pengeluaran' => $request->jenis_pengeluaran,
                'keterangan' => $request->keterangan,
            ]);

            // B. Hapus rincian lama
            $pemartaian->details()->delete();

            // C. Masukkan rincian baru dari form
            $fabrics = $request->fabric_id;
            foreach ($fabrics as $key => $fabricId) {
                if(!$fabricId) continue;
                
                PemartaianDetail::create([
                    'pemartaian_id' => $pemartaian->id,
                    'no_order' => $request->no_order[$key] ?? null,
                    'fabric_id' => $fabricId,
                    'warna' => $request->warna[$key] ?? null,
                    'no_batch' => $request->no_batch[$key] ?? null,
                    'jml_gulung' => $request->jml_gulung[$key] ?? 0,
                    'total_meter' => $request->total_meter[$key] ?? 0,
                    'berat' => $request->berat[$key] ?? 0,
                ]);
            }
        });

        return redirect()->route('pemartaians.index')->with('success', 'Data Pemartaian berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $pemartaian = Pemartaian::findOrFail($id);
        $pemartaian->delete(); // Detail otomatis terhapus karena onDelete('cascade')

        return redirect()->route('pemartaians.index')->with('success', 'Data pemartaian berhasil dihapus!');
    }
}