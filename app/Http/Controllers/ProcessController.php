<?php

namespace App\Http\Controllers;

use App\Models\Process;
use App\Models\Chemical;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function index() 
    {
        // Panggil Proses beserta resep obatnya
        $processes = Process::with('chemicals')->orderBy('name', 'asc')->get();
        // Ambil data Bahan Kimia yang aktif untuk pilihan Dropdown
        $chemicals = Chemical::where('is_active', 1)->orderBy('name', 'asc')->get(); 
        
        return view('processes.index', compact('processes', 'chemicals'));
    }

    public function store(Request $request) 
    {
        $process = Process::create([
            'name' => $request->name,
            'is_active' => $request->is_active
        ]);

        // Simpan "Paket Obat" jika ditambahkan
        if ($request->has('chemical_id')) {
            foreach ($request->chemical_id as $key => $chem_id) {
                if ($chem_id) {
                    $process->chemicals()->attach($chem_id, ['concentration' => $request->concentration[$key]]);
                }
            }
        }

        return back()->with('success', 'Proses beserta resepnya berhasil ditambahkan!');
    }

    public function update(Request $request, $id) 
    {
        $process = Process::findOrFail($id);
        $process->update([
            'name' => $request->name,
            'is_active' => $request->is_active
        ]);

        // Reset resep lama, lalu masukkan resep yang baru diupdate
        $process->chemicals()->detach();
        if ($request->has('chemical_id')) {
            foreach ($request->chemical_id as $key => $chem_id) {
                if ($chem_id) {
                    $process->chemicals()->attach($chem_id, ['concentration' => $request->concentration[$key]]);
                }
            }
        }

        return back()->with('success', 'Proses dan resep berhasil diupdate!');
    }

    public function destroy($id) 
    {
        Process::findOrFail($id)->delete();
        return back()->with('success', 'Proses dihapus!');
    }
}