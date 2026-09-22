@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Admin Dashboard</h3>
        <div class="text-muted small">
            Logged in as <strong>{{ auth()->user()->name ?? '—' }}</strong>
            <span class="badge bg-primary">{{ auth()->user()->role ?? 'admin' }}</span>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['Total',            $stats['total']    ?? 0, 'primary', 'fa-list'],
                ['Pending Approval', $stats['pending']  ?? 0, 'warning', 'fa-hourglass-half'],
                ['Paid',             $stats['paid']     ?? 0, 'info',    'fa-credit-card'],
                ['Verified',         $stats['verified'] ?? 0, 'success', 'fa-check-circle'],
                ['Approved',         $stats['approved'] ?? 0, 'success', 'fa-user-check'],
                ['Rejected',         $stats['rejected'] ?? 0, 'danger',  'fa-times-circle'],
            ];
        @endphp
        @foreach($cards as $c)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card text-center shadow-soft border-0 h-100">
                    <div class="card-body">
                        <i class="fa {{ $c[3] }} text-{{ $c[2] }} mb-2" style="font-size:22px;"></i>
                        <div class="text-muted small">{{ $c[0] }}</div>
                        <h3 class="text-{{ $c[2] }} mt-1 mb-0">{{ $c[1] }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- bKash Realtime Monitoring --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-soft border-0">
            <div class="card-body">
                <i class="fa fa-hourglass-half text-warning" style="font-size:22px;"></i>
                <div class="text-muted small">Pending bKash Queries</div>
                <h3 class="text-warning mt-1 mb-0">{{ $bkashStats['pending_query'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-soft border-0">
            <div class="card-body">
                <i class="fa fa-check-circle text-success" style="font-size:22px;"></i>
                <div class="text-muted small">Verified Today</div>
                <h3 class="text-success mt-1 mb-0">{{ $bkashStats['verified_today'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
            <div class="card text-center shadow-soft border-0">
                <div class="card-body">
                    <i class="fa fa-money text-primary" style="font-size:22px;"></i>
                    <div class="text-muted small">Revenue Today</div>
                        <h4 class="text-primary mt-1 mb-0">৳ {{ number_format($bkashStats['revenue_today'], 2) }}</h4>
                        </div>
                </div>
            </div>
        <div class="col-6 col-md-3">
            <div class="card text-center shadow-soft border-0">
                <div class="card-body">
                    <i class="fa fa-line-chart text-info" style="font-size:22px;"></i>
                    <div class="text-muted small">Revenue This Month</div>
                    <h4 class="text-info mt-1 mb-0">৳ {{ number_format($bkashStats['revenue_month'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue + Quick links --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-soft border-0 h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Verified Revenue</h6>
                    <h2 class="mb-0">৳ {{ number_format($stats['revenue'] ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-soft border-0 h-100">
                <div class="card-body d-flex flex-wrap gap-2 align-items-center">
                    <a href="{{ route('admin.registrations') }}" class="btn btn-primary">
                        <i class="fa fa-list"></i> Registrations
                    </a>
                    <a href="{{ route('admin.sms.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-comment"></i> SMS Logs
                    </a>
                    @if(auth()->user()->role !== 'moderator')
                        <a href="{{ route('admin.bkash.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-credit-card"></i> bKash
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-users"></i> Users
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-cog"></i> Settings
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Payment Verification --}}
    <div class="card shadow-soft border-0 mb-4">
        <div class="card-header bg-warning-subtle d-flex justify-content-between align-items-center flex-wrap gap-2">
            <strong>
                ⏳ Awaiting Payment Verification
                <span class="badge bg-warning text-dark">
                    {{ isset($pendingVerification) ? $pendingVerification->count() : 0 }}
                </span>
            </strong>
            <a href="{{ route('admin.registrations', ['payment' => 'pending']) }}"
               class="btn btn-sm btn-outline-dark">
                View All
            </a>
        </div>

        @if(isset($pendingVerification) && $pendingVerification->count())
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reg ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>TRN</th>
                            <th>Amount</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingVerification as $r)
                            <tr>
                                <td><code>{{ $r->registration_id }}</code></td>
                                <td>
                                    {{ $r->name_en }}
                                    <br><small class="text-muted">{{ $r->name_bn }}</small>
                                </td>
                                <td>{{ $r->contact_no }}</td>
                                <td><strong>{{ $r->mfs_trn ?? '—' }}</strong></td>
                                <td>৳ {{ number_format($r->membership_fee ?? 0, 2) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.registrations.show', $r->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body text-center text-muted py-4">
                <i class="fa fa-check-circle text-success" style="font-size:32px;"></i>
                <div class="mt-2">No pending verifications right now.</div>
            </div>
        @endif
    </div>

    {{-- Recent Registrations --}}
    <div class="card shadow-soft border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Recent Registrations</strong>
            <a href="{{ route('admin.registrations') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>

        @if(isset($recent) && $recent->count())
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reg ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Payment</th>
                            <th>Approval</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent as $r)
                            <tr>
                                <td><code>{{ $r->registration_id }}</code></td>
                                <td>{{ $r->name_en }}</td>
                                <td>{{ $r->contact_no }}</td>
                                <td>
                                    <span class="badge bg-{{ $r->payment_status==='verified'?'success':($r->payment_status==='paid'?'info':($r->payment_status==='rejected'?'danger':'warning')) }}">
                                        {{ $r->payment_status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $r->approval_status==='approved'?'success':($r->approval_status==='rejected'?'danger':'secondary') }}">
                                        {{ $r->approval_status }}
                                    </span>
                                </td>
                                <td>{{ optional($r->created_at)->format('d M Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body text-center text-muted py-4">
                No registrations yet.
            </div>
        @endif
    </div>

</div>
@endsection