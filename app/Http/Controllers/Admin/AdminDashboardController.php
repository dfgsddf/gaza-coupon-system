<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBeneficiaries = User::where('role', 'beneficiary')->count();
        $activeStores = User::where('role', 'store')->where('status', 'active')->count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $organizations = User::where('role', 'charity')->count();
        $users = User::all();

        return view('admin.dashboard', compact('totalBeneficiaries', 'activeStores', 'activeCampaigns', 'organizations', 'users'));
    }

    public function getStats()
    {
        $totalBeneficiaries = User::where('role', 'beneficiary')->count();
        $activeStores = User::where('role', 'store')->where('status', 'active')->count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $organizations = User::where('role', 'charity')->count();

        return response()->json([
            'totalBeneficiaries' => $totalBeneficiaries,
            'activeStores' => $activeStores,
            'activeCampaigns' => $activeCampaigns,
            'organizations' => $organizations
        ]);
    }

    public function getActivity()
    {
        // Mock activity data - in a real application, this would come from an activity log table
        $activities = [
            ['text' => 'System Started', 'time' => '1.6.8.7'],
            ['text' => 'New Store Registered', 'time' => '2.9.87'],
            ['text' => 'Campaign Launched', 'time' => '8.6.5.3'],
            ['text' => 'Settings Updated', 'time' => '12.8.6.0'],
            ['text' => 'Activate Stores', 'time' => '6.8.9.3']
        ];

        return response()->json($activities);
    }

    public function getUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->get(['id', 'name', 'role', 'status']);

        return response()->json($users);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        $user->update($request->only(['name', 'email', 'phone']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    public function updateSystemSettings(Request $request)
    {
        // In a real application, you would save these to a settings table or config file
        return response()->json([
            'success' => true,
            'message' => 'System settings saved successfully'
        ]);
    }

    public function updateNotificationSettings(Request $request)
    {
        // In a real application, you would save these to a settings table or config file
        return response()->json([
            'success' => true,
            'message' => 'Notification settings saved successfully'
        ]);
    }
}
