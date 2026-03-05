<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StokWipIndexExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;
    private $rowNumber = 0;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($item): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $item->no_order ?? '-',
            $item->fabric->corak ?? '-',
            $item->no_batch ?? '-',
            $item->jml_gulung ?? 0,
            number_format($item->sisa_meter_aktual ?? 0, 0, '.', ''),
            $item->keterangan ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            "No",
            "No. Order",
            "Corak",
            "Batch",
            "Jml. Gulung",
            "Meter",
            "Keterangan",
        ];
    }
}
