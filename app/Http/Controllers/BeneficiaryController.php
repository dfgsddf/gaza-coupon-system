<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;

class BeneficiaryController extends Controller
{
    public function index()
    {
        $beneficiaries = Beneficiary::all();
        return view('beneficiaries.index', compact('beneficiaries'));
    }

    public function create()
    {
        return view('beneficiaries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|unique:beneficiaries,id_number',
            'phone' => 'required|string',
            'address' => 'required|string',
            'family_size' => 'required|integer',
            'monthly_income' => 'required|numeric',
        ]);

        Beneficiary::create($data);

        return redirect()->route('beneficiaries.index')->with('success', 'تمت إضافة المستفيد بنجاح!');
    }
}
