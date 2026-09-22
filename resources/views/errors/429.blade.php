@extends('layouts.app')
@section('title', '429 — Too Many Requests')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-3 text-warning">429</h1>
    <p class="lead">অনেক বেশি অনুরোধ পাঠানো হয়েছে। কিছু সময় পর আবার চেষ্টা করুন।</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-2">← হোমপেজ</a>
</div>
@endsection