<?php

namespace App\Http\Controllers;

use App\Models\OrderRecipe;
use App\Models\OrderRecipeDyestuff;
use App\Models\OrderRecipeChemical;
use App\Models\Order;
use App\Models\Dyestuff;
use App\Models\Chemical;
use Illuminate\Http\Request;

class OrderRecipeController extends Controller
{
    // Tampilkan Daftar Resep Original
    public function index(Request $request)
    {
        $search = $request->search;

        // Tarik data resep beserta relasi order dan warna
        $recipes = \App\Models\OrderRecipe::with(['order', 'color'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('order', function ($q) use ($search) {
                    $q->where('mf_number', 'LIKE', "%{$search}%");
                })->orWhereHas('color', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('order_recipes.index', compact('recipes'));
    }

    // Tampilkan Form Buat Resep Baru
    public function create()
    {
        $orders = Order::orderBy('id', 'desc')->get();
        $dyestuffs = Dyestuff::where('is_active', 1)->orderBy('name', 'asc')->get();
        $chemicals = Chemical::where('is_active', 1)->orderBy('name', 'asc')->get();

        return view('order_recipes.create', compact('orders', 'dyestuffs', 'chemicals'));
    }

    // Simpan Data Resep ke Database
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'color_id' => 'required',
        ]);

        // Cek apakah Order & Warna ini sudah punya resep sebelumnya
        $exists = OrderRecipe::where('order_id', $request->order_id)
                             ->where('color_id', $request->color_id)->first();
        if($exists) {
            return back()->with('error', 'Gagal! Resep untuk Order dan Warna ini sudah pernah dibuat.');
        }

        // 1. Simpan Induk Resep
        $recipe = OrderRecipe::create([
            'order_id' => $request->order_id,
            'color_id' => $request->color_id,
        ]);

        // 2. Simpan Rincian Zat Warna (Dyestuffs)
        if ($request->dyestuff_id) {
            foreach ($request->dyestuff_id as $key => $dye_id) {
                if ($dye_id) {
                    OrderRecipeDyestuff::create([
                        'order_recipe_id' => $recipe->id,
                        'dyestuff_id' => $dye_id,
                        'concentration' => $request->dye_concentration[$key] ?? 0,
                    ]);
                }
            }
        }

        // 3. Simpan Rincian Bahan Kimia (Chemicals)
        if ($request->chemical_id) {
            foreach ($request->chemical_id as $key => $chem_id) {
                if ($chem_id) {
                    OrderRecipeChemical::create([
                        'order_recipe_id' => $recipe->id,
                        'chemical_id' => $chem_id,
                        'concentration' => $request->chem_concentration[$key] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('order-recipes.index')->with('success', 'Buku Resep Original berhasil disimpan!');
    }
    // Hapus Resep Original
    public function destroy($id)
    {
        OrderRecipe::findOrFail($id)->delete();
        return back()->with('success', 'Resep berhasil dihapus!');
    }
}