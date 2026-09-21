@extends('layouts.app')
@section('title', 'সদস্য ফরম')

@section('content')
<div class="container">
    <div class="text-center py-3 py-lg-5">
        <a href="#registration-form" class="text-decoration-none">
            <button class="shadow px-4 py-3 rounded btn btn-primary text-white btn-lg-mobile" style="font-size:22px;">
                সদস্য ফরম
            </button>
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form id="registration-form"
          action="{{ route('register.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        {{-- Personal Info + Photo --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-8 col-12">
                <div class="card shadow-soft h-100">
                    <div class="card-body">
                        {{-- 01 --}}
                        <div class="row mb-3">
                            <label class="col-12 col-form-label">01. Full Name (পূর্ণ নাম) <span class="required-asterisk">*</span></label>
                            <div class="col-12 mb-2">
                                <input type="text" name="name_en" value="{{ old('name_en') }}"
                                       class="form-control @error('name_en') is-invalid @enderror"
                                       placeholder="English" required>
                                @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <input type="text" name="name_bn" value="{{ old('name_bn') }}"
                                       class="form-control @error('name_bn') is-invalid @enderror"
                                       placeholder="বাংলা" required>
                                @error('name_bn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- 02 --}}
                        <div class="row mb-3">
                            <label class="col-12 col-md-6 col-form-label">02. Nickname (কলোনীতে ডাক নাম) <span class="required-asterisk">*</span></label>
                            <div class="col-12 col-md-6">
                                <input type="text" name="nickname" value="{{ old('nickname') }}"
                                       class="form-control @error('nickname') is-invalid @enderror" required>
                                @error('nickname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- 03 --}}
                        <div class="row mb-3">
                            <label class="col-12 col-form-label">03. Father's Name (পিতার নাম) <span class="required-asterisk">*</span></label>
                            <div class="col-12 mb-2">
                                <input type="text" name="father_en" value="{{ old('father_en') }}" class="form-control" placeholder="English" required>
                            </div>
                            <div class="col-12">
                                <input type="text" name="father_bn" value="{{ old('father_bn') }}" class="form-control" placeholder="বাংলা" required>
                            </div>
                        </div>

                        {{-- 04 --}}
                        <div class="row mb-3">
                            <label class="col-12 col-form-label">04. Mother's Name (মাতার নাম) <span class="required-asterisk">*</span></label>
                            <div class="col-12 mb-2">
                                <input type="text" name="mother_en" value="{{ old('mother_en') }}" class="form-control" placeholder="English" required>
                            </div>
                            <div class="col-12">
                                <input type="text" name="mother_bn" value="{{ old('mother_bn') }}" class="form-control" placeholder="বাংলা" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Photo --}}
            <div class="col-lg-4 col-12">
                <div class="card shadow-soft h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <h5>Upload Your Photo</h5>
                        <label for="avatar" class="my-3">
                            <img id="preview" src="{{ asset('images/avatar.png') }}" alt="Default Avatar">
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/*">
                        <small class="text-muted">JPG/PNG, max 4MB</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Residence / Contact --}}
        <div class="card shadow-soft mb-3">
            <div class="card-body">
                {{-- 05 --}}
                <div class="row mb-3">
                    <label class="col-12 col-md-4 col-lg-3 col-form-label">05. Building / Flat no <span class="required-asterisk">*</span></label>
                    <div class="col-12 col-md-8 col-lg-4">
                        <input type="text" name="flat" value="{{ old('flat') }}" class="form-control" required>
                    </div>
                </div>

                {{-- 06 --}}
                <div class="row mb-3">
                    <label class="col-12 col-lg-7 col-form-label">06. Duration of Staying (অবস্থানের সময়) <span class="required-asterisk">*</span></label>
                    <div class="col-6 col-lg-2 mb-2 mb-lg-0">
                        <input type="text" name="from_year" value="{{ old('from_year') }}" class="form-control" placeholder="From" required>
                    </div>
                    <div class="col-6 col-lg-1 text-center d-flex align-items-center justify-content-center">
                        <label class="mb-0">থেকে</label>
                    </div>
                    <div class="col-12 col-lg-2">
                        <input type="text" name="to_year" value="{{ old('to_year') }}" class="form-control" placeholder="To" required>
                    </div>
                </div>

                {{-- 07 --}}
                <div class="row mb-3">
                    <label class="col-12 col-form-label">07. Present Address (বর্তমান ঠিকানা)</label>
                    <div class="col-12">
                        <textarea name="present_add" rows="3" class="form-control">{{ old('present_add') }}</textarea>
                    </div>
                </div>

                {{-- 08 --}}
                <div class="row mb-3">
                    <label class="col-12 col-form-label">08. Contact No (ফোন নম্বর) <span class="required-asterisk">*</span></label>
                    <div class="col-12">
                        <input type="text" name="contact_no" value="{{ old('contact_no') }}" class="form-control" placeholder="01XXXXXXXXX" required>
                    </div>
                </div>

                {{-- 09 --}}
                <div class="row mb-3">
                    <label class="col-12 col-form-label">09. Occupation (পেশা)</label>
                    <div class="col-12">
                        <input type="text" name="occupation" value="{{ old('occupation') }}" class="form-control">
                    </div>
                </div>

                {{-- 10 --}}
                <div class="row mb-3">
                    <label class="col-12 col-form-label">10. Email (ইমেইল) ঐচ্ছিক</label>
                    <div class="col-12">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                    </div>
                </div>

                {{-- 11 --}}
                <div class="row mb-3">
                    <label class="col-12 col-form-label">
                        11. Annual Membership Fee (বার্ষিক চাঁদা)
                        <span class="p-1 text-success"><strong>1020Tk</strong></span>
                    </label>
                    <div class="col-12">
                        <input type="hidden" name="mode_of_payment" value="mfs">
                        <a href="https://shop.bkash.com/oitijjo01761983617/pay/bdt1020/XpxHaY"
                           target="_blank" class="text-decoration-none d-inline-block mb-2">
                            Pay Now &nbsp;
                            <img src="https://business.bkash.com/img/header-bkash-icon.d8af3614.png" style="width:120px;" alt="bKash">
                        </a>
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label small">bKash (বিকাশ)</label>
                                <input type="text" name="mfs_no" value="{{ old('mfs_no') }}" class="form-control" placeholder="Mobile No" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small">&nbsp;</label>
                                <input type="text" name="mfs_trn" value="{{ old('mfs_trn') }}" class="form-control" placeholder="Transaction ID" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 12 --}}
                <div class="row mb-3">
                    <label class="col-12 col-md-2 col-form-label">12. Date</label>
                    <div class="col-12 col-md-4 col-lg-3">
                        <input type="text" class="form-control" value="{{ date('d-m-Y') }}" readonly>
                    </div>
                </div>

                {{-- bKash Guide --}}
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">বিকাশঃ</h5></div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>01761983617</strong><br>
                            <small>(ক্যাশ আউট চার্জ সহ মার্চেন্ট নাম্বারে "Make Payment" করুন)</small>
                        </p>
                        <div class="row g-3">
                            <div class="col-12 col-md-6 col-lg-4">
                                <h6>From bKash App</h6>
                                <img src="{{ asset('images/bkash-app.png') }}" class="img-fluid" alt="bKash App">
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <h6>Dialing *247#</h6>
                                <img src="{{ asset('images/bkash-ussd.png') }}" class="img-fluid" alt="bKash USSD">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="row align-items-center g-3">
                    <div class="col-12 col-lg-8">
                        <h6 class="mt-2 mb-0"><span class="required-asterisk">*</span> <i>Marked fields are required.</i></h6>
                    </div>
                    <div class="col-12 col-lg-4">
                        <button type="submit" class="w-100 btn btn-warning btn-lg-mobile py-2">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
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
            const $prev = $('#preview');
            const reader = new FileReader();
            reader.onload = ev => $prev.attr('src', ev.target.result);
            reader.readAsDataURL(file);
            setTimeout(() => resizeImage(file, src => $prev.attr('src', src)), 100);
        });
    });
</script>
@endpush