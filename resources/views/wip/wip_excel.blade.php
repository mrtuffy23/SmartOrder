<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center; font-size: 14px;">
                LAPORAN BUKU BESAR MESIN (WIP)
            </th>
        </tr>
        <tr></tr> <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">No</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Corak Kain</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Saldo Awal</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Terima (Dari Greige)</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Keluar</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Saldo Akhir</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $total_awal = 0; $total_terima = 0; $total_keluar = 0; $total_akhir = 0; 
        @endphp
        
        @foreach($laporan_wip as $index => $item)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $item->corak }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $item->saldo_awal }}</td>
                <td style="border: 1px solid #000; text-align: center; color: green;">{{ $item->terima }}</td>
                <td style="border: 1px solid #000; text-align: center; color: red;">- {{ $item->keluar }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold; color: blue;">{{ $item->saldo_akhir }}</td>
            </tr>
            @php
                $total_awal += $item->saldo_awal;
                $total_terima += $item->terima;
                $total_keluar += $item->keluar;
                $total_akhir += $item->saldo_akhir;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: right; border: 1px solid #000;">TOTAL KESELURUHAN:</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000;">{{ $total_awal }}</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000; color: green;">{{ $total_terima }}</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000; color: red;">- {{ $total_keluar }}</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000; color: blue;">{{ $total_akhir }}</td>
        </tr>
    </tfoot>
</table>