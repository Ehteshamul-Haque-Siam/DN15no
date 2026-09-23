@extends('layouts.app')
@section('title', 'Add Bank')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Add Bank Account</h3>
    <div class="card shadow-soft border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.banks.store') }}">
                @csrf
                @include('admin.banks._form', ['bank' => null])
                <button class="btn btn-primary mt-3"><i class="fa fa-save"></i> Save</button>
                <a href="{{ route('admin.banks.index') }}" class="btn btn-outline-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection