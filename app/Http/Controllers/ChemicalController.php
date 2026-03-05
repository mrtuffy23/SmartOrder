<?php
namespace App\Http\Controllers;
use App\Models\Chemical;
use Illuminate\Http\Request;

class ChemicalController extends Controller
{
    public function index() {
        $chemicals = Chemical::orderBy('name', 'asc')->get();
        return view('chemicals.index', compact('chemicals'));
    }
    public function store(Request $request) {
        Chemical::create($request->all());
        return back()->with('success', 'Bahan Kimia berhasil ditambahkan!');
    }
    public function update(Request $request, $id) {
        Chemical::findOrFail($id)->update($request->all());
        return back()->with('success', 'Bahan Kimia berhasil diupdate!');
    }
    public function destroy($id) {
        Chemical::findOrFail($id)->delete();
        return back()->with('success', 'Bahan Kimia dihapus!');
    }
}