@extends('layouts.app')
@section('title', 'Registration Details')

@section('content')
<div class="container py-4">
    <a href="{{ route('admin.registrations') }}" class="btn btn-sm btn-outline-secondary mb-3">← Back</a>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

    <div class="alert alert-{{ $registration->approval_status==='approved' ? 'success' : ($registration->approval_status==='rejected' ? 'danger' : 'warning') }} d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>Workflow Status:</strong>
            Payment
            <span class="badge bg-{{ $registration->payment_status==='verified'?'success':($registration->payment_status==='rejected'?'danger':'warning') }}">
                {{ $registration->payment_status }}
            </span>
            →
            Approval
            <span class="badge bg-{{ $registration->approval_status==='approved'?'success':($registration->approval_status==='rejected'?'danger':'secondary') }}">
                {{ $registration->approval_status }}
            </span>
        </div>
        <div class="small">
            @if($registration->payment_status !== 'verified')
                <span class="text-danger">⚠ Verify payment first to enable approval</span>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-soft">
                <div class="card-body">
                    <h4 class="mb-1">{{ $registration->name_en }}</h4>
                    <p class="text-muted mb-3"><code>{{ $registration->registration_id }}</code></p>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name (BN):</strong> {{ $registration->name_bn }}</p>
                            <p><strong>Nickname:</strong> {{ $registration->nickname }}</p>
                            <p><strong>Father:</strong> {{ $registration->father_en }}<br><small>{{ $registration->father_bn }}</small></p>
                            <p><strong>Mother:</strong> {{ $registration->mother_en }}<br><small>{{ $registration->mother_bn }}</small></p>
                            <p><strong>Flat:</strong> {{ $registration->flat }}</p>
                            <p><strong>Duration:</strong> {{ $registration->from_year }} – {{ $registration->to_year }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mobile:</strong> {{ $registration->contact_no }}</p>
                            <p><strong>Email:</strong> {{ $registration->email ?? '—' }}</p>
                            <p><strong>Occupation:</strong> {{ $registration->occupation ?? '—' }}</p>
                            <p><strong>Present Address:</strong> {{ $registration->present_add ?? '—' }}</p>
                            <p><strong>Fee:</strong> ৳ {{ number_format($registration->membership_fee, 2) }}</p>
                            <p><strong>Created:</strong> {{ $registration->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>

                    <hr>
                    <h5>💳 Payment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>bKash Mobile:</strong> {{ $registration->mfs_no }}</p>
                            <p><strong>Transaction ID (TRN):</strong> <code>{{ $registration->mfs_trn }}</code></p>
                            <p><strong>Amount:</strong> ৳ {{ number_format($registration->membership_fee, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                <span class="badge bg-{{ $registration->payment_status==='verified'?'success':($registration->payment_status==='rejected'?'danger':'warning') }}">
                                    {{ $registration->payment_status }}
                                </span>
                            </p>
                            @if($registration->verified_at)
                                <p><strong>Verified At:</strong> {{ $registration->verified_at->format('d M Y, h:i A') }}</p>
                                <p><strong>Verified By:</strong> {{ $registration->verifier?->name ?? '—' }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- ============ ACTION BUTTONS ============ --}}
                    <div class="d-flex flex-wrap gap-2 mt-3">

                        {{-- NEW: bKash realtime query --}}
                        <form method="POST" action="{{ route('admin.query.bkash', $registration->id) }}">
                            @csrf
                            <button class="btn btn-info text-white"
                                    onclick="return confirm('Query bKash API for transaction {{ $registration->mfs_trn }}?')">
                                <i class="fa fa-search"></i> Query bKash
                            </button>
                        </form>

                        {{-- Manual verify --}}
                        @if($registration->payment_status !== 'verified')
                            <form method="POST" action="{{ route('admin.verify.payment', $registration->id) }}">
                                @csrf
                                <button class="btn btn-success">
                                    <i class="fa fa-check"></i> Verify Manually
                                </button>
                            </form>
                        @endif

                        {{-- Reject payment --}}
                        @if($registration->payment_status !== 'rejected')
                            <form method="POST" action="{{ route('admin.reject.payment', $registration->id) }}" class="d-flex gap-2 flex-wrap">
                                @csrf
                                <input type="text" name="remarks" class="form-control" placeholder="Reason" required style="max-width:220px;">
                                <button class="btn btn-outline-danger">Reject Payment</button>
                            </form>
                        @endif
                    </div>

                    <hr>
                    <h5>✅ Approval</h5>
                    @if($registration->payment_status !== 'verified')
                        <div class="alert alert-warning py-2 mb-2 small">
                            Approval is disabled until payment is verified.
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2">
                        @if($registration->approval_status !== 'approved')
                            <form method="POST" action="{{ route('admin.approve', $registration->id) }}">
                                @csrf
                                <button class="btn btn-success" @disabled($registration->payment_status !== 'verified')>
                                    <i class="fa fa-user-check"></i> Approve Membership
                                </button>
                            </form>
                        @endif
                        @if($registration->approval_status !== 'rejected')
                            <form method="POST" action="{{ route('admin.reject', $registration->id) }}" class="d-flex gap-2 flex-wrap">
                                @csrf
                                <input type="text" name="remarks" class="form-control" placeholder="Reason" required style="max-width:220px;">
                                <button class="btn btn-outline-danger">Reject Application</button>
                            </form>
                        @endif
                    </div>

                    @if($registration->admin_remarks)
                        <div class="alert alert-warning mt-3 mb-0">
                            <strong>Last Remarks:</strong> {{ $registration->admin_remarks }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-soft">
                <div class="card-body text-center">
                    @if($registration->hasPhoto())
                        <img src="{{ asset('storage/' . $registration->photo) }}"
                             class="img-fluid rounded mb-3"
                             alt="Applicant Photo"
                             style="max-height:280px;object-fit:cover;"
                             onerror="this.parentNode.innerHTML='<div class=\'text-muted py-4\'>📷 Image missing</div>';">
                    @else
                        <div class="bg-light border rounded d-flex align-items-center justify-content-center mb-3" style="height:180px;">
                            <div class="text-center text-muted">
                                <div style="font-size:36px;">📷</div>
                                <small>No photo uploaded</small>
                            </div>
                        </div>
                    @endif
                    <div class="text-muted small">Applicant photo</div>
                </div>
            </div>

            <div class="card shadow-soft mt-3">
                <div class="card-header"><strong>SMS History</strong></div>
                <div class="card-body p-2" style="max-height:300px;overflow-y:auto;">
                    @forelse($registration->smsLogs as $log)
                        <div class="border-bottom py-2 small">
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-secondary">{{ $log->type }}</span>
                                <span class="text-muted">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="mt-1">{{ $log->message }}</div>
                            <span class="badge bg-{{ $log->status==='sent'?'success':($log->status==='failed'?'danger':'warning') }} mt-1">
                                {{ $log->status }}
                            </span>
                        </div>
                    @empty
                        <div class="text-muted small p-3">No SMS sent yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection