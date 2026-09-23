@extends('layouts.app')
@section('title', 'SMS Templates')

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

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h3 class="mb-0">SMS Templates</h3>
            <div class="text-muted small">
                {{ $templates->count() }} templates · Customize the messages sent to members
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.sms.templates.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> New Template
            </a>
            <a href="{{ route('admin.sms.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-list"></i> SMS Logs
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    @if($templates->count() === 0)
        <div class="card shadow-soft border-0">
            <div class="card-body text-center py-5">
                <i class="fa fa-comment" style="font-size:36px;opacity:.4;"></i>
                <h5 class="mt-3">No Templates Found</h5>
                <p class="text-muted">Create your first template or seed the defaults.</p>
                <a href="{{ route('admin.sms.templates.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Create Template
                </a>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($templates as $t)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-soft border-0 h-100 {{ !$t->is_active ? 'opacity-75' : '' }}">
                        <div class="card-body d-flex flex-column">

                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="mb-0">{{ $t->name }}</h5>
                                    <small class="text-muted font-monospace">{{ $t->key }}</small>
                                </div>
                                <form method="POST"
                                      action="{{ route('admin.sms.templates.toggle', $t) }}"
                                      class="d-inline">
                                    @csrf
                                    <button class="btn p-0 border-0" title="Click to toggle">
                                        <span class="badge {{ $t->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $t->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </button>
                                </form>
                            </div>

                            @if($t->description)
                                <p class="small text-muted mb-2">{{ $t->description }}</p>
                            @endif

                            <div class="sms-preview border rounded p-2 mb-3 bg-light flex-grow-1"
                                 style="font-size:.82rem; max-height:120px; overflow:auto;">
                                {{ $t->body }}
                            </div>

                            <div class="d-flex flex-wrap gap-1 mb-3">
                                @forelse($t->placeholders ?? [] as $ph)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                          style="font-size:.68rem; font-family: monospace;">
                                        {{ '{' . $ph . '}' }}
                                    </span>
                                @empty
                                    <span class="text-muted small">No placeholders</span>
                                @endforelse
                            </div>

                            {{-- Actions --}}
                            <div class="mt-auto">
                                <div class="d-flex gap-1 mb-2">
                                    <a href="{{ route('admin.sms.templates.edit', $t) }}"
                                       class="btn btn-sm btn-primary flex-grow-1" title="Edit">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.sms.templates.preview', $t) }}"
                                       class="btn btn-sm btn-outline-info"
                                       title="Preview" target="_blank">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>

                                <div class="d-flex gap-1">
                                    <form method="POST"
                                          action="{{ route('admin.sms.templates.duplicate', $t) }}"
                                          class="flex-grow-1">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-secondary w-100" title="Duplicate">
                                            <i class="fa fa-copy"></i> Copy
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('admin.sms.templates.reset', $t) }}"
                                          onsubmit="return confirm('Reset this template to its default?')">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-warning" title="Reset">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('admin.sms.templates.destroy', $t) }}"
                                          onsubmit="return confirm('Delete template {{ $t->name }}? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection