@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit User</h2>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="beneficiary" {{ $user->role == 'beneficiary' ? 'selected' : '' }}>Beneficiary</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="store" {{ $user->role == 'store' ? 'selected' : '' }}>Store</option>
                    <option value="charity" {{ $user->role == 'charity' ? 'selected' : '' }}>Charity</option>
                </select>
            </div>
            <button class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
