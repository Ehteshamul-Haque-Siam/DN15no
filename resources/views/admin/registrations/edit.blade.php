@extends('layouts.app')
@section('title', 'Edit ' . $registration->registration_id)

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h3 class="mb-0">Edit Registration</h3>
            <div class="text-muted small">
                <code>{{ $registration->registration_id }}</code>
                · Current Method:
                <span class="badge bg-{{ $registration->payment_method === 'bkash' ? 'danger' : ($registration->payment_method === 'bank' ? 'primary' : 'success') }}">
                    {{ $registration->payment_method_label }}
                </span>
            </div>
        </div>
        <a href="{{ route('admin.registrations.show', $registration->id) }}"
           class="btn btn-outline-secondary btn-sm">
            ← Cancel
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.registrations.update', $registration->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- LEFT: Main details --}}
            <div class="col-lg-8">

                {{-- Personal Information --}}
                <div class="card shadow-soft mb-3">
                    <div class="card-header"><strong>Personal Information</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name (English) *</label>
                                <input type="text" name="name_en"
                                       value="{{ old('name_en', $registration->name_en) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Name (বাংলা) *</label>
                                <input type="text" name="name_bn"
                                       value="{{ old('name_bn', $registration->name_bn) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nickname *</label>
                                <input type="text" name="nickname"
                                       value="{{ old('nickname', $registration->nickname) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile *</label>
                                <input type="text" name="contact_no"
                                       value="{{ old('contact_no', $registration->contact_no) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father (English) *</label>
                                <input type="text" name="father_en"
                                       value="{{ old('father_en', $registration->father_en) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father (বাংলা) *</label>
                                <input type="text" name="father_bn"
                                       value="{{ old('father_bn', $registration->father_bn) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mother (English) *</label>
                                <input type="text" name="mother_en"
                                       value="{{ old('mother_en', $registration->mother_en) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mother (বাংলা) *</label>
                                <input type="text" name="mother_bn"
                                       value="{{ old('mother_bn', $registration->mother_bn) }}"
                                       class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Residence & Contact --}}
                <div class="card shadow-soft mb-3">
                    <div class="card-header"><strong>Residence & Contact</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Building No *</label>
                                <input type="text" name="building_no"
                                       value="{{ old('building_no', $registration->building_no) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Flat No *</label>
                                <input type="text" name="flat_no"
                                       value="{{ old('flat_no', $registration->flat_no) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">From Year *</label>
                                <input type="text" name="from_year"
                                       value="{{ old('from_year', $registration->from_year) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">To Year *</label>
                                <input type="text" name="to_year"
                                       value="{{ old('to_year', $registration->to_year) }}"
                                       class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation</label>
                                <input type="text" name="occupation"
                                       value="{{ old('occupation', $registration->occupation) }}"
                                       class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Present Address</label>
                                <textarea name="present_add" rows="2"
                                          class="form-control">{{ old('present_add', $registration->present_add) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                       value="{{ old('email', $registration->email) }}"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment (method-aware) --}}
                <div class="card shadow-soft mb-3">
                    <div class="card-header">
                        <strong>Payment</strong>
                        <small class="text-muted ms-2">(admin can update fields below)</small>
                    </div>
                    <div class="card-body">

                        {{-- Payment method selector --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Payment Method *
                            </label>
                            <select name="payment_method" id="paymentMethod" class="form-select" onchange="switchMethod()">
                                <option value="bkash" @selected(old('payment_method', $registration->payment_method) === 'bkash')>
                                    bKash (Mobile Banking)
                                </option>
                                <option value="bank" @selected(old('payment_method', $registration->payment_method) === 'bank')>
                                    Bank Transfer
                                </option>
                                <option value="cash" @selected(old('payment_method', $registration->payment_method) === 'cash')>
                                    Cash
                                </option>
                            </select>
                            <small class="text-muted">
                                Changing method here will hide other method's data in the detail page, but won't erase existing data.
                            </small>
                        </div>

                        {{-- ================ bKash FIELDS ================ --}}
                        <div id="fields-bkash" class="method-fields">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">bKash Mobile</label>
                                    <input type="text" name="mfs_no"
                                           value="{{ old('mfs_no', $registration->mfs_no) }}"
                                           class="form-control" placeholder="01XXXXXXXXX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Transaction ID (TRN)</label>
                                    <input type="text" name="mfs_trn"
                                           value="{{ old('mfs_trn', $registration->mfs_trn) }}"
                                           class="form-control" placeholder="9AB1C2D3E4">
                                </div>
                            </div>
                        </div>

                        {{-- ================ BANK FIELDS ================ --}}
                        <div id="fields-bank" class="method-fields">
                            @php $banks = \App\Models\Bank::orderBy('display_order')->get(); @endphp

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Bank</label>
                                    <select name="bank_id" class="form-select">
                                        <option value="">— Select Bank —</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}"
                                                @selected(old('bank_id', $registration->bank_id) == $bank->id)>
                                                {{ $bank->bank_name }} · {{ $bank->account_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sender Account No</label>
                                    <input type="text" name="paid_to_bank"
                                           value="{{ old('paid_to_bank', $registration->paid_to_bank) }}"
                                           class="form-control" placeholder="Your sending account number">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Transaction Reference</label>
                                    <input type="text" name="bank_reference"
                                           value="{{ old('bank_reference', $registration->bank_reference) }}"
                                           class="form-control" placeholder="FT2508ABC123">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Bank Slip (JPG / PNG / PDF · 8MB)</label>
                                    <input type="file" name="bank_slip"
                                           class="form-control" accept="image/*,application/pdf">

                                    @if($registration->bank_slip)
                                        <div class="mt-2 small">
                                            <i class="fa fa-paperclip"></i>
                                            Current slip:
                                            <a href="{{ asset('storage/' . $registration->bank_slip) }}"
                                               target="_blank">
                                                View file
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ================ CASH FIELDS ================ --}}
                        <div id="fields-cash" class="method-fields">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Receipt No</label>
                                    <input type="text" name="cash_receipt_no"
                                           value="{{ old('cash_receipt_no', $registration->cash_receipt_no) }}"
                                           class="form-control" placeholder="C-2026-0042">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact (received by)</label>
                                    <input type="text" name="paid_to_bank"
                                           value="{{ old('paid_to_bank', $registration->paid_to_bank) }}"
                                           class="form-control" placeholder="01XXXXXXXXX">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Cash Receipt Upload (JPG / PNG / PDF · 8MB)</label>
                                    <input type="file" name="cash_receipt"
                                           class="form-control" accept="image/*,application/pdf">

                                    @if($registration->cash_receipt)
                                        <div class="mt-2 small">
                                            <i class="fa fa-paperclip"></i>
                                            Current receipt:
                                            <a href="{{ asset('storage/' . $registration->cash_receipt) }}"
                                               target="_blank">
                                                View file
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT: Photo + Remarks + Actions --}}
            <div class="col-lg-4">

                {{-- Photo --}}
                <div class="card shadow-soft mb-3">
                    <div class="card-header"><strong>Photo</strong></div>
                    <div class="card-body text-center">
                        @if($registration->hasPhoto())
                            <img src="{{ asset('storage/' . $registration->photo) }}"
                                 class="img-fluid rounded mb-3"
                                 style="max-height:200px;object-fit:cover;"
                                 alt="Current photo">
                        @else
                            <img src="{{ asset('images/avatar.png') }}"
                                 class="img-fluid rounded mb-3"
                                 style="max-height:200px;object-fit:cover;"
                                 alt="No photo">
                        @endif
                        <input type="file" name="photo" accept="image/*" class="form-control">
                        <small class="text-muted d-block mt-2">
                            Leave blank to keep existing photo
                        </small>
                    </div>
                </div>

                {{-- Admin Remarks --}}
                <div class="card shadow-soft mb-3">
                    <div class="card-header"><strong>Admin Remarks</strong></div>
                    <div class="card-body">
                        <textarea name="admin_remarks" rows="4" class="form-control"
                                  placeholder="Optional internal notes">{{ old('admin_remarks', $registration->admin_remarks) }}</textarea>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.registrations.show', $registration->id) }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    function switchMethod() {
        const selected = document.getElementById('paymentMethod').value;

        document.querySelectorAll('.method-fields').forEach(el => {
            el.style.display = 'none';
        });

        const target = document.getElementById('fields-' + selected);
        if (target) {
            target.style.display = 'block';
            target.style.animation = 'fadeIn .2s ease';
        }
    }

    // Initialise on load
    document.addEventListener('DOMContentLoaded', switchMethod);
</script>
@endpush