<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Bank Name *</label>
        <input name="bank_name" class="form-control" required
               value="{{ old('bank_name', $bank->bank_name ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Account Name *</label>
        <input name="account_name" class="form-control" required
               value="{{ old('account_name', $bank->account_name ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Account Number *</label>
        <input name="account_number" class="form-control" required
               value="{{ old('account_number', $bank->account_number ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Branch</label>
        <input name="branch" class="form-control"
               value="{{ old('branch', $bank->branch ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Routing Number</label>
        <input name="routing_number" class="form-control"
               value="{{ old('routing_number', $bank->routing_number ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">SWIFT Code</label>
        <input name="swift_code" class="form-control"
               value="{{ old('swift_code', $bank->swift_code ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Instructions (shown to users)</label>
        <textarea name="instructions" rows="2" class="form-control">{{ old('instructions', $bank->instructions ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $bank->is_active ?? true))>
            <label class="form-check-label">Active</label>
        </div>
    </div>
</div>