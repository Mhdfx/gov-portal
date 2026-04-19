<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\CompanyUpdate;

class CompanyPublicController extends Controller
{
    /**
     * Display a listing of approved companies.
     */
    public function index(Request $request)
    {
        $query = Company::approved()->active();

        // Search by company name
        if ($request->filled('search')) {
            $query->where('company_name', 'like', '%' . $request->search . '%');
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Get filter options
        $cities = Company::approved()->active()->distinct()->pluck('city')->filter();
        
        $companies = $query->withCount(['jobListings' => function($q) {
            $q->active();
        }])->latest()->paginate(12);

        return view('companies.index', compact('companies', 'cities'));
    }

    /**
     * Display the public profile of a company.
     */
    public function show($slug)
    {
        $company = Company::where('slug', $slug)
            ->approved()
            ->active()
            ->with(['updates' => function($q) {
                $q->where('is_active', true)->latest();
            }, 'jobListings' => function($q) {
                $q->active()->latest();
            }, 'products' => function($q) {
                $q->latest()->limit(6);
            }])
            ->firstOrFail();

        return view('companies.show', compact('company'));
    }
}
