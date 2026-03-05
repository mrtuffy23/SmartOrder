<?php

namespace App\Http\Controllers;

use App\Models\Dyestuff;
use Illuminate\Http\Request;

class DyestuffController extends Controller
{
    public function index()
    {
        $dyestuffs = Dyestuff::orderBy('name', 'asc')->get();
        return view('dyestuffs.index', compact('dyestuffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'active_code' => 'required|unique:dyestuffs,active_code',
            'name' => 'required'
        ]);

        Dyestuff::create($request->all());
        return redirect()->back()->with('success', 'Zat warna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $dyestuff = Dyestuff::findOrFail($id);
        $dyestuff->update($request->all());

        return redirect()->back()->with('success', 'Zat warna berhasil diupdate!');
    }

    public function destroy($id)
    {
        Dyestuff::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Zat warna berhasil dihapus!');
    }
}