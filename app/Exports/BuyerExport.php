<?php

namespace App\Exports;

use App\Models\Buyer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Tambah ini

class BuyerExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Buyer::select('id', 'name', 'kode_buyer')->get();
    }

    public function headings(): array
    {
        return ["No", "Nama Buyer", "Kode Buyer"];
    }
}