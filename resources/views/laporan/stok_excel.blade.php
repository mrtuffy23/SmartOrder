<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight: bold; text-align: center; font-size: 14px;">
                LAPORAN BUKU BESAR STOK GUDANG GREIGE
            </th>
        </tr>
        <tr></tr> <tr>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000;">No</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000;">Corak Kain</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000;">Saldo Awal</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000;">Terima (Masuk)</th>
            <th colspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000;">Keluar</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000;">Saldo Akhir</th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Pemartaian</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Retur</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $tot_awal = 0; $tot_masuk = 0; $tot_pemartaian = 0; $tot_retur = 0; $tot_akhir = 0; 
        @endphp
        
        @foreach($laporan as $index => $item)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ $item->corak }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->saldo_awal }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->terima }}</td>
                <td style="border: 1px solid #000; text-align: center; color: red;">- {{ $item->pemartaian }}</td>
                <td style="border: 1px solid #000; text-align: center; color: red;">- {{ $item->retur }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold; color: blue;">{{ $item->saldo_akhir }}</td>
            </tr>
            @php
                // Hitung total keseluruhan
                $tot_awal += $item->saldo_awal;
                $tot_masuk += $item->terima;
                $tot_pemartaian += $item->pemartaian;
                $tot_retur += $item->retur;
                $tot_akhir += $item->saldo_akhir;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: right; border: 1px solid #000;">TOTAL KESELURUHAN:</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000;">{{ $tot_awal }}</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000;">{{ $tot_masuk }}</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000; color: red;">- {{ $tot_pemartaian }}</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000; color: red;">- {{ $tot_retur }}</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000; color: blue;">{{ $tot_akhir }}</td>
        </tr>
    </tfoot>
</table>