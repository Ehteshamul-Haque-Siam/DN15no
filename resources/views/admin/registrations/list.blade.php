@extends('layouts.app')
@section('title', $pageTitle)

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $errors->first() }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h3 class="mb-0">{{ $pageTitle }}</h3>
            <div class="text-muted small">{{ $pageSubtitle }}</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.registrations.print', request()->only(['search','id','building','flat','contact','payment','method','sort','dir'])) }}"
               target="_blank"
               class="btn btn-danger btn-sm">
                <i class="fa fa-file-pdf-o"></i> Download PDF
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $listKey==='all' ? 'active' : '' }}"
               href="{{ route('admin.registrations') }}">All</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $listKey==='pending' ? 'active' : '' }}"
               href="{{ route('admin.registrations.pending') }}">Pending</a>
        </li>
    </ul>

    {{-- ============ FILTER BAR ============ --}}
    <div class="filter-card mb-3">
        <form method="GET" class="filter-form">

            {{-- Search row: ALWAYS visible (mobile + desktop) --}}
            <div class="search-row mb-2">
                <div class="filter-field search-field">
                    <i class="fa fa-search"></i>
                    <input type="text" name="search"
                           placeholder="Search name / father / mobile / TRX..."
                           value="{{ request('search') }}">
                </div>

                <button type="button"
                        class="filter-btn filter-btn-primary search-submit-btn"
                        onclick="this.form.submit()">
                    <i class="fa fa-search"></i> Search
                </button>

                <button type="button"
                        class="filter-btn filter-btn-toggle d-lg-none"
                        onclick="toggleAdvancedFilters()">
                    <i class="fa fa-sliders"></i> Filters
                    @php
                        $activeCount = collect(['id','building','flat','contact','method','payment'])
                            ->filter(fn ($k) => request()->filled($k))
                            ->count();
                    @endphp
                    @if($activeCount)
                        <span class="badge bg-light text-primary ms-1">{{ $activeCount }}</span>
                    @endif
                </button>
            </div>

            {{-- Advanced filters: hidden on mobile until toggled --}}
            <div class="advanced-filters" id="advancedFilters">

                <div class="filter-row">

                    <div class="filter-field">
                        <i class="fa fa-hashtag"></i>
                        <input type="text" name="id" placeholder="Reg ID"
                               value="{{ request('id') }}">
                    </div>

                    <div class="filter-field">
                        <i class="fa fa-building-o"></i>
                        <input type="text" name="building" placeholder="Building"
                               value="{{ request('building') }}">
                    </div>

                    <div class="filter-field">
                        <i class="fa fa-home"></i>
                        <input type="text" name="flat" placeholder="Flat"
                               value="{{ request('flat') }}">
                    </div>

                    <div class="filter-field">
                        <i class="fa fa-phone"></i>
                        <input type="text" name="contact" placeholder="Contact"
                               value="{{ request('contact') }}">
                    </div>

                    <div class="filter-field filter-select">
                        <i class="fa fa-credit-card"></i>
                        <select name="method">
                            <option value="">Method</option>
                            @foreach(['bkash' => 'bKash', 'bank' => 'Bank', 'cash' => 'Cash'] as $val => $label)
                                <option value="{{ $val }}" @selected(request('method')==$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($listKey === 'all')
                        <div class="filter-field filter-select">
                            <i class="fa fa-money"></i>
                            <select name="payment">
                                <option value="">Payment</option>
                                @foreach(['pending','paid','verified','rejected'] as $s)
                                    <option value="{{ $s }}" @selected(request('payment')==$s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-field filter-select">
                            <i class="fa fa-sort"></i>
                            <select name="sort">
                                <option value="created_at" @selected(request('sort', 'created_at')=='created_at')>Created Date</option>
                                <option value="registration_id" @selected(request('sort')=='registration_id')>Reg ID</option>
                                <option value="name_en" @selected(request('sort')=='name_en')>Name</option>
                                <option value="building_no" @selected(request('sort')=='building_no')>Building</option>
                                <option value="approved_at" @selected(request('sort')=='approved_at')>Approved Date</option>
                            </select>
                        </div>

                        <div class="filter-field filter-select" style="flex:0 1 110px;">
                            <i class="fa fa-arrow-down"></i>
                            <select name="dir">
                                <option value="desc" @selected(request('dir', 'desc')=='desc')>Desc</option>
                                <option value="asc" @selected(request('dir')=='asc')>Asc</option>
                            </select>
                        </div>
                    @endif

                    <button type="submit" class="filter-btn filter-btn-primary d-none d-lg-inline-flex">
                        <i class="fa fa-filter"></i> Apply
                    </button>

                    @if(request()->hasAny(['search','id','building','flat','contact','method','payment','sort','dir']))
                        <a href="{{ $listKey === 'pending' ? route('admin.registrations.pending') : route('admin.registrations') }}"
                           class="filter-btn filter-btn-reset">
                            <i class="fa fa-times"></i> Reset
                        </a>
                    @endif

                </div>
            </div>

            {{-- Active filter chips --}}
            @php
                $active = collect(['search','id','building','flat','contact','method','payment'])
                    ->filter(fn ($k) => request()->filled($k));
            @endphp
            @if($active->count())
                <div class="filter-chips">
                    <span class="text-muted small me-1">Active:</span>
                    @foreach($active as $key)
                        <span class="filter-chip">
                            {{ $key }}: {{ request($key) }}
                        </span>
                    @endforeach
                    <span class="text-muted small ms-2">
                        <strong>{{ $registrations->total() }}</strong> results
                    </span>
                </div>
            @endif

        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Reg ID</th>
                    <th class="text-center">Photo</th>
                    <th style="min-width:140px;">Name</th>
                    <th style="min-width:130px;">Father's Name</th>
                    <th class="text-center">Building</th>
                    <th class="text-center">Flat</th>
                    <th class="text-center" style="min-width:110px;">Duration</th>
                    <th>Contact</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $r)
                    <tr>
                        <td><code>{{ $r->registration_id }}</code></td>

                        <td class="text-center">
                            @if($r->hasPhoto())
                                <a href="{{ asset('storage/' . $r->photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $r->photo) }}"
                                         alt="Photo"
                                         class="rounded border"
                                         style="width:44px;height:44px;object-fit:cover;"
                                         onerror="this.onerror=null; this.src='{{ asset('images/avatar.png') }}';">
                                </a>
                            @else
                                <img src="{{ asset('images/avatar.png') }}"
                                     alt="No photo"
                                     class="rounded border opacity-50"
                                     style="width:44px;height:44px;object-fit:cover;">
                            @endif
                        </td>

                        <td>
                            <strong>{{ $r->name_en }}</strong><br>
                            <small class="text-muted">{{ $r->name_bn }}</small>
                            @if($r->nickname)
                                <br><small class="text-muted" style="font-size:10.5px;">"{{ $r->nickname }}"</small>
                            @endif
                        </td>

                        <td class="small">
                            @if($r->father_en || $r->father_bn)
                                <div><strong>{{ $r->father_en ?? '—' }}</strong></div>
                                @if($r->father_bn && $r->father_bn !== $r->father_en)
                                    <div class="text-muted" style="font-size:11px;">{{ $r->father_bn }}</div>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($r->building_no)
                                <span class="badge bg-light text-dark border">{{ $r->building_no }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($r->flat_no)
                                <span class="badge bg-light text-dark border">{{ $r->flat_no }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($r->from_year || $r->to_year)
                                <span class="badge bg-light text-dark border" style="font-weight:600;">
                                    {{ $r->from_year ?? '?' }} – {{ $r->to_year ?? '?' }}
                                </span>
                                <br>
                                <small class="text-muted" style="font-size:10px;">
                                    {{ $r->from_year && $r->to_year ? ($r->to_year - $r->from_year) . ' yrs' : '' }}
                                </small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>{{ $r->contact_no }}</td>

                        <td>
                            <span class="badge bg-{{ $r->payment_method === 'bkash' ? 'danger' : ($r->payment_method === 'bank' ? 'primary' : 'success') }}">
                                {{ $r->payment_method_label }}
                            </span>
                        </td>

                        <td><code>{{ $r->transaction_reference }}</code></td>

                        <td>
                            <span class="badge bg-{{ $r->payment_status==='verified'?'success':($r->payment_status==='paid'?'info':($r->payment_status==='rejected'?'danger':'warning')) }}">
                                {{ $r->payment_status }}
                            </span>
                        </td>

                        <td>{{ optional($r->created_at)->format('d M Y') ?? '—' }}</td>

                        <td class="text-end">
                            <div class="action-btns">
                                <a href="{{ route('admin.registrations.show', $r->id) }}"
                                   class="action-btn view" title="View details">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.registrations.edit', $r->id) }}"
                                   class="action-btn edit" title="Edit record">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.registrations.destroy', $r->id) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete {{ $r->registration_id }}? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn delete" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @if($listKey === 'pending')
                                    <form method="POST"
                                          action="{{ route('admin.verify.payment', $r->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Verify payment for {{ $r->registration_id }}?');">
                                        @csrf
                                        <button class="action-btn verify" title="Verify payment">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center py-5 text-muted">
                            <i class="fa fa-inbox" style="font-size:36px;opacity:.4;"></i>
                            <div class="mt-2">
                                @if($listKey === 'pending')
                                    No pending verifications.
                                @else
                                    No registrations found.
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $registrations->links() }}</div>

</div>
@endsection

@push('scripts')
<script>
    /* ============================================================
     | Toggle advanced filters (mobile only)
     | ============================================================ */
    function toggleAdvancedFilters() {
        const filters = document.getElementById('advancedFilters');
        if (!filters) return;
        filters.classList.toggle('open');
    }

    /* ============================================================
     | Auto-open advanced filters if any filter is active
     | ============================================================ */
    document.addEventListener('DOMContentLoaded', function () {
        const filters = document.getElementById('advancedFilters');
        if (!filters) return;

        const hasActiveFilter = {{ request()->hasAny(['id','building','flat','contact','method','payment']) ? 'true' : 'false' }};

        if (hasActiveFilter) {
            filters.classList.add('open');
        }
    });
</script>
@endpush