<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: 210mm 330mm;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px;
            padding: 10mm;
            margin: 0;
            width: 210mm;
            height: 330mm;
        }
        
        .header-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 8px;
            padding: 0 10mm;
        }
        
        .header-table td {
            padding: 2px 0;
            font-size: 10px;
        }
        
        .title { 
            font-size: 14px; 
            font-weight: bold; 
            text-align: center; 
            margin: 8px 0;
            text-decoration: underline;
            padding: 0 10mm;
        }
        
        .info-table {
            width: calc(100% - 20mm);
            border: 1px solid #000;
            border-collapse: collapse;
            margin: 0 10mm 10px 10mm;
            font-size: 10px;
        }
        
        .info-table td {
            padding: 6px 10px;
            vertical-align: top;
        }
        
        .info-table .label {
            width: 15%;
            white-space: nowrap;
        }
        
        .info-table .value {
            width: 35%;
        }
        
        .main-table { 
            width: calc(100% - 20mm);
            border-collapse: collapse;
            margin: 0 10mm 10px 10mm;
            font-size: 9px;
        }
        
        .main-table th, 
        .main-table td { 
            border: 1px solid #000; 
            padding: 4px 5px;
            text-align: center;
            vertical-align: middle;
        }
        
        .main-table th {
            background-color: #dddddd;
            font-weight: bold;
            font-size: 9px;
        }
        
        .main-table .left-align { 
            text-align: left;
        }
        
        .main-table tfoot td {
            font-weight: bold;
            background-color: #eeeeee;
            font-size: 9px;
        }
        
        .checkbox-section {
            width: calc(100% - 20mm);
            margin: 8px 10mm 0 10mm;
        }
        
        .box-row {
            width: 100%;
            display: table;
            table-layout: fixed;
            gap: 8px;
        }
        
        .box { 
            display: table-cell;
            width: calc(50% - 4px);
            border: 1px solid #000; 
            padding: 8px;
            font-size: 9px;
            vertical-align: top;
        }
        
        .box-title {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 9px;
        }
        
        .checkbox-item { 
            margin-bottom: 4px;
            font-size: 9px;
        }
        
        .square { 
            display: inline-block; 
            width: 10px; 
            height: 10px; 
            border: 1px solid #000; 
            margin-right: 6px;
            vertical-align: middle;
        }
        
        .konstruksi-box {
            width: calc(100% - 20mm);
            margin: 0 10mm 10px 10mm;
            border: 1px solid #000;
            border-collapse: collapse;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }
        
        .konstruksi-box td {
            padding: 8px 10px;
            border: 1px solid #000;
        }
        /* 👇 CSS UNTUK KOTAK KETIK BEBAS 👇 */
        .free-text-area {
            width: 100%;
            height: 120px; /* Tinggi kotak, bisa disesuaikan */
            border: none; /* Menghilangkan border dalam agar menyatu dengan kotak luar */
            resize: none; /* Mencegah kotak ditarik-tarik yang bisa merusak layout cetak */
            font-family: Arial, sans-serif;
            font-size: 10px;
            outline: none;
            padding: 4px;
            background: transparent;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <strong>PT. INDOTEX LASINDO JAYA</strong><br>
                DYEING FINISHING
            </td>
            <td style="width: 40%; text-align: right;">
                Tgl Cetak: {{ now()->format('d-m-Y H:i') }}
            </td>
        </tr>
    </table>

    <div class="title">ORDER KERJA DYEING FINISHING</div>

    <table class="info-table">
        <tr>
            <td class="label">NO ORDER</td>
            <td class="value">: <strong>{{ $order->mf_number }}</strong></td>
            <td class="label">TANGGAL</td>
            <td class="value">: {{ \Carbon\Carbon::parse($order->order_date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">CUSTOMER</td>
            <td class="value">: {{ $order->buyer->name ?? '-' }}</td>
            <td class="label">NO. PO</td>
            <td class="value">: {{ $order->po_number }}</td>
        </tr>
        <tr>
            <td class="label">KODE CORAK</td>
            <td class="value">: <strong>{{ $order->fabric->corak ?? '-' }}</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $order->fabric->code_kain ?? '-' }}</td>
            <td class="label">CUSTOMER LEVEL</td>
            <td class="value">: {{ $order->customer_level ?? '-' }} </td>
        </tr>
    </table>

    <table class="konstruksi-box">
        <tr>
            <td>KONSTRUKSI:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $order->fabric->construction ?? '-' }}&nbsp;&nbsp; X&nbsp;&nbsp; {{ $order->fabric->density}}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 17%;">WARNA</th>
                <th style="width: 13%;">JML OM</th>
                <th style="width: 14%;">BATCH SIZE (m)</th>
                <th style="width: 11%;">JML BATCH</th>
                <th style="width: 14%;">JML GREY (m/kg)</th>
                <th style="width: 26%;">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="left-align">{{ $item->color->name ?? '-' }}</td>
                <td>{{ number_format($item->qty_om) }} Y</td>
                <td>{{ number_format($item->batch_size) }}</td>
                <td>{{ $item->jml_batch }}</td>
                <td>{{ number_format($item->jml_grey) }}</td>
                <td class="left-align">{{ $item->notes }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td>{{ number_format($order->details->sum('qty_om')) }} Y</td>
                <td></td>
                <td>{{ $order->details->sum('jml_batch') }}</td>
                <td>{{ number_format($order->details->sum('jml_grey')) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="checkbox-section">
        <div class="box-row">
            
            <div class="box" style="height: 150px;"> 
                <div class="box-title">TARGET PRODUKSI</div>
                <div class="free-text-area" style="white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word;">{!! $order->target_produksi ?? '' !!}</div>
            </div>
            
            <div class="box" style="height: 150px;">
                <div class="box-title">TARGET PACKING</div>
                <div class="free-text-area" style="white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word;">{!! $order->target_packing ?? '' !!}</div>
            </div>

        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>