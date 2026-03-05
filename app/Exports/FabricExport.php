<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FabricExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data_kain;
    private $rowNumber = 0;

    // 1. Tangkap data dari Controller
    public function __construct($data_kain)
    {
        $this->data_kain = $data_kain;
    }

    public function collection()
    {
        // Kembalikan data yang sudah difilter Aktif tadi
        return $this->data_kain;
    }

    public function map($kain): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $kain->corak ?? '-',
            $kain->code_kain ?? '-',
            $kain->quality ?? '-',
            $kain->buyer_code ?? '-',
            $kain->brand ?? '-',
            $kain->construction ?? '-',
            $kain->density ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            "No", 
            "Corak", 
            "Kode Kain", 
            "Quality", 
            "Kode Buyer", 
            "Brand", 
            "Konstruksi", 
            "Density"
        ];
    }
}