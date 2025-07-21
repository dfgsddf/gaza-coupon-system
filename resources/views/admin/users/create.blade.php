@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create New User</h2>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="beneficiary">Beneficiary</option>
                    <option value="admin">Admin</option>
                    <option value="store">Store</option>
                    <option value="charity">Charity</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-success">Create</button>
        </form>
    </div>
@endsection
