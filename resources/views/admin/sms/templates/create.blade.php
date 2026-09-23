@extends('layouts.app')
@section('title', 'New SMS Template')

@section('content')
<div class="container py-4">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h3 class="mb-0">Create New Template</h3>
            <div class="text-muted small">Define a new SMS template with placeholders</div>
        </div>
        <a href="{{ route('admin.sms.templates.index') }}" class="btn btn-outline-secondary btn-sm">
            ← Back
        </a>
    </div>

    <div class="row g-3">

        <div class="col-lg-8">
            <div class="card shadow-soft border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.sms.templates.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Display Name *</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name') }}"
                                       placeholder="e.g. Payment Reminder" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Template Key *
                                    <span class="text-muted small">(lowercase, underscores)</span>
                                </label>
                                <input type="text" name="key" class="form-control font-monospace"
                                       value="{{ old('key') }}"
                                       placeholder="e.g. payment_reminder"
                                       pattern="[a-z0-9_]+"
                                       title="Only lowercase letters, numbers, and underscores"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control"
                                   value="{{ old('description') }}"
                                   placeholder="What is this template for?"
                                   maxlength="500">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message Body *</label>
                            <textarea name="body" id="templateBody" rows="6"
                                      class="form-control font-monospace"
                                      style="font-size:.9rem;"
                                      placeholder="ধন্যবাদ {name}! আপনার Reg ID: {reg_id}"
                                      required>{{ old('body') }}</textarea>
                            <div class="form-text d-flex justify-content-between">
                                <span>
                                    <strong>Characters:</strong>
                                    <span id="charCount">0</span>
                                </span>
                                <span class="text-muted">
                                    Bengali SMS: 70 chars / part · English: 160 chars / part
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Available Placeholders
                                <span class="text-muted small">(comma-separated)</span>
                            </label>
                            <input type="text" name="placeholders" class="form-control font-monospace"
                                   value="{{ old('placeholders', 'name, reg_id, amount') }}"
                                   placeholder="name, reg_id, amount, payment_number">
                            <div class="form-text">
                                Users of this template can use these placeholders. Example: <code>{name}</code>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" value="1"
                                   class="form-check-input" id="isActive"
                                   @checked(old('is_active', true))>
                            <label class="form-check-label" for="isActive">
                                Active — send this template when triggered
                            </label>
                        </div>

                        <button class="btn btn-primary">
                            <i class="fa fa-save"></i> Create Template
                        </button>
                        <a href="{{ route('admin.sms.templates.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-soft border-0">
                <div class="card-header"><strong>Common Placeholders</strong></div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Click a placeholder to insert at the cursor.</p>
                    @foreach(['name', 'reg_id', 'amount', 'payment_number', 'trn', 'reason', 'contact', 'mobile', 'datetime'] as $ph)
                        <button type="button"
                                class="btn btn-sm btn-outline-primary mb-2 font-monospace"
                                onclick="insertPlaceholder('{{ '{' . $ph . '}' }}')">
                            {{ '{' . $ph . '}' }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="card shadow-soft border-0 mt-3">
                <div class="card-header"><strong>Live Preview</strong></div>
                <div class="card-body">
                    <div id="previewBox"
                         style="background:#f3f4f6;padding:1rem;border-radius:8px;font-size:.9rem;min-height:80px;white-space:pre-wrap;word-break:break-word;">
                        (empty)
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const bodyEl     = document.getElementById('templateBody');
    const charCount  = document.getElementById('charCount');
    const previewBox = document.getElementById('previewBox');

    const samples = {
        name:           'মোঃ রহিম উদ্দিন',
        reg_id:         'D15-2026-001',
        amount:         '1,020',
        payment_number: '01761983617',
        trn:            'AIUD222DJHD25',
        reason:         'ভুল TRN',
        contact:        '01721308219',
        mobile:         '01712345678',
        datetime:       new Date().toLocaleString('bn-BD'),
    };

    function insertPlaceholder(text) {
        const start = bodyEl.selectionStart;
        const end   = bodyEl.selectionEnd;
        const value = bodyEl.value;
        bodyEl.value = value.substring(0, start) + text + value.substring(end);
        bodyEl.focus();
        bodyEl.selectionStart = bodyEl.selectionEnd = start + text.length;
        updatePreview();
    }

    function updatePreview() {
        let rendered = bodyEl.value || '';
        Object.entries(samples).forEach(([k, v]) => {
            rendered = rendered.split('{' + k + '}').join(v);
        });
        previewBox.textContent = rendered || '(empty)';
        charCount.textContent  = bodyEl.value.length;
    }

    bodyEl.addEventListener('input', updatePreview);
    updatePreview();
</script>
@endpush