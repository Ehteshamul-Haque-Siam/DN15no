@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Admin Dashboard</h3>
        <div class="text-muted small">
            Logged in as <strong>{{ auth()->user()->name }}</strong>
            <span class="badge bg-primary">{{ auth()->user()->role }}</span>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['Total',            $stats['total'],    'primary', 'fa-list'],
                ['Pending Approval', $stats['pending'],  'warning', 'fa-hourglass-half'],
                ['Paid',             $stats['paid'],     'info',    'fa-credit-card'],
                ['Verified',         $stats['verified'], 'success', 'fa-check-circle'],
                ['Approved',         $stats['approved'], 'success', 'fa-user-check'],
                ['Rejected',         $stats['rejected'], 'danger',  'fa-times-circle'],
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

    {{-- Revenue + Quick links --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-soft border-0 h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Verified Revenue</h6>
                    <h2 class="mb-0">৳ {{ number_format($stats['revenue'], 2) }}</h2>
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

    {{-- Pending payment verifications --}}
    @if($pendingVerification->count())
        <div class="card shadow-soft border-0 mb-4">
            <div class="card-header bg-warning-subtle d-flex justify-content-between align-items-center">
                <strong>⏳ Awaiting Payment Verification ({{ $pendingVerification->count() }})</strong>
                <a href="{{ route('admin.registrations', ['payment' => 'pending']) }}" class="btn btn-sm btn-outline-dark">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reg ID</th><th>Name</th><th>Mobile</th>
                            <th>TRN</th><th>Amount</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingVerification as $r)
                            <tr>
                                <td><code>{{ $r->registration_id }}</code></td>
                                <td>{{ $r->name_en }}</td>
                                <td>{{ $r->contact_no }}</td>
                                <td><strong>{{ $r->mfs_trn }}</strong></td>
                                <td>৳ {{ number_format($r->membership_fee, 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.registrations.show', $r->id) }}"
                                       class="btn btn-sm btn-primary">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Recent registrations --}}
    <div class="card shadow-soft border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Recent Registrations</strong>
            <a href="{{ route('admin.registrations') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Reg ID</th><th>Name</th><th>Mobile</th>
                        <th>Payment</th><th>Approval</th><th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $r)
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
                            <td>{{ $r->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No registrations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection