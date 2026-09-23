@extends('layouts.app')
@section('title', 'Preview — ' . $template->name)

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h3 class="mb-0">Preview — {{ $template->name }}</h3>
            <div class="text-muted small font-monospace">key: {{ $template->key }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.sms.templates.edit', $template) }}" class="btn btn-primary btn-sm">
                <i class="fa fa-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.sms.templates.index') }}" class="btn btn-outline-secondary btn-sm">
                ← Back
            </a>
        </div>
    </div>

    <div class="row g-3">

        <div class="col-lg-8">
            <div class="card shadow-soft border-0">
                <div class="card-header"><strong>Rendered Output (Sample Data)</strong></div>
                <div class="card-body">
                    <div style="background:#f3f4f6;padding:1.25rem;border-radius:10px;font-size:1rem;white-space:pre-wrap;word-break:break-word;line-height:1.6;">
                        {{ $rendered }}
                    </div>
                    <div class="mt-3 small text-muted">
                        <strong>Character count:</strong> {{ mb_strlen($rendered) }}
                    </div>
                </div>
            </div>

            <div class="card shadow-soft border-0 mt-3">
                <div class="card-header"><strong>Raw Template</strong></div>
                <div class="card-body">
                    <pre style="background:#f3f4f6;padding:1rem;border-radius:8px;font-size:.85rem;white-space:pre-wrap;word-break:break-word;margin:0;">{{ $template->body }}</pre>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-soft border-0">
                <div class="card-header"><strong>Sample Values</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @foreach($samples as $key => $value)
                                <tr>
                                    <td class="font-monospace small">{{ '{' . $key . '}' }}</td>
                                    <td class="small">{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="alert alert-info mt-3 small">
                <i class="fa fa-info-circle"></i>
                Real values are substituted from the actual registration record when this SMS is sent.
            </div>
        </div>

    </div>
</div>
@endsection