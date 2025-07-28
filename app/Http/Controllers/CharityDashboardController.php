<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\RequestModel;
use App\Models\Campaign;
use App\Models\CampaignDonation;
use App\Models\CharityReport;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class CharityDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.dashboard.view')) {
            abort(403, 'ليس لديك صلاحية لعرض لوحة التحكم');
        }

        $charityId = Auth::id();
        
        // Get campaign statistics
        $campaignsCount = Campaign::where('charity_id', $charityId)->count();
        $activeCampaignsCount = Campaign::where('charity_id', $charityId)->where('status', 'active')->count();
        $totalDonations = CampaignDonation::whereHas('campaign', function($query) use ($charityId) {
            $query->where('charity_id', $charityId);
        })->sum('amount');
        
        // Get request statistics
        $totalRequests = RequestModel::count();
        $pendingRequests = RequestModel::where('status', 'pending')->count();
        $approvedRequests = RequestModel::where('status', 'approved')->count();
        
        // Get recent data
        $recentCampaigns = Campaign::where('charity_id', $charityId)->latest()->take(5)->get();
        $recentDonations = CampaignDonation::whereHas('campaign', function($query) use ($charityId) {
            $query->where('charity_id', $charityId);
        })->with(['campaign', 'donor'])->latest()->take(5)->get();
        $recentRequests = RequestModel::with('user')->latest()->take(5)->get();

        return view('charity.dashboard', compact(
            'campaignsCount',
            'activeCampaignsCount', 
            'totalDonations',
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'recentCampaigns',
            'recentDonations',
            'recentRequests'
        ));
    }

    public function getStats()
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.dashboard.stats')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض الإحصائيات'
            ], 403);
        }

        $charityId = Auth::id();
        
        // Campaign statistics
        $campaignStats = [
            'total' => Campaign::where('charity_id', $charityId)->count(),
            'active' => Campaign::where('charity_id', $charityId)->where('status', 'active')->count(),
            'completed' => Campaign::where('charity_id', $charityId)->where('status', 'completed')->count(),
            'total_goal' => Campaign::where('charity_id', $charityId)->sum('goal'),
            'total_raised' => Campaign::where('charity_id', $charityId)->sum('current_amount'),
        ];

        // Donation statistics
        $donationStats = [
            'total_donations' => CampaignDonation::whereHas('campaign', function($query) use ($charityId) {
                $query->where('charity_id', $charityId);
            })->count(),
            'total_amount' => CampaignDonation::whereHas('campaign', function($query) use ($charityId) {
                $query->where('charity_id', $charityId);
            })->sum('amount'),
            'average_donation' => CampaignDonation::whereHas('campaign', function($query) use ($charityId) {
                $query->where('charity_id', $charityId);
            })->avg('amount'),
        ];

        // Request statistics
        $requestStats = [
            'total' => RequestModel::count(),
            'pending' => RequestModel::where('status', 'pending')->count(),
            'approved' => RequestModel::where('status', 'approved')->count(),
            'rejected' => RequestModel::where('status', 'rejected')->count(),
        ];

        return response()->json([
            'success' => true,
            'campaign_stats' => $campaignStats,
            'donation_stats' => $donationStats,
            'request_stats' => $requestStats,
        ]);
    }

    public function getRecentRequests()
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.requests.view')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض الطلبات'
            ], 403);
        }

        $recentRequests = RequestModel::with('user')->latest()->take(5)->get()->map(function($req) {
            return [
                'num' => $req->id,
                'name' => $req->user ? $req->user->name : 'Unknown',
                'category' => ucfirst($req->type),
                'date' => $req->created_at->format('M d, Y'),
                'status' => $req->status,
            ];
        });
        return response()->json(['success' => true, 'recentRequests' => $recentRequests]);
    }

    public function getRequestDetails($num)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.requests.details')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض تفاصيل الطلبات'
            ], 403);
        }

        $req = RequestModel::with('user')->findOrFail($num);
        $details = [
            'num' => $req->id,
            'name' => $req->user ? $req->user->name : 'Unknown',
            'category' => ucfirst($req->type),
            'date' => $req->created_at->format('M d, Y'),
            'status' => $req->status,
            'description' => 'Request details for ' . ($req->user ? $req->user->name : 'Unknown'),
        ];
        return view('charity.partials.request-details', compact('details'))->render();
    }

    public function campaigns()
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.campaigns.view')) {
            abort(403, 'ليس لديك صلاحية لعرض الحملات');
        }

        $charityId = Auth::id();
        $campaigns = Campaign::where('charity_id', $charityId)
            ->withCount('donations')
            ->withSum('donations', 'amount')
            ->latest()
            ->get();

        return view('charity.campaigns', compact('campaigns'));
    }

    public function storeCampaignAjax(Request $request)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.campaigns.create')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لإنشاء حملات جديدة'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'goal' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_featured' => 'boolean',
        ], [
            'name.required' => 'اسم الحملة مطلوب',
            'goal.required' => 'الهدف المالي مطلوب',
            'goal.numeric' => 'الهدف المالي يجب أن يكون رقماً',
            'goal.min' => 'الهدف المالي يجب أن يكون أكبر من صفر',
            'end_date.after' => 'تاريخ الانتهاء يجب أن يكون بعد تاريخ البداية',
        ]);

        try {
            $campaign = Campaign::create([
                'name' => $request->name,
                'description' => $request->description,
                'goal' => $request->goal,
                'current_amount' => 0,
                'status' => 'active',
                'charity_id' => Auth::id(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_featured' => $request->boolean('is_featured', false),
                'donors_count' => 0,
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'تم إنشاء الحملة بنجاح!', 
                'campaign' => $campaign
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الحملة'
            ], 500);
        }
    }

    public function updateCampaign(Request $request, $id)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.campaigns.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لتعديل الحملات'
            ], 403);
        }

        $campaign = Campaign::where('charity_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'goal' => 'required|numeric|min:0',
            'status' => 'required|in:active,paused,completed,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_featured' => 'boolean',
        ]);

        try {
            $campaign->update($request->only([
                'name', 'description', 'goal', 'status', 
                'start_date', 'end_date', 'is_featured'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحملة بنجاح!',
                'campaign' => $campaign
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الحملة'
            ], 500);
        }
    }

    public function deleteCampaign($id)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.campaigns.delete')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لحذف الحملات'
            ], 403);
        }

        $campaign = Campaign::where('charity_id', Auth::id())->findOrFail($id);

        try {
            $campaign->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الحملة بنجاح!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الحملة'
            ], 500);
        }
    }

    public function getCampaignDonations($campaignId)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.campaigns.view')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لعرض التبرعات'
            ], 403);
        }

        $campaign = Campaign::where('charity_id', Auth::id())->findOrFail($campaignId);
        $donations = $campaign->donations()
            ->with('donor')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'donations' => $donations
        ]);
    }

    public function reports()
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.reports.view')) {
            abort(403, 'ليس لديك صلاحية لعرض التقارير');
        }

        $charityId = Auth::id();
        
        // Get report statistics
        $totalReports = CharityReport::where('charity_id', $charityId)->count();
        $recentReports = CharityReport::where('charity_id', $charityId)
            ->latest()
            ->take(5)
            ->get();

        // Get campaign performance data
        $campaignPerformance = Campaign::where('charity_id', $charityId)
            ->select('name', 'goal', 'current_amount', 'donors_count')
            ->get()
            ->map(function($campaign) {
                return [
                    'name' => $campaign->name,
                    'goal' => $campaign->goal,
                    'raised' => $campaign->current_amount,
                    'progress' => $campaign->progress_percentage,
                    'donors_count' => $campaign->donors_count,
                ];
            });

        // Get donation trends (last 6 months)
        $donationTrends = CampaignDonation::whereHas('campaign', function($query) use ($charityId) {
            $query->where('charity_id', $charityId);
        })
        ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        return view('charity.reports', compact(
            'totalReports',
            'recentReports',
            'campaignPerformance',
            'donationTrends'
        ));
    }

    public function generateReport(Request $request)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.reports.generate')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لإنشاء التقارير'
            ], 403);
        }

        $request->validate([
            'report_type' => 'required|in:campaign_summary,donation_analysis,financial_report,request_summary',
            'title' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $charityId = Auth::id();
        $reportType = $request->report_type;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        try {
            $reportData = $this->generateReportData($reportType, $charityId, $startDate, $endDate);

            $report = CharityReport::create([
                'charity_id' => $charityId,
                'report_type' => $reportType,
                'title' => $request->title,
                'description' => $request->description ?? null,
                'data' => $reportData,
                'report_date' => Carbon::now(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'generated',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء التقرير بنجاح!',
                'report' => $report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء التقرير'
            ], 500);
        }
    }

    private function generateReportData($type, $charityId, $startDate, $endDate)
    {
        switch ($type) {
            case 'campaign_summary':
                return $this->generateCampaignSummary($charityId, $startDate, $endDate);
            case 'donation_analysis':
                return $this->generateDonationAnalysis($charityId, $startDate, $endDate);
            case 'financial_report':
                return $this->generateFinancialReport($charityId, $startDate, $endDate);
            case 'request_summary':
                return $this->generateRequestSummary($startDate, $endDate);
            default:
                return [];
        }
    }

    private function generateCampaignSummary($charityId, $startDate, $endDate)
    {
        $campaigns = Campaign::where('charity_id', $charityId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->withCount('donations')
            ->withSum('donations', 'amount')
            ->get();

        return [
            'total_campaigns' => $campaigns->count(),
            'active_campaigns' => $campaigns->where('status', 'active')->count(),
            'completed_campaigns' => $campaigns->where('status', 'completed')->count(),
            'total_goal' => $campaigns->sum('goal'),
            'total_raised' => $campaigns->sum('donations_sum_amount'),
            'campaigns' => $campaigns->map(function($campaign) {
                return [
                    'name' => $campaign->name,
                    'goal' => $campaign->goal,
                    'raised' => $campaign->donations_sum_amount ?? 0,
                    'progress' => $campaign->progress_percentage,
                    'donors_count' => $campaign->donations_count,
                    'status' => $campaign->status,
                ];
            })
        ];
    }

    private function generateDonationAnalysis($charityId, $startDate, $endDate)
    {
        $donations = CampaignDonation::whereHas('campaign', function($query) use ($charityId) {
            $query->where('charity_id', $charityId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();

        return [
            'total_donations' => $donations->count(),
            'total_amount' => $donations->sum('amount'),
            'average_donation' => $donations->avg('amount'),
            'payment_methods' => $donations->groupBy('payment_method')->map->count(),
            'anonymous_donations' => $donations->where('is_anonymous', true)->count(),
            'monthly_trends' => $donations->groupBy(function($donation) {
                return $donation->created_at->format('Y-m');
            })->map(function($monthDonations) {
                return [
                    'count' => $monthDonations->count(),
                    'amount' => $monthDonations->sum('amount'),
                ];
            })
        ];
    }

    private function generateFinancialReport($charityId, $startDate, $endDate)
    {
        $campaigns = Campaign::where('charity_id', $charityId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $donations = CampaignDonation::whereHas('campaign', function($query) use ($charityId) {
            $query->where('charity_id', $charityId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();

        return [
            'total_goals' => $campaigns->sum('goal'),
            'total_raised' => $donations->sum('amount'),
            'funding_gap' => $campaigns->sum('goal') - $donations->sum('amount'),
            'campaign_performance' => $campaigns->map(function($campaign) {
                return [
                    'name' => $campaign->name,
                    'goal' => $campaign->goal,
                    'raised' => $campaign->current_amount,
                    'percentage' => $campaign->progress_percentage,
                ];
            }),
            'donation_breakdown' => [
                'by_payment_method' => $donations->groupBy('payment_method')->map->sum('amount'),
                'by_month' => $donations->groupBy(function($donation) {
                    return $donation->created_at->format('Y-m');
                })->map->sum('amount'),
            ]
        ];
    }

    private function generateRequestSummary($startDate, $endDate)
    {
        $requests = RequestModel::whereBetween('created_at', [$startDate, $endDate])->get();

        return [
            'total_requests' => $requests->count(),
            'pending_requests' => $requests->where('status', 'pending')->count(),
            'approved_requests' => $requests->where('status', 'approved')->count(),
            'rejected_requests' => $requests->where('status', 'rejected')->count(),
            'by_type' => $requests->groupBy('type')->map->count(),
            'by_status' => $requests->groupBy('status')->map->count(),
            'monthly_trends' => $requests->groupBy(function($request) {
                return $request->created_at->format('Y-m');
            })->map->count(),
        ];
    }

    public function exportReport($reportId)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.reports.export')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لتصدير التقارير'
            ], 403);
        }

        $report = CharityReport::where('charity_id', Auth::id())->findOrFail($reportId);

        try {
            // Update report status
            $report->update([
                'status' => 'exported',
                'file_type' => 'pdf', // For now, just mark as exported
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تصدير التقرير بنجاح!',
                'report' => $report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تصدير التقرير'
            ], 500);
        }
    }

    public function approveRequest($num)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.requests.approve')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية للموافقة على الطلبات'
            ], 403);
        }

        try {
            $req = RequestModel::findOrFail($num);
            $req->status = 'approved';
            $req->approved_by = Auth::id();
            $req->approved_at = now();
            $req->save();

            return response()->json([
                'success' => true, 
                'message' => "تمت الموافقة على الطلب رقم #$num بنجاح."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الموافقة على الطلب'
            ], 500);
        }
    }

    public function rejectRequest($num)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.requests.reject')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لرفض الطلبات'
            ], 403);
        }

        try {
            $req = RequestModel::findOrFail($num);
            $req->status = 'rejected';
            $req->rejected_by = Auth::id();
            $req->rejected_at = now();
            $req->save();

            return response()->json([
                'success' => true, 
                'message' => "تم رفض الطلب رقم #$num بنجاح."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء رفض الطلب'
            ], 500);
        }
    }

    public function storeCampaign(Request $request)
    {
        // Check permission
        if (!Auth::user()->hasPermission('charity.campaigns.create')) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لإنشاء حملات جديدة'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'required|numeric|min:0',
        ]);

        try {
            $campaign = Campaign::create([
                'name' => $request->name,
                'goal' => $request->goal,
                'status' => 'active',
                'charity_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الحملة بنجاح!',
                'campaign' => $campaign
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الحملة'
            ], 500);
        }
    }

    // صفحة إدارة الكوبونات
    public function coupons()
    {
        $charityId = auth()->id();
        $coupons = \App\Models\Coupon::where('charity_id', $charityId)->latest()->get();
        return view('charity.coupons', compact('coupons'));
    }

    // عرض نموذج إنشاء كوبون جديد
    public function createCoupon()
    {
        return view('charity.coupons_create');
    }

    // حفظ كوبون جديد
    public function storeCoupon(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'value' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'expiry_date' => 'required|date|after:today',
        ]);
        $coupon = new \App\Models\Coupon();
        $coupon->code = $request->code;
        $coupon->value = $request->value;
        $coupon->description = $request->description;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->charity_id = auth()->id();
        $coupon->redeemed = false;
        $coupon->save();
        return redirect()->route('charity.coupons')->with('success', 'تم إنشاء الكوبون بنجاح');
    }
} 