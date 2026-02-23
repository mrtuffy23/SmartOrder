<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Exports\ColorExport;
use Maatwebsite\Excel\Facades\Excel;

class ColorController extends Controller
{
    public function index(Request $request)
    {
    $query = Color::query();

    // Jika ada input pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('name', 'like', "%{$search}%");
    }

    // Ambil data (diurutkan sesuai abjad, dan dipaginasi 10 per halaman)
    // withQueryString() berguna agar saat kita pindah halaman 2, hasil pencariannya tidak hilang
    $colors = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

    return view('colors.index', compact('colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:colors,name' // Wajib isi & Gak boleh kembar
        ]);

        Color::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Warna baru berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Color::find($id)->delete();
        return back()->with('success', 'Warna berhasil dihapus!');
    }
    public function export()
    {
        return Excel::download(new ColorExport, 'colors.xlsx');
    }
    // TAMPILKAN HALAMAN EDIT
public function edit($id)
{
    $color = Color::findOrFail($id);
    return view('colors.edit', compact('color'));
}

// SIMPAN PERUBAHAN
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $color = Color::findOrFail($id);
        $color->update($request->all());

        return redirect()->route('colors.index')->with('success', 'Data Warna berhasil diupdate!');
    }
}
    
