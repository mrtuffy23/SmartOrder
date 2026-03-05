<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Job Ticket - {{ $job->ticket_code }}</title>
    <style>
        /* Gaya Font Dot Matrix Klasik */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .header-title {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }
        .date-text {
            text-align: right;
            font-size: 12px;
            margin-bottom: 30px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 5px 2px;
            vertical-align: top;
        }
        .process-bar {
            font-size: 18px;
            font-weight: bold;
            padding: 10px 0;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-table th {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 5px;
            text-align: left;
        }
        .item-table td {
            padding: 8px 5px;
            vertical-align: top;
        }
        .section-spacer { height: 20px; }

        @media print {
            @page { margin: 15mm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header-title">Job Ticket</div>
    <div class="date-text">
        Date<br>
        {{ \Carbon\Carbon::parse($job->tanggal)->translatedFormat('l, d F Y') }}<br>
        {{ date('H:i') }} WIB
    </div>

    <table class="info-table font-bold">
        <tr>
            <td width="20%">Ticket Code</td>
            <td width="30%">: {{ $job->ticket_code }}</td>
            <td width="15%">Machine type</td>
            <td width="35%">: {{ $job->machine->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Machine Code</td>
            <td>: {{ $job->machine->machine_code ?? '-' }}</td>
            <td>Order. No</td>
            <td>: {{ $job->order->mf_number ?? '-' }}</td>
        </tr>
        <tr>
            <td>Fabric Weight</td>
            <td>: {{ number_format($job->fabric_weight, 0, ',', '.') }} Kg</td>
            <td>Color</td>
            <td>: {{ $job->color->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Volume</td>
            <td>: {{ number_format($job->volume, 0, ',', '.') }} L</td>
            <td>Article</td>
            <td>: {{ $job->order->fabric->corak ?? '-' }}</td>
        </tr>
    </table>

    <div class="process-bar">
        {{ $job->process->name ?? '' }} - {{ $job->order->mf_number ?? '' }} - {{ $job->color->name ?? '' }} - {{ $job->order->fabric->corak ?? '' }}
    </div>

    <table class="item-table">
        <thead>
            <tr>
                <th width="30%">D/A Code</th>
                <th width="20%" class="text-center">Concentration</th>
                <th width="20%" class="text-center">Weight (g)</th>
                <th width="30%" class="text-right">D/A Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($job->dyestuffs as $dye)
            <tr>
                <td class="font-bold">{{ $dye->dyestuff->active_code ?? '-' }}</td>
                <td class="text-center">{{ number_format($dye->concentration, 5, ',', '.') }}%</td>
                <td class="text-center font-bold">{{ number_format($dye->gram, 1, ',', '.') }}</td>
                <td class="text-right">{{ $dye->dyestuff->name ?? '-' }}</td>
            </tr>
            @endforeach

            <tr><td colspan="4"><div class="section-spacer"></div></td></tr>

            @foreach($job->chemicals as $chem)
            <tr>
                <td>{{ $chem->chemical->active_code ?? '-' }}</td>
                <td class="text-center">{{ number_format($chem->concentration, 1, ',', '.') }}</td>
                <td class="text-center font-bold">{{ number_format($chem->gram, 0, ',', '.') }}</td>
                <td class="text-right">{{ $chem->chemical->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>