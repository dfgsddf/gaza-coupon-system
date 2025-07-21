@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>قائمة المستخدمين</h2>
        <table class="table">
            <thead>
            <tr>
                <th>الرقم</th>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الدور</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
