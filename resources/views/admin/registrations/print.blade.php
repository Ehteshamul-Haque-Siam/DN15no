<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Registered Members — {{ date('d M Y') }}</title>
    <style>
        /* ============================================================
         | Page Setup
         | ============================================================ */
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ============================================================
         | A4 Sheet
         | ============================================================ */
        .a4-sheet {
            display: flex;
            flex-direction: column;
            width: 190mm;
            height: 277mm;
            overflow: hidden;
        }

        .sheet-content {
            flex: 1 1 auto;
            overflow: hidden;
        }

        .sheet-footer {
            flex: 0 0 auto;
        }

        /* ============================================================
         | Letterhead
         | ============================================================ */
        .letterhead {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 8px;
            border-bottom: 2px double #0d6efd;
            margin-bottom: 10px;
        }

        .letterhead-logo {
            flex-shrink: 0;
            width: 72px;
            height: 72px;
            object-fit: contain;
        }

        .letterhead-info { flex: 1; text-align: center; }

        .letterhead-title {
            font-size: 18px;               /* was 15px */
            font-weight: 700;
            color: #0d6efd;
            line-height: 1.25;
            margin-bottom: 3px;
        }

        .letterhead-subtitle {
            font-size: 11px;               /* was 9px */
            color: #4b5563;
            font-style: italic;
            margin-bottom: 3px;
        }

        .letterhead-contact {
            font-size: 10px;               /* was 8px */
            color: #6b7280;
            line-height: 1.5;
        }

        .letterhead-contact span { margin: 0 5px; }

        /* ============================================================
         | Title Block
         | ============================================================ */
        .title-block {
            text-align: center;
            margin-bottom: 10px;
        }

        .report-title {
            font-size: 15px;               /* was 12px */
            font-weight: 700;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 3px;
        }

        .report-meta {
            font-size: 10.5px;             /* was 8.5px */
            color: #6b7280;
        }

        .report-meta strong { color: #0d6efd; }

        /* ============================================================
         | Table
         | ============================================================ */
        table.members-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;               /* was 8px */
        }

        table.members-table thead {
            display: table-header-group;
        }

        table.members-table thead th {
            background: #0d6efd;
            color: #fff;
            padding: 6px 4px;
            text-align: left;
            font-weight: 700;
            font-size: 10px;               /* was 8px */
            border: 1px solid #0d6efd;
        }

        table.members-table tbody td {
            padding: 5px 4px;
            border: 1px solid #d1d5db;
            vertical-align: middle;
            line-height: 1.35;
        }

        table.members-table tbody tr:nth-child(even) td {
            background: #f9fafb;
        }

        .td-center { text-align: center; }

        .photo-cell { width: 32px; text-align: center; }

        .photo-cell img {
            width: 26px;                   /* was 20px */
            height: 26px;
            object-fit: cover;
            border-radius: 3px;
            border: 1px solid #e5e7eb;
            display: block;
            margin: 0 auto;
        }

        .photo-placeholder {
            display: inline-block;
            width: 26px;
            height: 26px;
            border-radius: 3px;
            background: #f3f4f6;
            color: #9ca3af;
            font-size: 11px;
            line-height: 26px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .name-cell { max-width: 140px; }
        .name-en {
            font-weight: 700;
            color: #1f2937;
            display: block;
            font-size: 10px;               /* was 8px */
        }
        .name-bn {
            color: #6b7280;
            font-size: 9.5px;              /* was 7.5px */
            display: block;
        }

        .father-cell { max-width: 130px; }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8.5px;              /* was 6.5px */
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-success   { background: #d1e7dd; color: #0f5132; }
        .badge-info      { background: #cff4fc; color: #055160; }
        .badge-danger    { background: #f8d7da; color: #842029; }

        .trn-text {
            display: block;
            font-size: 8.5px;              /* was 6.5px */
            color: #6b7280;
            margin-top: 2px;
        }

        .duration-cell {
            text-align: center;
            white-space: nowrap;
            font-size: 9.5px;              /* was 7.5px */
        }

        /* ============================================================
         | Signatures
         | ============================================================ */
        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            padding-top: 10px;
        }

        .signature-box {
            width: 160px;
            border-top: 1px solid #6b7280;
            padding-top: 5px;
            text-align: center;
            font-size: 11px;               /* was 9px */
            color: #374151;
        }

        /* ============================================================
         | Footer
         | ============================================================ */
        .report-footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            text-align: center;
            font-size: 9.5px;              /* was 7.5px */
            color: #9ca3af;
            line-height: 1.5;
        }

        /* ============================================================
         | Print rules
         | ============================================================ */
        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
        }

        /* ============================================================
         | Screen preview
         | ============================================================ */
        @media screen {
            body {
                background: #e5e7eb;
                padding: 30px 20px;
            }

            .a4-sheet {
                margin: 0 auto;
                padding: 10mm;
                background: #fff;
                box-shadow: 0 8px 32px rgba(0,0,0,.15);
                border-radius: 4px;
                width: 210mm;
                height: 297mm;
            }

            .print-toolbar {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 100;
                display: flex;
                gap: 8px;
            }

            .print-toolbar button,
            .print-toolbar a {
                padding: 10px 18px;
                border-radius: 8px;
                border: none;
                font-weight: 600;
                font-size: 13px;
                cursor: pointer;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-family: Arial, sans-serif;
            }

            .btn-print {
                background: linear-gradient(135deg, #0d6efd, #3d7cff);
                color: #fff;
                box-shadow: 0 2px 8px rgba(13,110,253,.3);
            }

            .btn-close-window {
                background: #fff;
                color: #6b7280;
                border: 1px solid #e5e7eb;
            }
        }
    </style>
</head>
<body>

    {{-- Toolbar --}}
    <div class="print-toolbar no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Print / Save as PDF
        </button>
        <button onclick="window.close()" class="btn-close-window">
            ✕ Close
        </button>
    </div>

    <div class="a4-sheet">

        {{-- MAIN CONTENT --}}
        <div class="sheet-content">

            {{-- LETTERHEAD --}}
            <div class="letterhead">
                <img src="{{ asset('images/logo-square.png') }}"
                     alt="Logo"
                     class="letterhead-logo"
                     onerror="this.style.display='none'">

                <div class="letterhead-info">
                    <h1 class="letterhead-title">
                        ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম
                    </h1>
                    <p class="letterhead-subtitle">
                        Dhanmondi 15 No Govt. Staff Quarters Ex-Residents Forum
                    </p>
                    <p class="letterhead-contact">
                        <span>📍 সরকারী কর্মচারী কল্যাণ কেন্দ্র, সড়ক-৮/এ (পুরাতন-১৫), পশ্চিম ধানমণ্ডি, ঢাকা</span>
                        <br>
                        <span>📞 ০১৭২১৩০৮২১৯, ০১৭১২৩৭০১৭২</span>
                        <span>✉️ 15nocolony1966@gmail.com</span>
                    </p>
                </div>
            </div>

            {{-- TITLE --}}
            <div class="title-block">
                <h2 class="report-title">Registered Members List</h2>
                <div class="report-meta">
                    <strong>Total Members:</strong> {{ $registrations->count() }}
                    &nbsp;|&nbsp;
                    <strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}
                </div>
            </div>

            {{-- TABLE --}}
            @if($registrations->count() > 0)
                <table class="members-table">
                    <thead>
                        <tr>
                            <th style="width:4%;" class="td-center">#</th>
                            <th style="width:11%;">Reg ID</th>
                            <th style="width:5%;" class="td-center">Photo</th>
                            <th style="width:16%;">Name</th>
                            <th style="width:14%;">Father's Name</th>
                            <th style="width:5%;" class="td-center">Bldg</th>
                            <th style="width:5%;" class="td-center">Flat</th>
                            <th style="width:9%;" class="td-center">Duration</th>
                            <th style="width:11%;">Contact</th>
                            <th style="width:11%;">Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $i => $r)
                            <tr>
                                <td class="td-center">{{ $i + 1 }}</td>

                                <td>
                                    <code style="font-size:9px;">{{ $r->registration_id }}</code>
                                </td>

                                <td class="photo-cell">
                                    @if($r->hasPhoto())
                                        <img src="{{ asset('storage/' . $r->photo) }}"
                                             alt=""
                                             onerror="this.style.display='none'; this.parentNode.innerHTML='<span class=\'photo-placeholder\'>📷</span>';">
                                    @else
                                        <span class="photo-placeholder">📷</span>
                                    @endif
                                </td>

                                <td class="name-cell">
                                    <span class="name-en">{{ $r->name_en }}</span>
                                    <span class="name-bn">{{ $r->name_bn }}</span>
                                </td>

                                <td class="father-cell">
                                    <span class="name-en">{{ $r->father_en ?? '—' }}</span>
                                    @if($r->father_bn && $r->father_bn !== $r->father_en)
                                        <span class="name-bn">{{ $r->father_bn }}</span>
                                    @endif
                                </td>

                                <td class="td-center"><strong>{{ $r->building_no ?? '—' }}</strong></td>
                                <td class="td-center"><strong>{{ $r->flat_no ?? '—' }}</strong></td>

                                <td class="duration-cell">
                                    @if($r->from_year || $r->to_year)
                                        {{ $r->from_year ?? '?' }}–{{ $r->to_year ?? '?' }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>{{ $r->contact_no }}</td>

                                <td>
                                    <span class="badge badge-{{ $r->payment_method === 'bkash' ? 'danger' : ($r->payment_method === 'bank' ? 'info' : 'success') }}">
                                        {{ $r->payment_method_label }}
                                    </span>

                                    @if($r->mfs_trn)
                                        <span class="trn-text">TRN: {{ $r->mfs_trn }}</span>
                                    @elseif($r->bank_reference)
                                        <span class="trn-text">Ref: {{ $r->bank_reference }}</span>
                                    @elseif($r->cash_receipt_no)
                                        <span class="trn-text">Receipt: {{ $r->cash_receipt_no }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align:center; padding:30px 0; color:#9ca3af; font-size:11px;">
                    <div style="font-size:36px; opacity:.25;">📋</div>
                    <div style="margin-top:8px;">No members found.</div>
                </div>
            @endif

        </div>{{-- /sheet-content --}}

        {{-- FOOTER --}}
        <div class="sheet-footer">

            @if($registrations->count() > 0)
                <div class="signature-area">
                    <div class="signature-box">Prepared By</div>
                    <div class="signature-box">General Secretary</div>
                    <div class="signature-box">President</div>
                </div>
            @endif

            <div class="report-footer">
                ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম &copy; {{ date('Y') }}
                <br>
                This is a computer-generated report — no signature required for verification.
            </div>

        </div>{{-- /sheet-footer --}}

    </div>{{-- /a4-sheet --}}

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 600);
        });
    </script>

</body>
</html>