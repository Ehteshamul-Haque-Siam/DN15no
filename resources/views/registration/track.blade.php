@extends('layouts.app')
@section('title', 'রেজিস্ট্রেশন ট্র্যাক')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="card shadow-soft">
                <div class="card-body">
                    <h4 class="mb-3">রেজিস্ট্রেশন ট্র্যাক করুন</h4>
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('register.track.post') }}">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Reg ID অথবা মোবাইল নম্বর" required>
                            <button class="btn btn-primary">খুঁজুন</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection