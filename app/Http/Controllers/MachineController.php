<?php
namespace App\Http\Controllers;
use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index() {
        $machines = Machine::orderBy('name', 'asc')->get();
        return view('machines.index', compact('machines'));
    }
    public function store(Request $request) {
        Machine::create($request->all());
        return back()->with('success', 'Mesin berhasil ditambahkan!');
    }
    public function update(Request $request, $id) {
        Machine::findOrFail($id)->update($request->all());
        return back()->with('success', 'Mesin berhasil diupdate!');
    }
    public function destroy($id) {
        Machine::findOrFail($id)->delete();
        return back()->with('success', 'Mesin dihapus!');
    }
}