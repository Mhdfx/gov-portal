<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Get company profile.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company profile not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $company,
        ]);
    }

    /**
     * Update company profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company profile not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string|max:500',
            'city' => 'sometimes|required|string|max:100',
            'region' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|nullable|string|max:20',
            'website' => 'sometimes|nullable|url|max:255',
            'description' => 'sometimes|nullable|string|max:2000',
            'sector' => 'sometimes|required|string|max:100',
            'size' => 'sometimes|required|in:small,medium,large,enterprise',
            'founded_year' => 'sometimes|nullable|integer|min:1800|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $company->update($request->only([
            'name', 'email', 'phone', 'address', 'city', 'region',
            'postal_code', 'website', 'description', 'sector', 'size', 'founded_year'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Company profile updated successfully',
            'data' => $company,
        ]);
    }

    /**
     * Get company's job listings.
     */
    public function jobListings(Request $request): JsonResponse
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company profile not found',
            ], 404);
        }

        $jobListings = $company->jobListings()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $jobListings->items(),
            'pagination' => [
                'current_page' => $jobListings->currentPage(),
                'last_page' => $jobListings->lastPage(),
                'per_page' => $jobListings->perPage(),
                'total' => $jobListings->total(),
            ],
        ]);
    }

    /**
     * Get company's products.
     */
    public function products(Request $request): JsonResponse
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company profile not found',
            ], 404);
        }

        $products = $company->products()
            ->with('images')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    /**
     * Get company's orders.
     */
    public function orders(Request $request): JsonResponse
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company profile not found',
            ], 404);
        }

        $orders = $company->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * Get company dashboard data.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company profile not found',
            ], 404);
        }

        $dashboardData = [
            'company' => $company,
            'job_listings_count' => $company->jobListings()->count(),
            'active_jobs' => $company->jobListings()->where('status', 'active')->count(),
            'products_count' => $company->products()->count(),
            'orders_count' => $company->orders()->count(),
            'total_revenue' => $company->orders()->where('status', 'completed')->sum('total_amount'),
            'recent_job_applications' => $company->jobListings()
                ->withCount('jobApplications')
                ->get()
                ->sum('job_applications_count'),
        ];

        return response()->json([
            'success' => true,
            'data' => $dashboardData,
        ]);
    }
}