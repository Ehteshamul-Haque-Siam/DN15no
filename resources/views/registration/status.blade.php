@extends('layouts.app')
@section('title', 'স্ট্যাটাস')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-soft">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Reg ID: {{ $registration->registration_id }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Name:</strong> {{ $registration->name_en }}</div>
                        <div class="col-md-6"><strong>Mobile:</strong> {{ $registration->contact_no }}</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <div class="text-muted small">Payment Status</div>
                                <span class="badge badge-status bg-{{ $registration->payment_status === 'verified' ? 'success' : ($registration->payment_status === 'paid' ? 'info' : 'warning') }} mt-2">
                                    {{ ucfirst($registration->payment_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <div class="text-muted small">Approval Status</div>
                                <span class="badge badge-status bg-{{ $registration->approval_status === 'approved' ? 'success' : ($registration->approval_status === 'rejected' ? 'danger' : 'secondary') }} mt-2">
                                    {{ ucfirst($registration->approval_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($registration->admin_remarks)
                        <div class="alert alert-warning mt-3">
                            <strong>Remarks:</strong> {{ $registration->admin_remarks }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection