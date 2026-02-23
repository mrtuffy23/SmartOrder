<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Exports\BuyerExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
    $query = Buyer::query();

    // Jika ada input pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('kode_buyer', 'like', "%{$search}%");
    }

    // Ambil data (diurutkan abjad, dan dipaginasi 10 per halaman)
    $buyers = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

    return view('buyers.index', compact('buyers'));
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'name' => 'required',
            'kode_buyer' => 'nullable|unique:buyers,kode_buyer'
        ]);

        Buyer::create($request->all());

        return back()->with('success', 'Buyer berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Buyer::find($id)->delete();
        return back()->with('success', 'Buyer dihapus!');
    }
    public function export()
    {
    return Excel::download(new BuyerExport, 'data-buyers.xlsx');
    }
    // TAMPILKAN HALAMAN EDIT
    public function edit($id)
    {
        $buyer = Buyer::findOrFail($id);
        return view('buyers.edit', compact('buyer'));
    }

    // SIMPAN PERUBAHAN
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'kode_buyer' => 'nullable'
        ]);

        $buyer = Buyer::findOrFail($id);
        $buyer->update($request->all());

        return redirect()->route('buyers.index')->with('success', 'Data Buyer berhasil diupdate!');
    }
}