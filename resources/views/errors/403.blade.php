@extends('layouts.app')
@section('title', '403 — Forbidden')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-3 text-danger">403</h1>
    <p class="lead">এই পেজে আপনার প্রবেশাধিকার নেই।</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-2">← হোমপেজে ফিরে যান</a>
</div>
@endsection