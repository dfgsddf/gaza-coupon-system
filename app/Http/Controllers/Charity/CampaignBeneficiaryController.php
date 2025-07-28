<?php

namespace App\Http\Controllers\Charity;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignBeneficiary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaignBeneficiaryController extends Controller
{
    /**
     * عرض قائمة المستفيدين في الحملة
     */
    public function index($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        
        // التحقق من أن الحملة تخص الجمعية الحالية
        if ($campaign->charity_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الحملة');
        }

        $beneficiaries = $campaign->beneficiaries()
            ->with('beneficiary')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => $campaign->beneficiaries()->count(),
            'approved' => $campaign->approvedBeneficiaries()->count(),
            'pending' => $campaign->pendingBeneficiaries()->count(),
            'rejected' => $campaign->beneficiaries()->where('status', 'rejected')->count(),
            'completed' => $campaign->beneficiaries()->where('status', 'completed')->count(),
        ];

        return view('charity.campaigns.beneficiaries.index', compact('campaign', 'beneficiaries', 'stats'));
    }

    /**
     * عرض صفحة إضافة مستفيدين للحملة
     */
    public function create($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        
        // التحقق من أن الحملة تخص الجمعية الحالية
        if ($campaign->charity_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الحملة');
        }

        // جلب المستفيدين المتاحين (غير مضافين للحملة)
        $availableBeneficiaries = User::where('role', 'beneficiary')
            ->whereNotIn('id', $campaign->beneficiaries()->pluck('beneficiary_id'))
            ->with('beneficiaryProfile')
            ->get();

        return view('charity.campaigns.beneficiaries.create', compact('campaign', 'availableBeneficiaries'));
    }

    /**
     * حفظ المستفيدين المحددين للحملة
     */
    public function store(Request $request, $campaignId)
    {
        $request->validate([
            'beneficiary_ids' => 'required|array|min:1',
            'beneficiary_ids.*' => 'exists:users,id',
            'allocated_amounts' => 'nullable|array',
            'allocated_amounts.*' => 'nullable|numeric|min:0',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:500',
        ]);

        $campaign = Campaign::findOrFail($campaignId);
        
        // التحقق من أن الحملة تخص الجمعية الحالية
        if ($campaign->charity_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الحملة');
        }

        DB::beginTransaction();
        try {
            foreach ($request->beneficiary_ids as $index => $beneficiaryId) {
                // التحقق من أن المستفيد غير مضاف مسبقاً
                $exists = $campaign->beneficiaries()
                    ->where('beneficiary_id', $beneficiaryId)
                    ->exists();

                if (!$exists) {
                    CampaignBeneficiary::create([
                        'campaign_id' => $campaignId,
                        'beneficiary_id' => $beneficiaryId,
                        'status' => 'pending',
                        'allocated_amount' => $request->allocated_amounts[$index] ?? null,
                        'notes' => $request->notes[$index] ?? null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('charity.campaigns.beneficiaries.index', $campaignId)
                ->with('success', 'تم إضافة المستفيدين للحملة بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'حدث خطأ أثناء إضافة المستفيدين');
        }
    }

    /**
     * عرض تفاصيل مستفيد في الحملة
     */
    public function show($campaignId, $beneficiaryId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $campaignBeneficiary = $campaign->beneficiaries()
            ->where('beneficiary_id', $beneficiaryId)
            ->with('beneficiary.beneficiaryProfile')
            ->firstOrFail();

        return view('charity.campaigns.beneficiaries.show', compact('campaign', 'campaignBeneficiary'));
    }

    /**
     * تحديث حالة المستفيد في الحملة
     */
    public function updateStatus(Request $request, $campaignId, $beneficiaryId)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'allocated_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $campaign = Campaign::findOrFail($campaignId);
        
        // التحقق من أن الحملة تخص الجمعية الحالية
        if ($campaign->charity_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الحملة');
        }

        $campaignBeneficiary = $campaign->beneficiaries()
            ->where('beneficiary_id', $beneficiaryId)
            ->firstOrFail();

        $updateData = [
            'status' => $request->status,
            'allocated_amount' => $request->allocated_amount,
            'notes' => $request->notes,
        ];

        // تحديث وقت الموافقة إذا تمت الموافقة
        if ($request->status === 'approved' && $campaignBeneficiary->status !== 'approved') {
            $updateData['approved_at'] = now();
        }

        // تحديث وقت الإكمال إذا تم الإكمال
        if ($request->status === 'completed' && $campaignBeneficiary->status !== 'completed') {
            $updateData['completed_at'] = now();
        }

        $campaignBeneficiary->update($updateData);

        return back()->with('success', 'تم تحديث حالة المستفيد بنجاح');
    }

    /**
     * حذف مستفيد من الحملة
     */
    public function destroy($campaignId, $beneficiaryId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        
        // التحقق من أن الحملة تخص الجمعية الحالية
        if ($campaign->charity_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الحملة');
        }

        $campaignBeneficiary = $campaign->beneficiaries()
            ->where('beneficiary_id', $beneficiaryId)
            ->firstOrFail();

        $campaignBeneficiary->delete();

        return back()->with('success', 'تم حذف المستفيد من الحملة بنجاح');
    }

    /**
     * البحث في مستفيدين الحملة
     */
    public function search(Request $request, $campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        
        // التحقق من أن الحملة تخص الجمعية الحالية
        if ($campaign->charity_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول لهذه الحملة');
        }

        $query = $campaign->beneficiaries()->with('beneficiary');

        // البحث بالاسم
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('beneficiary', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // التصفية بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $beneficiaries = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('charity.campaigns.beneficiaries.index', compact('campaign', 'beneficiaries'));
    }
} 