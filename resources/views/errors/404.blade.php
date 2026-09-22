@extends('layouts.app')
@section('title', '404 — Not Found')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-3 text-warning">404</h1>
    <p class="lead">এই পেজটি খুঁজে পাওয়া যায়নি।</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-2">← হোমপেজে ফিরে যান</a>
</div>
@endsection