<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retur;
use App\Models\Fabric;

class ReturController extends Controller
{
    // 1. TAMPILKAN DAFTAR RETUR
    public function index()
    {
        $returs = Retur::with('fabric')->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();
        return view('returs.index', compact('returs'));
    }

    // 2. FORM TAMBAH RETUR
    public function create()
    {
        // Logika Auto-Numbering No. Retur (5 Digit)
        $lastRetur = Retur::orderBy('id', 'desc')->first();
        if (!$lastRetur) {
            $nextRetur = '00001';
        } else {
            $nextRetur = str_pad((int)$lastRetur->no_retur + 1, 5, '0', STR_PAD_LEFT);
        }

        $fabrics = Fabric::orderBy('corak', 'asc')->get();
        return view('returs.create', compact('nextRetur', 'fabrics'));
    }

    // 3. SIMPAN DATA RETUR
    public function store(Request $request)
    {
        $request->validate([
            'no_retur'    => 'required|unique:returs',
            'tanggal'     => 'required|date',
            'fabric_id'   => 'required|exists:fabrics,id',
            'total_meter' => 'required|numeric|min:0.1',
        ]);

        Retur::create([
            'no_retur'    => $request->no_retur,
            'tanggal'     => $request->tanggal,
            'fabric_id'   => $request->fabric_id,
            'total_meter' => $request->total_meter,
            'keterangan'  => $request->keterangan,
        ]);

        return redirect()->route('returs.index')->with('success', 'Data Retur Barang berhasil dicatat!');
    }

    // 4. HAPUS DATA RETUR
    public function destroy($id)
    {
        $retur = Retur::findOrFail($id);
        $retur->delete();

        return redirect()->route('returs.index')->with('success', 'Data Retur berhasil dihapus!');
    }
}