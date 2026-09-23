@extends('layouts.app')
@section('title', 'Banks')

@section('content')
<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Bank Accounts</h3>
            <div class="text-muted small">Maximum 2 accounts</div>
        </div>
        @if($banks->count() < 2)
            <a href="{{ route('admin.banks.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Bank
            </a>
        @endif
    </div>

    @if($banks->count() === 0)
        <div class="card shadow-soft border-0">
            <div class="card-body text-center py-5 text-muted">
                <i class="fa fa-university" style="font-size:36px;opacity:.4;"></i>
                <div class="mt-2">No bank accounts added yet.</div>
                <a href="{{ route('admin.banks.create') }}" class="btn btn-primary mt-3">
                    <i class="fa fa-plus"></i> Add First Bank
                </a>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($banks as $bank)
                <div class="col-md-6">
                    <div class="card shadow-soft border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1">{{ $bank->bank_name }}</h5>
                                    <span class="badge {{ $bank->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $bank->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.banks.edit', $bank) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.banks.destroy', $bank) }}"
                                          onsubmit="return confirm('Delete this bank?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <div class="small">
                                <div><strong>Account Name:</strong> {{ $bank->account_name }}</div>
                                <div><strong>Account No:</strong> <code>{{ $bank->account_number }}</code></div>
                                @if($bank->branch) <div><strong>Branch:</strong> {{ $bank->branch }}</div> @endif
                                @if($bank->routing_number) <div><strong>Routing:</strong> {{ $bank->routing_number }}</div> @endif
                                @if($bank->swift_code) <div><strong>SWIFT:</strong> {{ $bank->swift_code }}</div> @endif
                                @if($bank->instructions)
                                    <div class="mt-2 text-muted">
                                        <i class="fa fa-lightbulb-o text-warning"></i> {{ $bank->instructions }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
    </div>
</div>
@endsection