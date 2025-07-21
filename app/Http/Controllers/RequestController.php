<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Models\RequestModel;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    // عرض الطلبات الخاصة بالمستفيد الحالي
    public function index()
    {
        $requests = RequestModel::where('user_id', Auth::id())->latest()->get();
        return view('beneficiary.requests.index', compact('requests'));
    }

    // عرض نموذج إنشاء طلب
    public function create()
    {
        return view('beneficiary.requests.create');
    }

    // تخزين الطلب الجديد
    public function store(HttpRequest $request)
    {
        $request->validate([
            'type' => 'required|in:monthly,urgent,emergency',
        ]);

        RequestModel::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'status' => 'processing',
        ]);

        return redirect()->route('requests.index')->with('success', 'Request submitted successfully.');
    }
}
