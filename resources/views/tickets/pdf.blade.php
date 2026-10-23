<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tiket IT Bulanan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .footer { position: absolute; bottom: 30px; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ccc; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">LAPORAN KINERJA IT (TICKETING SYSTEM)</div>
        <div class="subtitle">
            Periode: {{ request('filter_month') ? date('F Y', strtotime(request('filter_month'))) : 'Keseluruhan' }}
        </div>
    </div>

    <div>
        <strong>Total Tiket:</strong> {{ count($tickets) }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 15%">Pelapor</th>
                <th style="width: 15%">Perangkat</th>
                <th style="width: 25%">Keterangan/Judul</th>
                <th style="width: 10%">Prioritas</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $ticket->user->name }}</td>
                <td>{{ $ticket->item_type ?? '-' }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ strtoupper($ticket->priority) }}</td>
                <td>{{ strtoupper($ticket->status) }}</td>
            </tr>
            @endforeach
            @if(count($tickets) == 0)
            <tr>
                <td colspan="7" style="text-align: center">Tidak ada data di periode ini.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh TGA Ticketing System pada {{ date('d M Y H:i') }} WIB.
    </div>

</body>
</html>
