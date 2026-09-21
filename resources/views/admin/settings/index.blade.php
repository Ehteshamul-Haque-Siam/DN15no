@extends('layouts.app')
@section('title', 'Settings')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h3 class="mb-0">Settings</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card shadow-soft">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Membership Fee (৳)</label>
                        <input name="membership_fee" class="form-control" value="{{ $settings['membership_fee'] ?? '1020' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Numbers</label>
                        <input name="contact_numbers" class="form-control" value="{{ $settings['contact_numbers'] ?? '01721308219, 01712370172, 01707269988' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" class="form-control" value="{{ $settings['email'] ?? '15nocolony1966@gmail.com' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">bKash Merchant Number</label>
                        <input name="bkash_number" class="form-control" value="{{ $settings['bkash_number'] ?? '01761983617' }}">
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection