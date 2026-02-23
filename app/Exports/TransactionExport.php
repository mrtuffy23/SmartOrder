<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Ambil semua detail, urutkan dari yang terbaru inputnya
        return OrderDetail::with(['order.buyer', 'order.fabric', 'color'])->latest()->get();
    }

    // Judul Kolom di Excel
    public function headings(): array
    {
        return [
            'Tanggal',
            'No Order',
            'No PO',
            'Customer',
            'Corak',
            'Kode Kain',
            'Kode Quality',
            'Kode Buyer',
            'Brand',
            'Konstruksi',
            'Density',
            'Warna',
            'Qty OM',
            'Batch Size',
            'Jml Batch',
            'Jml Grey',
            'Keterangan',
        ];
    }

    // Mengisi Data per Baris
    public function map($row): array
    {
        return [
            $row->order->order_date,
            $row->order->mf_number,          // Ambil dari Induk (Order)
            $row->order->po_number,
            $row->order->buyer->name ?? '-',  // Ambil dari Kakek (Buyer)
            $row->order->fabric->corak ?? '-', // Ambil dari Kakek (Fabric)
            $row->order->fabric->code_kain ?? '-', // Ambil dari Kakek (Fabric)
            $row->order->fabric->quality ?? '-', // Ambil dari Kakek (Fabric)
            $row->order->buyer->kode_buyer ?? '-', // Ambil dari Kakek (Buyer)
            $row->order->fabric->brand ?? '-', // Ambil dari Kakek (Fabric)
            $row->order->fabric->construction ?? '-', // Ambil dari Kakek (Fabric)
            $row->order->fabric->density ?? '-', // Ambil dari Kakek (Fabric)
            $row->color->name ?? '-',         // Ambil dari Color
            $row->qty_om,
            $row->batch_size,
            $row->jml_batch,
            $row->jml_grey,
            $row->notes,
        ];
    }
}