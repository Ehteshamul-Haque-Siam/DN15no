<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Registrations Report</title>
    <style>
        @page { margin: 12mm 8mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9px;
            color: #1f2937;
            margin: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 4px 0;
            font-size: 14px;
            color: #0d6efd;
        }
        .header p {
            margin: 0;
            font-size: 9px;
            color: #6b7280;
        }
        .meta {
            margin-bottom: 10px;
            font-size: 8px;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        th {
            background: #0d6efd;
            color: #fff;
            border: 1px solid #0d6efd;
            padding: 5px 3px;
            text-align: left;
            font-weight: 600;
        }
        td {
            border: 1px solid #d1d5db;
            padding: 4px 3px;
        }
        tr:nth-child(even) td { background: #f9fafb; }
        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 600;
        }
        .badge-success   { background: #d1e7dd; color: #0f5132; }
        .badge-warning   { background: #fff3cd; color: #664d03; }
        .badge-danger    { background: #f8d7da; color: #842029; }
        .badge-info      { background: #cff4fc; color: #055160; }
        .badge-secondary { background: #e2e3e5; color: #41464b; }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 7px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম</h1>
        <p>Registrations Report</p>
    </div>

    <div class="meta">
        Generated: {{ now()->format('d M Y, h:i A') }} &nbsp; | &nbsp;
        Total records: {{ $registrations->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%;">#</th>
                <th style="width:11%;">Reg ID</th>
                <th style="width:17%;">Name</th>
                <th style="width:6%;">Bldg</th>
                <th style="width:6%;">Flat</th>
                <th style="width:12%;">Contact</th>
                <th style="width:13%;">TRN / Ref</th>
                <th style="width:8%;">Method</th>
                <th style="width:8%;">Payment</th>
                <th style="width:8%;">Approval</th>
                <th style="width:8%;">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->registration_id }}</td>
                    <td>
                        <strong>{{ $r->name_en }}</strong><br>
                        <span style="color:#6b7280;">{{ $r->name_bn }}</span>
                    </td>
                    <td>{{ $r->building_no ?? '—' }}</td>
                    <td>{{ $r->flat_no ?? '—' }}</td>
                    <td>{{ $r->contact_no }}</td>
                    <td>{{ $r->transaction_reference ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $r->payment_method === 'bkash' ? 'danger' : ($r->payment_method === 'bank' ? 'info' : 'success') }}">
                            {{ $r->payment_method_label }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $r->payment_status==='verified'?'success':($r->payment_status==='paid'?'info':($r->payment_status==='rejected'?'danger':'warning')) }}">
                            {{ $r->payment_status }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $r->approval_status==='approved'?'success':($r->approval_status==='rejected'?'danger':'secondary') }}">
                            {{ $r->approval_status }}
                        </span>
                    </td>
                    <td>{{ optional($r->created_at)->format('d M Y') ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম &copy; {{ date('Y') }}
    </div>

</body>
</html>