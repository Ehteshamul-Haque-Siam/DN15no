@extends('layouts.app')
@section('title', 'সদস্য রেজিস্ট্রেশন ফরম — ধানমন্ডি ১৫ নং')

@section('content')
<div class="container pb-5">

    {{-- Hero banner --}}
    <div class="reg-hero mb-4">
        <div class="container text-center">
            <h1>সদস্য রেজিস্ট্রেশন ফরম</h1>
            <p>ধানমন্ডি ১৫ নং গভঃ ষ্টাফ কোয়ার্টার্স প্রাক্তন নিবাসী ফোরাম</p>
        </div>
    </div>

    {{-- Error alert --}}
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

            {{-- ============================================================
                 LEFT COLUMN: Fields
            ============================================================ --}}
            <div class="col-lg-8">

                {{-- ========== SECTION 1: Personal Information ========== --}}
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
                                <label class="field-label">
                                    পূর্ণ নাম (English) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name_en" value="{{ old('name_en') }}"
                                       class="form-control @error('name_en') is-invalid @enderror"
                                       placeholder="e.g. Md. Rahim Uddin" required>
                                @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="field-label">
                                    পূর্ণ নাম (বাংলা) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name_bn" value="{{ old('name_bn') }}"
                                       class="form-control @error('name_bn') is-invalid @enderror"
                                       placeholder="যেমন: মোঃ রহিম উদ্দিন" required>
                                @error('name_bn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="field-label">
                                    ডাক নাম <span class="bn">(কলোনীতে)</span> <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nickname" value="{{ old('nickname') }}"
                                       class="form-control @error('nickname') is-invalid @enderror"
                                       placeholder="যেমন: রহিম ভাই" required>
                                @error('nickname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-3 opacity-25">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label">
                                    পিতার নাম (English) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="father_en" value="{{ old('father_en') }}"
                                       class="form-control" placeholder="Father's name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">
                                    পিতার নাম (বাংলা) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="father_bn" value="{{ old('father_bn') }}"
                                       class="form-control" placeholder="পিতার নাম" required>
                            </div>

                            <div class="col-md-6">
                                <label class="field-label">
                                    মাতার নাম (English) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="mother_en" value="{{ old('mother_en') }}"
                                       class="form-control" placeholder="Mother's name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">
                                    মাতার নাম (বাংলা) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="mother_bn" value="{{ old('mother_bn') }}"
                                       class="form-control" placeholder="মাতার নাম" required>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ========== SECTION 2: Residence & Contact ========== --}}
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
                            <div class="col-md-6">
                                <label class="field-label">
                                    বিল্ডিং / ফ্ল্যাট নং <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="flat" value="{{ old('flat') }}"
                                       class="form-control" placeholder="যেমন: ১৫/বি-৪" required>
                                <div class="field-hint">কলোনীতে আপনার বসবাসের ঠিকানা</div>
                            </div>

                            <div class="col-md-6">
                                <label class="field-label">
                                    অবস্থানের সময়কাল <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" name="from_year" value="{{ old('from_year') }}"
                                           class="form-control" placeholder="শুরু" required>
                                    <span class="input-group-text">থেকে</span>
                                    <input type="text" name="to_year" value="{{ old('to_year') }}"
                                           class="form-control" placeholder="শেষ" required>
                                </div>
                                <div class="field-hint">যেমন: ২০০০ থেকে ২০০৬</div>
                            </div>

                            <div class="col-12">
                                <label class="field-label">বর্তমান ঠিকানা</label>
                                <textarea name="present_add" rows="2" class="form-control"
                                          placeholder="বর্তমানে কোথায় বসবাস করছেন (ঐচ্ছিক)">{{ old('present_add') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="field-label">
                                    মোবাইল নম্বর <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="contact_no" value="{{ old('contact_no') }}"
                                       class="form-control" placeholder="01XXXXXXXXX" required>
                                <div class="field-hint">যেমন: 01712345678</div>
                            </div>

                            <div class="col-md-6">
                                <label class="field-label">পেশা</label>
                                <input type="text" name="occupation" value="{{ old('occupation') }}"
                                       class="form-control" placeholder="যেমন: সরকারি চাকরি / ব্যবসা">
                            </div>

                            <div class="col-12">
                                <label class="field-label">ইমেইল <span class="bn">(ঐচ্ছিক)</span></label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="form-control" placeholder="you@example.com">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ========== SECTION 3: Payment ========== --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="step-badge">3</div>
                        <div>
                            <h5>বার্ষিক চাঁদা পরিশোধ</h5>
                            <small>Annual Membership Fee</small>
                        </div>
                    </div>
                    <div class="form-card-body">

                        <div class="pay-guide mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <div class="text-muted small">পরিশোধযোগ্য পরিমাণ</div>
                                    <div class="bkash-amount">৳ ১,০২০</div>
                                </div>

                                <a href="https://shop.bkash.com/oitijjo01761983617/pay/bdt1020/XpxHaY"
                                   target="_blank"
                                   rel="noopener"
                                   class="btn rounded-pill px-4 d-inline-flex align-items-center gap-2"
                                   style="background:#E2136E;color:#fff;font-weight:600;">
                                    <i class="fa fa-mobile" style="font-size:18px;"></i>
                                    Pay Now with bKash
                                </a>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            বিকাশ নাম্বার <strong>01761983617</strong> এ <em>"Make Payment"</em> করুন
                            (ক্যাশ আউট চার্জ সহ)। পেমেন্টের পর নিচের তথ্য পূরণ করুন।
                        </p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label">
                                    বিকাশ নাম্বার <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="mfs_no" value="{{ old('mfs_no') }}"
                                       class="form-control" placeholder="যে নাম্বার থেকে পাঠিয়েছেন" required>
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">
                                    Transaction ID (TRN) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="mfs_trn" value="{{ old('mfs_trn') }}"
                                       class="form-control" placeholder="যেমন: 9AB1C2D3E4" required>
                                <div class="field-hint">বিকাশ SMS থেকে TRN কপি করুন</div>
                            </div>
                        </div>

                        <hr class="my-3 opacity-25">

                        <h6 class="text-muted mb-3">কীভাবে পেমেন্ট করবেন (USSD)</h6>
                        <div class="row g-3 justify-content-center">
                            <div class="col-8 col-md-5">
                                <div class="text-center">
                                    <img src="{{ asset('images/bkash-ussd.png') }}"
                                         class="pay-guide-img"
                                         alt="*247#"
                                         onerror="this.style.display='none'; this.parentNode.style.display='none';">
                                    <div class="small text-muted mt-1">
                                        Dial *247# → Make Payment
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ============================================================
                 RIGHT COLUMN: Photo + Submit
            ============================================================ --}}
            <div class="col-lg-4">

                {{-- Photo upload --}}
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
                            <img id="preview"
                                 src="{{ asset('images/avatar.png') }}"
                                 alt="Preview">
                            <div class="cta">
                                <i class="fa fa-camera"></i> ছবি নির্বাচন করুন
                            </div>
                            <div class="meta">JPG / PNG · সর্বোচ্চ ৪ MB</div>
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/*">

                    </div>
                </div>

                {{-- Trust note --}}
                <div class="alert alert-light border small mb-3">
                    <i class="fa fa-shield text-success"></i>
                    আপনার তথ্য সম্পূর্ণ নিরাপদ। শুধুমাত্র অ্যাডমিন যাচাইয়ের জন্য ব্যবহৃত হবে।
                </div>

                {{-- Submit --}}
                <div class="submit-bar">
                    <div>
                        <div class="small text-danger">* চিহ্নিত ঘরগুলো অবশ্যই পূরণ করুন</div>
                        <div class="small text-muted">
                            সাবমিটের পর ২৪-৪৮ ঘণ্টার মধ্যে SMS পাবেন
                        </div>
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
    // ---- Photo live preview + client-side resize ----
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
        $('#avatar').on('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            // Size guard
            if (file.size > 4 * 1024 * 1024) {
                alert('ছবির সাইজ ৪ MB এর বেশি। ছোট ছবি দিন।');
                this.value = '';
                return;
            }

            const $prev = $('#preview');
            const reader = new FileReader();
            reader.onload = ev => $prev.attr('src', ev.target.result);
            reader.readAsDataURL(file);

            // Resize after preview
            setTimeout(() => resizeImage(file, src => $prev.attr('src', src)), 100);
        });

        // Auto-format mobile numbers
        $('input[name="contact_no"], input[name="mfs_no"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        });

        // Limit year fields to 4 digits
        $('input[name="from_year"], input[name="to_year"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
        });

        // TRN uppercase
        $('input[name="mfs_trn"]').on('input', function () {
            this.value = this.value.toUpperCase();
        });
    });
</script>
@endpush