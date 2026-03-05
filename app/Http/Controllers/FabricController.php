<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use Illuminate\Http\Request;
use App\Exports\FabricExport;
use Maatwebsite\Excel\Facades\Excel;

class FabricController extends Controller
{
    public function index(Request $request)
    {
        $query = Fabric::query();

        // Jika ada request pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            // Gunakan kurung pengelompokan agar filter pencarian akurat
            $query->where(function($q) use ($search) {
                $q->where('corak', 'like', "%{$search}%")
                  ->orWhere('code_kain', 'like', "%{$search}%")
                  ->orWhere('quality', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('buyer_code', 'like', "%{$search}%");
            });
        }

        // Ambil data (gunakan paginate agar rapi jika data sudah ratusan)
        $fabrics = $query->orderBy('corak', 'asc')->paginate(15)->withQueryString();

        return view('fabrics.index', compact('fabrics'));
    }

    public function store(Request $request)
    {
        // Validasi Corak wajib diisi
        $request->validate(['corak' => 'required']);
        
        Fabric::create($request->all());
        return back()->with('success', 'Data Kain berhasil disimpan!');
    }

    public function destroy($id)
    {
        Fabric::find($id)->delete();
        return back()->with('success', 'Data Kain dihapus!');
    }

    // =======================================================
    // ⚡ FUNGSI EXPORT YANG SUDAH DIPERBARUI ⚡
    // =======================================================
    public function export()
    {
        // Hanya tarik data kain yang statusnya Aktif (is_active = 1)
        $kain_aktif = Fabric::where('is_active', 1)->orderBy('corak', 'asc')->get();
        
        // Lempar data tersebut ke class FabricExport
        return Excel::download(new FabricExport($kain_aktif), 'Data_Kain_Aktif.xlsx');
    }

    // TAMPILKAN HALAMAN EDIT
    public function edit($id)
    {
        $fabrics = Fabric::findOrFail($id);
        return view('fabrics.edit', compact('fabrics'));
    }

    // SIMPAN PERUBAHAN
    public function update(Request $request, $id)
    {
        $request->validate([
            'corak' => 'required',
            'code_kain' => 'nullable',
            'quality' => 'nullable',
            'buyer_code' => 'nullable',
            'brand' => 'nullable',
            'construction' => 'nullable',
            'density' => 'nullable',
            'is_active' => 'nullable', // Menangkap status aktif/non-aktif
        ]);

        $fabrics = Fabric::findOrFail($id);
        $fabrics->update($request->all());

        return redirect()->route('fabrics.index')->with('success', 'Data Kain berhasil diupdate!');
    }
}