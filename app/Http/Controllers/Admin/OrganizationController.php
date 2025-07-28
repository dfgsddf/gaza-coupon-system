<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();
        return response()->json($organizations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
            'registration_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ]);
        $organization = Organization::create($validated);
        return response()->json($organization, 201);
    }

    public function show($id)
    {
        $organization = Organization::findOrFail($id);
        return response()->json($organization);
    }

    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'nullable|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
            'registration_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ]);
        $organization->update($validated);
        return response()->json($organization);
    }

    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();
        return response()->json(['message' => 'Organization deleted successfully']);
    }

    public function stats()
    {
        $total = Organization::count();
        $active = Organization::where('status', 'active')->count();
        $pending = Organization::where('status', 'pending')->count();
        $suspended = Organization::where('status', 'suspended')->count();
        return response()->json([
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'suspended' => $suspended,
        ]);
    }
} 