@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="container py-4">
    <h3>Edit User</h3>
    <div class="card shadow-soft">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ $user->name }}" required></div>
                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ $user->email }}" required></div>
                    <div class="col-md-6"><label class="form-label">Password (leave blank to keep)</label><input type="password" name="password" class="form-control"></div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            @foreach(['admin','moderator','super_admin'] as $r)
                                <option value="{{ $r }}" @selected($user->role===$r)>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Active</label>
                        <select name="is_active" class="form-select">
                            <option value="1" @selected($user->is_active)>Yes</option>
                            <option value="0" @selected(!$user->is_active)>No</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection