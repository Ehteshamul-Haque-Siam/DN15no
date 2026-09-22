@extends('layouts.app')
@section('title', '500 — Server Error')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-3 text-danger">500</h1>
    <p class="lead">সার্ভারে একটি সমস্যা হয়েছে। আমরা এটি দেখছি।</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-2">← হোমপেজ</a>
</div>
@endsection