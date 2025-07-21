@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Your Requests</h4>
            <a href="{{ route('requests.create') }}" class="btn btn-primary">New Request</a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($requests->count())
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Details</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ ucfirst($request->type) }}</td>
                                <td>{{ $request->description ?? '-' }}</td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge
                                        @if($request->status == 'approved') bg-success
                                        @elseif($request->status == 'rejected') bg-danger
                                        @else bg-warning text-dark @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">You have no requests yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
