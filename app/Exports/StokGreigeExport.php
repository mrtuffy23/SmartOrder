<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StokGreigeExport implements FromView, ShouldAutoSize
{
    protected $data_laporan; // Variabel untuk menampung data dari Controller

    // Menangkap data yang dilempar dari Controller
    public function __construct($data_laporan)
    {
        $this->data_laporan = $data_laporan;
    }

    public function view(): View
    {
        // Melempar data ke file Blade khusus Excel
        return view('laporan.stok_excel', [
            'laporan' => $this->data_laporan
        ]);
    }
}