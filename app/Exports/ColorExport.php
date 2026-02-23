<?php

namespace App\Exports;

use App\Models\Color;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Tambah ini

class ColorExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Color::select('id', 'name')->get();
    }

    public function headings(): array
    {
        return ["No", "Nama Warna"];
    }
}