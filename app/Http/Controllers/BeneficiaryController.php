<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeneficiaryController extends Controller
{
    public function index()
    {
        $beneficiaries = Beneficiary::all();
        return view('beneficiaries.index', compact('beneficiaries'));
    }

    public function create()
    {
        return view('beneficiary.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'family_size' => 'required|integer|min:1|max:20',
            'income' => 'required|numeric|min:0',
            'emergency_contact' => 'required|string|max:20',
            'terms' => 'required|accepted',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048'
        ]);

        try {
            // Create user account
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make(Str::random(12)), // Generate random password
                'phone' => $data['phone'],
                'role' => 'beneficiary',
                'status' => 'pending'
            ]);

            // Handle document uploads
            $documentPaths = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $path = $document->store('beneficiary-documents', 'public');
                    $documentPaths[] = $path;
                }
            }

            // Create beneficiary record
            $beneficiary = Beneficiary::create([
                'user_id' => $user->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'family_size' => $data['family_size'],
                'monthly_income' => $data['income'],
                'emergency_contact' => $data['emergency_contact'],
                'documents' => json_encode($documentPaths),
                'status' => 'pending'
            ]);

            return redirect()->route('login.form')
                ->with('success', 'تم تسجيل حسابك بنجاح! يرجى تسجيل الدخول باستخدام بريدك الإلكتروني. سيتم إرسال كلمة المرور إلى بريدك الإلكتروني قريباً.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى.');
        }
    }
}
