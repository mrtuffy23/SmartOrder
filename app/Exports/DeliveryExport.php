<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class DeliveryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data_delivery;
    private $rowNumber = 0;

    // Menangkap data yang dilempar dari Controller
    public function __construct($data_delivery)
    {
        $this->data_delivery = $data_delivery;
    }

    public function collection()
    {
        return $this->data_delivery;
    }

    public function map($detail): array
    {
        $this->rowNumber++;

        // Format tanggal dari tabel induk (Delivery)
        $tanggal = $detail->delivery && $detail->delivery->tanggal 
                    ? Carbon::parse($detail->delivery->tanggal)->translatedFormat('d M Y') 
                    : '-';

        return [
            $this->rowNumber,
            $tanggal,
            $detail->buyer->name ?? '-',
            $detail->no_order ?? '-',
            $detail->pemartaianDetail->fabric->corak ?? '-',
            $detail->color->name ?? '-',
            $detail->pemartaianDetail->no_batch ?? '-',
            $detail->pemartaianDetail->jml_gulung ?? 0,
            $detail->pemartaianDetail->total_meter ?? 0,
            $detail->no_roda ?? '-',
            $detail->keterangan ?? '-',
        ];
    }

    public function headings(): array
    {
        // Header disesuaikan dengan gambar Data Delivery milikmu
        return [
            "No", 
            "Tanggal", 
            "Buyer", 
            "No.Order", 
            "Corak", 
            "Warna", 
            "Batch", 
            "Rol", 
            "Meter", 
            "Roda", 
            "Keterangan"
        ];
    }
}