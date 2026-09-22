@extends('layouts.app')
@section('title', '419 — Session Expired')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-3 text-secondary">419</h1>
    <p class="lead">সেশনের সময় শেষ। পেজটি রিফ্রেশ করে আবার চেষ্টা করুন।</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary mt-2">← ফিরে যান</a>
</div>
@endsection