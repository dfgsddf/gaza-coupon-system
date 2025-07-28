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
        $requests = RequestModel::latest()->get();
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
        try {
            $validated = $request->validate([
                'type' => 'required|in:monthly,urgent,emergency',
                'description' => 'nullable|string|max:1000',
            ]);

            $newRequest = RequestModel::create([
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'status' => 'processing',
                'description' => $validated['description'],
            ]);

            \Log::info('Request created successfully', [
                'request_id' => $newRequest->id,
                'user_id' => Auth::id(),
                'type' => $validated['type']
            ]);

            return redirect()->route('requests.details', $newRequest->id)
                ->with('success', 'تم إرسال الطلب بنجاح!');

        } catch (\Exception $e) {
            \Log::error('Error creating request', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.']);
        }
    }

    // عرض تفاصيل الطلب
    public function details(RequestModel $request)
    {
        // حماية: السماح فقط لصاحب الطلب
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }
        return view('beneficiary.requests.details', compact('request'));
    }
}
