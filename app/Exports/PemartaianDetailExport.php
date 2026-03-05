<?php

namespace App\Exports;

use App\Models\PemartaianDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; // Tambahan wajib untuk relasi
use Carbon\Carbon;

class PemartaianDetailExport implements FromCollection, WithHeadings, WithMapping
{
    // Variabel untuk membuat nomor urut otomatis
    private $rowNumber = 0;

    public function collection()
    {
        // Gunakan with() untuk Eager Loading agar proses query ke database sangat cepat
        return PemartaianDetail::with(['pemartaian', 'fabric'])
            ->orderBy('id', 'desc') // Urutkan dari yang terbaru (opsional)
            ->get();
    }

    // Fungsi map() ini bertugas mengisi baris demi baris Excel
    public function map($detail): array
    {
        $this->rowNumber++;

        // Format tanggal menjadi "02 Mar 2026"
        $tanggal = $detail->pemartaian && $detail->pemartaian->tanggal 
                    ? Carbon::parse($detail->pemartaian->tanggal)->translatedFormat('d M Y') 
                    : '-';
        
        // Gabungkan Jenis Pengeluaran dan Keterangannya
        $jenisKet = $detail->pemartaian 
                    ? $detail->pemartaian->jenis_pengeluaran . ' (' . $detail->pemartaian->ket_pengiriman . ')' 
                    : '-';

        return [
            $this->rowNumber,
            $detail->pemartaian->no_partai ?? '-',
            $tanggal,
            $jenisKet,
            $detail->no_order ?? '-',
            $detail->fabric->corak ?? '-',
            $detail->warna ?? '-',
            $detail->no_batch ?? '-',
            $detail->jml_gulung ?? 0,
            $detail->total_meter ?? 0,
            $detail->berat_kg ?? 0,
            $detail->keterangan ?? '-',
        ];
    }

    public function headings(): array
    {
        // Sesuaikan persis dengan header di tabel HTML kamu
        return [
            "No", 
            "No.Partai", 
            "Tanggal", 
            "Jenis / Ket", 
            "No.Order", 
            "Corak Kain", 
            "Warna", 
            "Batch", 
            "Gulung", 
            "Meter", 
            "Berat(Kg)", 
            "Keterangan"
        ];
    }
}