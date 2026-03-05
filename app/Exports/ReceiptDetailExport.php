<?php

namespace App\Exports;

use App\Models\ReceiptDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ReceiptDetailExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    public function collection()
    {
        // Panggil relasi induk (receipt) dan master kain (fabric)
        return ReceiptDetail::with(['receipt', 'fabric'])
            ->select('receipt_details.*')
            ->join('receipts', 'receipts.id', '=', 'receipt_details.receipt_id')
            ->orderBy('receipts.tanggal', 'desc')
            ->orderBy('receipts.no_bukti', 'desc')
            ->get();
    }

    public function map($detail): array
    {
        $this->rowNumber++;

        // Format tanggal agar rapi di Excel (Contoh: 02 Mar 2026)
        $tanggal = $detail->receipt && $detail->receipt->tanggal 
                    ? Carbon::parse($detail->receipt->tanggal)->translatedFormat('d M Y') 
                    : '-';

        return [
            $this->rowNumber,
            $detail->receipt->no_bukti ?? '-',
            $tanggal,
            $detail->receipt->terima ?? '-',
            $detail->fabric->corak ?? '-',
            $detail->total_meter ?? 0,
            $detail->no_order ?? '-',
            $detail->keterangan ?? '-',
        ];
    }

    public function headings(): array
    {
        // Header ini akan sama persis dengan tabel di websitemu
        return [
            "No", 
            "No. Bukti", 
            "Tanggal", 
            "Terima (Asal)", 
            "Corak Kain", 
            "Meter", 
            "No. Order", 
            "Keterangan"
        ];
    }
}