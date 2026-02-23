<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $delivery->no_surat_jalan }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px; vertical-align: top; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px; text-align: center; }
        .data-table th { background-color: #f2f2f2; }
        
        .signature-box { width: 100%; margin-top: 50px; text-align: center; }
        .signature-box td { width: 33.33%; padding-top: 80px; }
        
        /* Auto-Print saat halaman dibuka */
        @media print {
            @page { margin: 1cm; }
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<script>
        function tutupHalaman() {
            // Trik 1: Mencoba melewati proteksi browser modern
            window.open('', '_self').close();
            
            // Trik 2: Fallback normal
            window.close();
            
            // Trik 3: Jika browser saking ketatnya tetap tidak mau menutup tab,
            // kita arahkan saja otomatis kembali ke halaman Daftar Surat Jalan
            setTimeout(function() {
                if (!window.closed) {
                    window.location.href = "{{ route('deliveries.index') }}";
                }
            }, 300);
        }
    </script>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 15px; cursor: pointer; background: #007bff; color: #fff; border: none; border-radius: 4px;">Cetak Sekarang</button>
        <button onclick="tutupHalaman()" style="padding: 8px 15px; cursor: pointer; background: #6c757d; color: #fff; border: none; border-radius: 4px; margin-left: 5px;">Tutup</button>
    </div>

    <div class="header">
        <h2>PT. INDOTEX LASINDO JAYA</h2>
        <p>Pabrik Tekstil & Garmen - Surat Jalan Pengiriman Barang</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>No. Surat Jalan</strong></td>
            <td width="2%">:</td>
            <td width="33%">{{ $delivery->no_surat_jalan }}</td>
            
            <td width="15%"><strong>Kepada Yth.</strong></td>
            <td width="2%">:</td>
            <td width="33%"><strong>{{ $delivery->buyer->name ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal Kirim</strong></td>
            <td>:</td>
            <td>{{ date('d F Y', strtotime($delivery->tanggal_kirim)) }}</td>
            
            <td><strong>Alamat</strong></td>
            <td>:</td>
            <td rowspan="2">{{ $delivery->buyer->address ?? '-' }} <br> Telp: {{ $delivery->buyer->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>No. Kendaraan</strong></td>
            <td>:</td>
            <td>{{ $delivery->no_kendaraan ?? '-' }} (Supir: {{ $delivery->nama_supir ?? '-' }})</td>
            
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">No. Order (PO)</th>
                <th width="35%">Spesifikasi Kain (Corak)</th>
                <th width="15%">Jml Roll</th>
                <th width="15%">Total Meter</th>
                <th width="10%">Berat (Kg)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $total_roll = 0; $total_mtr = 0; $total_kg = 0; 
            @endphp

            @foreach($delivery->details as $item)
                @php 
                    $total_roll += $item->jml_roll;
                    $total_mtr += $item->total_meter;
                    $total_kg += $item->total_berat;
                @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->qualityFinish->pemartaianDetail->no_order ?? '-' }}</td>
                <td style="text-align: left;">{{ $item->qualityFinish->pemartaianDetail->fabric->corak ?? '-' }}</td>
                <td>{{ $item->jml_roll }}</td>
                <td>{{ number_format($item->total_meter, 2) }}</td>
                <td>{{ number_format($item->total_berat, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="3" style="text-align: right;">TOTAL KESELURUHAN :</td>
                <td>{{ $total_roll }} Roll</td>
                <td>{{ number_format($total_mtr, 2) }} Mtr</td>
                <td>{{ number_format($total_kg, 2) }} Kg</td>
            </tr>
        </tfoot>
    </table>

    <p style="font-size: 13px;"><em>Catatan: {{ $delivery->keterangan ?? 'Barang telah diterima dalam keadaan baik dan cukup.' }}</em></p>

    <table class="signature-box">
        <tr>
            <td>
                ( ..................................... )<br>
                <strong>Tanda Terima / Pembeli</strong>
            </td>
            <td>
                ( {{ $delivery->nama_supir ?? '.....................................' }} )<br>
                <strong>Supir / Ekspedisi</strong>
            </td>
            <td>
                ( ..................................... )<br>
                <strong>Bagian Gudang / Hormat Kami</strong>
            </td>
        </tr>
    </table>

</body>
</html>