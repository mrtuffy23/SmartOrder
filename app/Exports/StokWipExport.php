<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StokWipExport implements FromView, ShouldAutoSize
{
    protected $data_laporan;

    public function __construct($data_laporan)
    {
        $this->data_laporan = $data_laporan;
    }

    public function view(): View
    {
        // Melempar data ke file Blade HTML khusus Excel
        return view('wip.wip_excel', [
            'laporan_wip' => $this->data_laporan
        ]);
    }
}