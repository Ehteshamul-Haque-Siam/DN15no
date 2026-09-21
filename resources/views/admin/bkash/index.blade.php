@extends('layouts.app')
@section('title', 'bKash Settings')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h3 class="mb-0">bKash Credentials</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card shadow-soft">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.bkash.update') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">App Key</label>
                        <input type="text" name="app_key" class="form-control" value="{{ old('app_key', $credential->app_key ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">App Secret</label>
                        <input type="text" name="app_secret" class="form-control" value="{{ old('app_secret', $credential->app_secret ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $credential->username ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" value="{{ old('password', $credential->password ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Merchant Number</label>
                        <input type="text" name="merchant_number" class="form-control" value="{{ old('merchant_number', $credential->merchant_number ?? '01761983617') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Environment</label>
                        <select name="environment" class="form-select">
                            <option value="sandbox" @selected(($credential->environment ?? '')==='sandbox')>Sandbox</option>
                            <option value="live" @selected(($credential->environment ?? '')==='live')>Live</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Save Credentials</button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.bkash.test') }}" class="mt-2">
                @csrf
                <button class="btn btn-outline-secondary">Test Connection</button>
            </form>
        </div>
    </div>
</div>
@endsection