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

    {{-- ==================== FILTERS ==================== --}}
    <form class="row g-2 mb-3 align-items-end" method="GET">

        <div class="col-12 col-md-6 col-lg-3">
            <label class="form-label small text-muted mb-1">Registration ID</label>
            <input type="text" name="id" class="form-control"
                   placeholder="e.g. 2026-001"
                   value="{{ request('id') }}">
        </div>

        <div class="col-6 col-md-3 col-lg-2">
            <label class="form-label small text-muted mb-1">Building No</label>
            <input type="text" name="building" class="form-control"
                   placeholder="e.g. 15"
                   value="{{ request('building') }}">
        </div>

        <div class="col-6 col-md-3 col-lg-2">
            <label class="form-label small text-muted mb-1">Flat No</label>
            <input type="text" name="flat" class="form-control"
                   placeholder="e.g. A-4"
                   value="{{ request('flat') }}">
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <label class="form-label small text-muted mb-1">Contact</label>
            <input type="text" name="contact" class="form-control"
                   placeholder="e.g. 01712345678"
                   value="{{ request('contact') }}">
        </div>

        <div class="col-6 col-md-3 col-lg-1">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

        <div class="col-6 col-md-3 col-lg-1">
            <a href="{{ route('admin.registrations') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>

        <div class="col-6 col-md-3 col-lg-2">
            <label class="form-label small text-muted mb-1">Payment</label>
            <select name="payment" class="form-select">
                <option value="">All</option>
                @foreach(['pending','paid','verified','rejected'] as $s)
                    <option value="{{ $s }}" @selected(request('payment')==$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-3 col-lg-2">
            <label class="form-label small text-muted mb-1">Approval</label>
            <select name="approval" class="form-select">
                <option value="">All</option>
                @foreach(['pending','approved','rejected'] as $s)
                    <option value="{{ $s }}" @selected(request('approval')==$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-lg-8">
            @php
                $activeFilters = collect(['id','building','flat','contact','payment','approval'])
                    ->filter(fn ($k) => request()->filled($k));
            @endphp
            @if($activeFilters->count())
                <div class="small text-muted">
                    <i class="fa fa-filter"></i>
                    Filtered: <strong>{{ $registrations->total() }}</strong> results
                    @foreach($activeFilters as $key)
                        <span class="badge bg-secondary ms-1">{{ $key }}: {{ request($key) }}</span>
                    @endforeach
                </div>
            @endif
        </div>

    </form>

    {{-- ==================== TABLE ==================== --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Reg ID</th>
                    <th class="text-center">Photo</th>
                    <th>Name</th>
                    <th class="text-center">Building No</th>
                    <th class="text-center">Flat No</th>
                    <th>Contact</th>
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
                        {{-- Reg ID --}}
                        <td><code>{{ $r->registration_id }}</code></td>

                        {{-- Photo thumbnail --}}
                        <td class="text-center">
                            @if($r->hasPhoto())
                                <a href="{{ asset('storage/' . $r->photo) }}"
                                   target="_blank"
                                   title="Click to open full photo">
                                    <img src="{{ asset('storage/' . $r->photo) }}"
                                         alt="Photo"
                                         class="rounded border"
                                         style="width:44px;height:44px;object-fit:cover;cursor:pointer;"
                                         onerror="this.onerror=null; this.src='{{ asset('images/avatar.png') }}';">
                                </a>
                            @else
                                <img src="{{ asset('images/avatar.png') }}"
                                     alt="No photo"
                                     class="rounded border opacity-50"
                                     style="width:44px;height:44px;object-fit:cover;">
                            @endif
                        </td>

                        {{-- Name --}}
                        <td>
                            {{ $r->name_en }}<br>
                            <small class="text-muted">{{ $r->name_bn }}</small>
                        </td>

                        {{-- Building No (separate) --}}
                        <td class="text-center">
                            @if($r->building_no)
                                <span class="badge bg-light text-dark border">{{ $r->building_no }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Flat No (separate) --}}
                        <td class="text-center">
                            @if($r->flat_no)
                                <span class="badge bg-light text-dark border">{{ $r->flat_no }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Contact --}}
                        <td>{{ $r->contact_no }}</td>

                        {{-- TRN --}}
                        <td>{{ $r->mfs_trn ?? '—' }}</td>

                        {{-- Payment --}}
                        <td>
                            <span class="badge bg-{{ $r->payment_status==='verified'?'success':($r->payment_status==='paid'?'info':($r->payment_status==='rejected'?'danger':'warning')) }}">
                                {{ $r->payment_status }}
                            </span>
                        </td>

                        {{-- Approval --}}
                        <td>
                            <span class="badge bg-{{ $r->approval_status==='approved'?'success':($r->approval_status==='rejected'?'danger':'secondary') }}">
                                {{ $r->approval_status }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td>{{ optional($r->created_at)->format('d M Y') ?? '—' }}</td>

                        {{-- Action --}}
                        <td>
                            <a href="{{ route('admin.registrations.show', $r->id) }}"
                               class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-4 text-muted">
                            <i class="fa fa-inbox" style="font-size:32px;"></i>
                            <div class="mt-2">No registrations match your filters.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $registrations->links() }}</div>

</div>
@endsection