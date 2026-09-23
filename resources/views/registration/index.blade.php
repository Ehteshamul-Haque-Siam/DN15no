@extends('layouts.app')
@section('title', 'সদস্য রেজিস্ট্রেশন ফরম — ধানমন্ডি ১৫ নং')

@section('content')
<div class="container pb-5">

    <div class="reg-hero mb-4">
        <div class="container text-center">
            <h1>সদস্য রেজিস্ট্রেশন ফরম</h1>
            <p>ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger shadow-soft border-0 rounded-3">
            <strong>⚠ অনুগ্রহ করে নিচের ত্রুটিগুলো ঠিক করুন:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form id="registration-form"
          action="{{ route('register.store') }}"
          method="POST"
          enctype="multipart/form-data"
          novalidate>
        @csrf

        <div class="row g-4">

            <div class="col-lg-8">

                {{-- SECTION 1 --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="step-badge">1</div>
                        <div>
                            <h5>ব্যক্তিগত তথ্য</h5>
                            <small>Personal Information</small>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label">পূর্ণ নাম (English) <span class="text-danger">*</span></label>
                                <input type="text" name="name_en" value="{{ old('name_en') }}"
                                       class="form-control @error('name_en') is-invalid @enderror"
                                       placeholder="e.g. Md. Rahim Uddin" required>
                                @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">পূর্ণ নাম (বাংলা) <span class="text-danger">*</span></label>
                                <input type="text" name="name_bn" value="{{ old('name_bn') }}"
                                       class="form-control @error('name_bn') is-invalid @enderror"
                                       placeholder="যেমন: মোঃ রহিম উদ্দিন" required>
                                @error('name_bn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">ডাক নাম <span class="bn">(কলোনীতে)</span> <span class="text-danger">*</span></label>
                                <input type="text" name="nickname" value="{{ old('nickname') }}"
                                       class="form-control" placeholder="যেমন: রহিম ভাই" required>
                            </div>
                        </div>

                        <hr class="my-3 opacity-25">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label">পিতার নাম (English) <span class="text-danger">*</span></label>
                                <input type="text" name="father_en" value="{{ old('father_en') }}"
                                       class="form-control" placeholder="Father's name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">পিতার নাম (বাংলা) <span class="text-danger">*</span></label>
                                <input type="text" name="father_bn" value="{{ old('father_bn') }}"
                                       class="form-control" placeholder="পিতার নাম" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">মাতার নাম (English) <span class="text-danger">*</span></label>
                                <input type="text" name="mother_en" value="{{ old('mother_en') }}"
                                       class="form-control" placeholder="Mother's name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">মাতার নাম (বাংলা) <span class="text-danger">*</span></label>
                                <input type="text" name="mother_bn" value="{{ old('mother_bn') }}"
                                       class="form-control" placeholder="মাতার নাম" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2 --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="step-badge">2</div>
                        <div>
                            <h5>বসবাস ও যোগাযোগ</h5>
                            <small>Residence & Contact</small>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label class="field-label">বিল্ডিং নম্বর <span class="text-danger">*</span></label>
                                <input type="text" name="building_no" value="{{ old('building_no') }}"
                                       class="form-control" placeholder="যেমন: 15" required>
                                <div class="field-hint">কলোনীতে বসবাসের বিল্ডিং নম্বর</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="field-label">ফ্ল্যাট নম্বর <span class="text-danger">*</span></label>
                                <input type="text" name="flat_no" value="{{ old('flat_no') }}"
                                       class="form-control" placeholder="যেমন: A-4 / 12-B" required>
                                <div class="field-hint">আপনার ফ্ল্যাট নম্বর</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="field-label">অবস্থানের সময়কাল <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="from_year" value="{{ old('from_year') }}"
                                           class="form-control" placeholder="শুরু" required>
                                    <span class="input-group-text">থেকে</span>
                                    <input type="text" name="to_year" value="{{ old('to_year') }}"
                                           class="form-control" placeholder="শেষ" required>
                                </div>
                                <div class="field-hint">যেমন: ২০০০ থেকে ২০০৬</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="field-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                <input type="text" name="contact_no" value="{{ old('contact_no') }}"
                                       class="form-control" placeholder="01XXXXXXXXX" required>
                                <div class="field-hint">যেমন: 01712345678</div>
                            </div>

                            <div class="col-12">
                                <label class="field-label">বর্তমান ঠিকানা</label>
                                <textarea name="present_add" rows="2" class="form-control"
                                          placeholder="বর্তমানে কোথায় বসবাস করছেন (ঐচ্ছিক)">{{ old('present_add') }}</textarea>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="field-label">পেশা</label>
                                <input type="text" name="occupation" value="{{ old('occupation') }}"
                                       class="form-control" placeholder="যেমন: সরকারি চাকরি / ব্যবসা">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="field-label">ইমেইল <span class="bn">(ঐচ্ছিক)</span></label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="form-control" placeholder="you@example.com">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- SECTION 3 — Payment --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="step-badge">3</div>
                        <div>
                            <h5>বার্ষিক চাঁদা পরিশোধ</h5>
                            <small>Annual Membership Fee</small>
                        </div>
                    </div>
                    <div class="form-card-body">

                        {{-- Amount --}}
                        <div class="pay-guide mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <div class="text-muted small">পরিশোধযোগ্য পরিমাণ</div>
                                    <div class="bkash-amount">৳ ১,০২০</div>
                                </div>
                                <div class="text-end small text-muted">
                                    নিচের যেকোনো একটি পদ্ধতিতে পরিশোধ করুন
                                </div>
                            </div>
                        </div>

                        {{-- Method selector --}}
                        <label class="field-label mb-2">
                            পেমেন্ট পদ্ধতি নির্বাচন করুন <span class="text-danger">*</span>
                        </label>

                        <input type="hidden" name="payment_method" id="payment_method"
                               value="{{ old('payment_method', 'bkash') }}">

                        @php $activeBankCount = \App\Models\Bank::activeCount(); @endphp

                        <div class="payment-methods mb-4 {{ $activeBankCount > 0 ? '' : 'two-methods' }}">

                            <div class="payment-method-card active" data-method="bkash" onclick="selectPaymentMethod('bkash')">
                                <div class="pm-icon pm-bkash"><i class="fa fa-mobile"></i></div>
                                <div class="pm-body">
                                    <div class="pm-title">bKash</div>
                                    <div class="pm-sub">Mobile Banking</div>
                                </div>
                                <div class="pm-check"><i class="fa fa-check-circle"></i></div>
                            </div>

                            @if($activeBankCount > 0)
                                <div class="payment-method-card" data-method="bank" onclick="selectPaymentMethod('bank')">
                                    <div class="pm-icon pm-bank"><i class="fa fa-university"></i></div>
                                    <div class="pm-body">
                                        <div class="pm-title">Bank Transfer</div>
                                        <div class="pm-sub">ব্যাংক ট্রান্সফার</div>
                                    </div>
                                    <div class="pm-check"><i class="fa fa-check-circle"></i></div>
                                </div>
                            @endif

                            <div class="payment-method-card" data-method="cash" onclick="selectPaymentMethod('cash')">
                                <div class="pm-icon pm-cash"><i class="fa fa-money"></i></div>
                                <div class="pm-body">
                                    <div class="pm-title">Cash</div>
                                    <div class="pm-sub">হাতে হাতে পরিশোধ</div>
                                </div>
                                <div class="pm-check"><i class="fa fa-check-circle"></i></div>
                            </div>

                        </div>

                        {{-- ============ bKash PANEL ============ --}}
                        <div id="panel-bkash" class="payment-panel active">
                            <div class="pay-guide mb-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="text-muted small">bKash নাম্বার</div>
                                        <div class="bkash-amount">01761983617</div>
                                    </div>
                                    <a href="https://shop.bkash.com/oitijjo01761983617/pay/bdt1020/XpxHaY"
                                       target="_blank" rel="noopener"
                                       class="btn rounded-pill px-4 d-inline-flex align-items-center gap-2"
                                       style="background:#E2136E;color:#fff;font-weight:600;">
                                        <i class="fa fa-mobile"></i> Pay with bKash
                                    </a>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="field-label">বিকাশ নাম্বার <span class="text-danger">*</span></label>
                                    <input type="text" name="mfs_no" id="mfs_no"
                                           value="{{ old('mfs_no') }}"
                                           class="form-control" placeholder="যে নাম্বার থেকে পাঠিয়েছেন">
                                </div>
                                <div class="col-md-6">
                                    <label class="field-label">Transaction ID (TRN) <span class="text-danger">*</span></label>
                                    <input type="text" name="mfs_trn" id="mfs_trn"
                                           value="{{ old('mfs_trn') }}"
                                           class="form-control" placeholder="যেমন: 9AB1C2D3E4">
                                    <div class="field-hint">বিকাশ SMS থেকে TRN কপি করুন</div>
                                </div>
                            </div>

                            <hr class="my-4 opacity-25">

                            <h6 class="text-muted mb-3">
                                <i class="fa fa-info-circle text-primary"></i>
                                কীভাবে পেমেন্ট করবেন (USSD)
                            </h6>

                            <div class="text-center">
                                <a href="{{ asset('images/bkash-ussd.png') }}" target="_blank"
                                   title="Click to open full-size image" class="d-inline-block">
                                    <img src="{{ asset('images/bkash-ussd.png') }}"
                                         class="pay-guide-img" alt="bKash USSD guide"
                                         onerror="this.style.display='none'; this.parentNode.style.display='none';">
                                </a>
                                <div class="pay-guide-hint">
                                    <i class="fa fa-search-plus"></i>
                                    <strong>Dial *247# → Make Payment</strong>
                                </div>
                            </div>
                        </div>

                        {{-- ============ BANK PANEL ============ --}}
                        <div id="panel-bank" class="payment-panel">
                            @php $banks = \App\Models\Bank::activeBanks(); @endphp

                            @if($banks->count() > 0)
                                <div class="alert alert-info small mb-3">
                                    <i class="fa fa-info-circle"></i>
                                    নিচের যেকোনো একটি ব্যাংক অ্যাকাউন্টে টাকা পাঠান, তারপর ট্রান্সফার রেফারেন্স নাম্বার দিন।
                                </div>

                                <label class="field-label mb-2">ব্যাংক নির্বাচন করুন <span class="text-danger">*</span></label>

                                <div class="bank-options mb-3">
                                    @foreach($banks as $i => $bank)
                                        <label class="bank-option">
                                            <input type="radio" name="bank_id" value="{{ $bank->id }}"
                                                   {{ (old('bank_id') == $bank->id || ($i === 0 && !old('bank_id'))) ? 'checked' : '' }}>
                                            <div class="bank-card">
                                                <div class="bank-name">
                                                    <i class="fa fa-university"></i>
                                                    {{ $bank->bank_name }}
                                                </div>
                                                <div class="bank-detail">
                                                    <div><strong>Account:</strong> {{ $bank->account_name }}</div>
                                                    <div><strong>A/C No:</strong> {{ $bank->account_number }}</div>
                                                    @if($bank->branch)
                                                        <div><strong>Branch:</strong> {{ $bank->branch }}</div>
                                                    @endif
                                                    @if($bank->routing_number)
                                                        <div><strong>Routing:</strong> {{ $bank->routing_number }}</div>
                                                    @endif
                                                </div>
                                                @if($bank->instructions)
                                                    <div class="bank-instructions small text-muted">
                                                        <i class="fa fa-lightbulb-o text-warning"></i>
                                                        {{ $bank->instructions }}
                                                    </div>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="field-label">আপনার অ্যাকাউন্ট নাম্বার <span class="text-danger">*</span></label>
                                        <input type="text" name="paid_to_bank" id="paid_to_bank"
                                               value="{{ old('paid_to_bank') }}"
                                               class="form-control" placeholder="যে অ্যাকাউন্ট থেকে পাঠিয়েছেন">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="field-label">Transaction Reference <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_reference" id="bank_reference"
                                               value="{{ old('bank_reference') }}"
                                               class="form-control" placeholder="যেমন: FT2508ABC123">
                                        <div class="field-hint">ব্যাংক SMS বা রিসিট থেকে রেফারেন্স কপি করুন</div>
                                    </div>

                                    <div class="col-12">
                                        <label class="field-label">ব্যাংক স্লিপ / রিসিট (ঐচ্ছিক)</label>
                                        <input type="file" name="bank_slip" id="bank_slip"
                                               class="form-control" accept="image/*,application/pdf">
                                        <div class="field-hint">JPG / PNG / PDF · সর্বোচ্চ ৮ MB</div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    কোনো ব্যাংক অ্যাকাউন্ট এখনো যোগ করা হয়নি। bKash বা Cash ব্যবহার করুন।
                                </div>
                            @endif
                        </div>

                        {{-- ============ CASH PANEL ============ --}}
                        <div id="panel-cash" class="payment-panel">
                            <div class="alert alert-info small mb-3">
                                <i class="fa fa-info-circle"></i>
                                ফোরাম অফিসে সরাসরি ক্যাশ পরিশোধ করুন এবং নিচের তথ্য পূরণ করুন।
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="field-label">রিসিট নাম্বার <span class="text-danger">*</span></label>
                                    <input type="text" name="cash_receipt_no" id="cash_receipt_no"
                                           value="{{ old('cash_receipt_no') }}"
                                           class="form-control" placeholder="যেমন: C-2026-0042">
                                    <div class="field-hint">ক্যাশ পরিশোধের সময় দেওয়া রিসিট নাম্বার</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="field-label">যোগাযোগ নাম্বার</label>
                                    <input type="text" name="cash_contact" id="cash_contact"
                                           value="{{ old('cash_contact') }}"
                                           class="form-control" placeholder="01XXXXXXXXX">
                                </div>

                                <div class="col-12">
                                    <label class="field-label">
                                        রিসিট / স্লিপ আপলোড <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="cash_receipt" id="cash_receipt"
                                           class="form-control" accept="image/*,application/pdf">
                                    <div class="field-hint">
                                        <i class="fa fa-info-circle text-primary"></i>
                                        JPG / PNG / PDF · সর্বোচ্চ ৮ MB — ক্যাশ রিসিটের ছবি বা PDF আপলোড করুন
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-4">

                <div class="form-card">
                    <div class="form-card-header">
                        <div class="step-badge">৪</div>
                        <div>
                            <h5>ছবি আপলোড</h5>
                            <small>Photo</small>
                        </div>
                    </div>
                    <div class="form-card-body text-center">
                        <label for="avatar" class="photo-box d-block">
                            <img id="preview" src="{{ asset('images/avatar.png') }}" alt="Preview">
                            <div class="cta"><i class="fa fa-camera"></i> ছবি নির্বাচন করুন</div>
                            <div class="meta">JPG / PNG · সর্বোচ্চ ৪ MB</div>
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/*">
                    </div>
                </div>

                <div class="alert alert-light border small mb-3">
                    <i class="fa fa-shield text-success"></i>
                    আপনার তথ্য সম্পূর্ণ নিরাপদ।
                </div>

                <div class="submit-bar">
                    <div>
                        <div class="small text-danger">* চিহ্নিত ঘরগুলো অবশ্যই পূরণ করুন</div>
                        <div class="small text-muted">সাবমিটের পর ২৪-৪৮ ঘণ্টার মধ্যে SMS পাবেন</div>
                    </div>
                    <button type="submit" class="btn-submit">
                        Submit Application <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    function selectPaymentMethod(method) {
        document.getElementById('payment_method').value = method;

        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.toggle('active', card.dataset.method === method);
        });

        document.querySelectorAll('.payment-panel').forEach(panel => {
            panel.classList.remove('active');
        });

        const target = document.getElementById('panel-' + method);
        if (target) target.classList.add('active');

        toggleRequiredFields(method);
    }

    function toggleRequiredFields(method) {
        const map = {
            bkash: ['mfs_no', 'mfs_trn'],
            bank:  ['bank_reference', 'paid_to_bank'],
            cash:  ['cash_receipt_no', 'cash_receipt'],
        };
        Object.keys(map).forEach(key => {
            map[key].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.required = (key === method);
            });
        });
    }

    function resizeImage(file, cb) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const maxW = 400, maxH = 400;
                let w = img.width, h = img.height;
                if (w > h) { if (w > maxW) { h *= maxW / w; w = maxW; } }
                else       { if (h > maxH) { w *= maxH / h; h = maxH; } }
                canvas.width = w; canvas.height = h;
                ctx.drawImage(img, 0, 0, w, h);
                cb(canvas.toDataURL('image/jpeg', 0.85));
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    $(function () {
        const initial = document.getElementById('payment_method').value || 'bkash';
        selectPaymentMethod(initial);

        $('#avatar').on('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            if (file.size > 4 * 1024 * 1024) {
                alert('ছবির সাইজ ৪ MB এর বেশি।');
                this.value = '';
                return;
            }
            const $prev = $('#preview');
            const reader = new FileReader();
            reader.onload = ev => $prev.attr('src', ev.target.result);
            reader.readAsDataURL(file);
            setTimeout(() => resizeImage(file, src => $prev.attr('src', src)), 100);
        });

        $('input[name="contact_no"], input[name="mfs_no"], input[name="cash_contact"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        });

        $('input[name="from_year"], input[name="to_year"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
        });

        $('input[name="mfs_trn"], input[name="bank_reference"], input[name="cash_receipt_no"]').on('input', function () {
            this.value = this.value.toUpperCase();
        });
    });
</script>
@endpush