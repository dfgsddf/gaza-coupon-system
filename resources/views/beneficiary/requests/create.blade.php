@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h4>Create New Request</h4>

        <form method="POST" action="{{ route('requests.store') }}">
            @csrf

            <div class="mb-3">
                <label for="type" class="form-label">Request Type</label>
                <select class="form-select" name="type" required>
                    <option value="Monthly">Monthly</option>
                    <option value="Emergency">Emergency</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="details" class="form-label">Details</label>
                <textarea class="form-control" name="details" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </div>
@endsection
