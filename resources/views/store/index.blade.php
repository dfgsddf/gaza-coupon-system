@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 text-center">قائمة المتاجر</h3>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>الرقم</th>
                            <th>اسم المتجر</th>
                            <th>البريد الإلكتروني</th>
                            <th>العنوان</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $index => $store)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $store->name }}</td>
                            <td>{{ $store->email }}</td>
                            <td>{{ $store->address ?? '-' }}</td>
                            <td>
                                @if($store->status === 'active')
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-secondary">{{ $store->status ?? 'غير محدد' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">لا يوجد متاجر حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection



