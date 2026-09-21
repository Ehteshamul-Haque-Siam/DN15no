@extends('layouts.app')
@section('title', 'Registrations')

@section('content')
<div class="container-fluid py-4">

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h3 class="mb-0">Registrations</h3>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.registrations.export') }}" class="btn btn-success btn-sm">Export CSV</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
        </div>
    </div>

    {{-- Filters --}}
    <form class="d-flex flex-wrap gap-2 mb-3" method="GET">
        <input type="text" name="search" class="form-control" style="max-width:240px;"
               placeholder="Search ID / name / mobile / TRN" value="{{ request('search') }}">

        <select name="status" class="form-select" style="max-width:170px;">
            <option value="">All Approval</option>
            @foreach(['pending','approved','rejected'] as $s)
                <option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>

        <select name="payment" class="form-select" style="max-width:170px;">
            <option value="">All Payment</option>
            @foreach(['pending','paid','verified','rejected'] as $s)
                <option value="{{ $s }}" @selected(request('payment')==$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>

        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.registrations') }}" class="btn btn-outline-secondary">Reset</a>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Reg ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>TRN</th>
                    <th>Payment</th>
                    <th>Approval</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $r)
                    <tr>
                        <td><code>{{ $r->registration_id }}</code></td>
                        <td>
                            {{ $r->name_en }}<br>
                            <small class="text-muted">{{ $r->name_bn }}</small>
                        </td>
                        <td>{{ $r->contact_no }}</td>
                        <td>{{ $r->mfs_trn ?? '—' }}</td>
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
                        <td>
                            <a href="{{ route('admin.registrations.show', $r->id) }}"
                               class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fa fa-inbox" style="font-size:32px;"></i>
                            <div class="mt-2">No registrations found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $registrations->links() }}</div>

</div>
@endsection