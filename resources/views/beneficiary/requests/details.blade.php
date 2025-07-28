@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-primary">
                        <i class="fa-solid fa-file-alt me-2"></i>
                        تفاصيل الطلب
                    </h3>
                    <table class="table table-bordered">
                        <tr>
                            <th>رقم الطلب</th>
                            <td>{{ $request->id }}</td>
                        </tr>
                        <tr>
                            <th>نوع الطلب</th>
                            <td>{{ ucfirst($request->type) }}</td>
                        </tr>
                        <tr>
                            <th>الحالة</th>
                            <td>
                                <span class="badge
                                    @if($request->status == 'approved') bg-success
                                    @elseif($request->status == 'rejected') bg-danger
                                    @else bg-warning text-dark @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>تفاصيل الطلب</th>
                            <td>{{ $request->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الإنشاء</th>
                            <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                    <div class="mt-4 text-end">
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-arrow-right"></i> العودة لقائمة الطلبات
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 