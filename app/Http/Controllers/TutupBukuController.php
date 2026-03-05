<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TutupBuku;

class TutupBukuController extends Controller
{
    // Tampilkan halaman daftar tutup buku
    public function index()
    {
        $tutup_bukus = TutupBuku::orderBy('bulan', 'desc')->get();
        return view('tutup_buku.index', compact('tutup_bukus'));
    }

    // Proses Menutup Buku
    public function store(Request $request)
    {
        $request->validate(['bulan' => 'required']);

        // UpdateOrCreate: Jika bulan sudah ada di DB maka update jadi closed, jika belum buat baru
        TutupBuku::updateOrCreate(
            ['bulan' => $request->bulan],
            ['status' => 'closed']
        );

        return back()->with('success', 'Bulan ' . $request->bulan . ' berhasil DITUTUP! Semua transaksi di bulan ini terkunci.');
    }

    // Proses Membuka Buku (Revisi Darurat)
    public function open($id)
    {
        $tb = TutupBuku::findOrFail($id);
        $tb->update(['status' => 'open']);

        return back()->with('success', 'Bulan ' . $tb->bulan . ' berhasil DIBUKA KEMBALI. Transaksi bisa diedit lagi.');
    }
}