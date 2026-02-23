<?php

namespace App\Exports;

use App\Models\Fabric;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Tambah ini

class FabricExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Fabric::select('id','corak','code_kain','quality','buyer_code','brand', 'construction', 'density')->get();
    }

    public function headings(): array
    {
        return ["No", "Corak", "Kode Kain", "Kualitas", "Kode Buyer", "Brand", "Konstruksi", "Density"];
    }
}