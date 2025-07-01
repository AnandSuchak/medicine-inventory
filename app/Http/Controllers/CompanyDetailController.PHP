<?php

namespace App\Http\Controllers;

use App\Models\CompanyDetail;
use Illuminate\Http\Request;

class CompanyDetailController extends Controller
{
    /**
     * Show the form for editing the company details.
     * There will only ever be one row in the company_details table.
     */
    public function edit()
    {
        // Get the first record, or create a new empty one if it doesn't exist
        $companyDetail = CompanyDetail::firstOrNew([]);
        return view('company_details.edit', compact('companyDetail'));
    }

    /**
     * Update the company details in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'gstin' => 'required|string|max:15',
        ]);

        // Update the first record, or create it if it doesn't exist.
        CompanyDetail::updateOrCreate(['id' => 1], $request->all());

        return redirect()->route('company_details.edit')->with('success', 'Company details updated successfully.');
    }
}