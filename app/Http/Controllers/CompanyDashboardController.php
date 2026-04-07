<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Company;
use App\Models\Product;
use App\Models\Order;
use App\Models\JobListing;
use App\Models\JobApplication;
use App\Models\CompanyDocument;
use App\Models\Notification;
use SEO;

class CompanyDashboardController extends Controller
{
    /**
     * Show the company dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        // Set SEO meta tags
        SEO::setTitle('Tableau de Bord - ' . $company->company_name);
        SEO::setDescription('Gérez votre entreprise, produits et services sur la plateforme Boiema.');
        SEO::setCanonical(url()->current());
        
        // Get company statistics with caching
        $stats = Cache::remember('company_stats_' . $company->id, 300, function () use ($company) {
            return [
                'total_products' => Product::where('company_id', $company->id)->count(),
                'active_products' => Product::where('company_id', $company->id)->where('is_active', true)->count(),
                'total_orders' => Order::where('company_id', $company->id)->count(),
                'pending_orders' => Order::where('company_id', $company->id)->where('status', 'pending')->count(),
                'total_jobs' => JobListing::where('company_id', $company->id)->count(),
                'active_jobs' => JobListing::where('company_id', $company->id)->where('is_active', true)->count(),
            ];
        });
        
        // Get recent orders with optimized query
        $recentOrders = Order::where('company_id', $company->id)
            ->select('id', 'user_id', 'total_amount', 'status', 'created_at')
            ->with('user:id,username,email')
            ->latest()
            ->limit(10)
            ->get();
        
        // Get recent job applications
        $recentApplications = JobListing::where('company_id', $company->id)
            ->with(['applications' => function($query) {
                $query->latest()->limit(5);
            }])
            ->get()
            ->pluck('applications')
            ->flatten();
        
        // Get recent notifications
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();
        
        return view('dashboard.company.index', compact('user', 'company', 'stats', 'recentOrders', 'recentApplications', 'notifications'));
    }
    
    /**
     * Show company setup form.
     */
    public function setup()
    {
        $user = Auth::user();
        
        if ($user->company) {
            return redirect()->route('company.dashboard');
        }
        
        SEO::setTitle('Configuration de l\'Entreprise');
        SEO::setDescription('Configurez les informations de votre entreprise.');
        
        return view('dashboard.company.setup', compact('user'));
    }
    
    /**
     * Store company information.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->company) {
            return redirect()->route('company.dashboard');
        }
        
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_type' => 'required|string|in:SARL,SA,SAS,SNC,SPA,Cooperative,Association,Autre',
            'registration_number' => 'required|string|max:100|unique:companies',
            'tax_number' => 'nullable|string|max:100',
            'description' => 'required|string|max:2000',
            'website' => 'nullable|url|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'business_sectors' => 'required|array|min:1',
            'business_sectors.*' => 'string|max:255',
            'employee_count' => 'nullable|integer|min:1',
            'annual_revenue' => 'nullable|numeric|min:0',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);
        
        $company = Company::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'company_type' => $request->company_type,
            'registration_number' => $request->registration_number,
            'tax_number' => $request->tax_number,
            'description' => $request->description,
            'website' => $request->website,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'region' => $request->region,
            'postal_code' => $request->postal_code,
            'country' => $request->country ?? 'Morocco',
            'business_sectors' => $request->business_sectors,
            'employee_count' => $request->employee_count,
            'annual_revenue' => $request->annual_revenue,
            'founded_year' => $request->founded_year,
            'approval_status' => 'pending',
            'is_active' => true,
        ]);
        
        return redirect()->route('company.dashboard')->with('success', 'Informations de l\'entreprise enregistrées avec succès. En attente d\'approbation.');
    }
    
    /**
     * Show company profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        SEO::setTitle('Profil de l\'Entreprise - ' . $company->company_name);
        SEO::setDescription('Gérez les informations de votre entreprise.');
        
        return view('dashboard.company.profile', compact('user', 'company'));
    }
    
    /**
     * Update company profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_type' => 'required|string|in:SARL,SA,SAS,SNC,SPA,Cooperative,Association,Autre',
            'description' => 'required|string|max:2000',
            'website' => 'nullable|url|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'business_sectors' => 'required|array|min:1',
            'business_sectors.*' => 'string|max:255',
            'employee_count' => 'nullable|integer|min:1',
            'annual_revenue' => 'nullable|numeric|min:0',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);
        
        $company->update($request->only([
            'company_name', 'company_type', 'description', 'website', 'phone', 'email',
            'address', 'city', 'region', 'postal_code', 'country', 'business_sectors',
            'employee_count', 'annual_revenue', 'founded_year'
        ]));
        
        return redirect()->route('company.profile')->with('success', 'Profil de l\'entreprise mis à jour avec succès.');
    }
    
    /**
     * Show company products.
     */
    public function products()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        SEO::setTitle('Mes Produits - ' . $company->company_name);
        SEO::setDescription('Gérez vos produits et services.');
        
        $products = Product::where('company_id', $company->id)->latest()->paginate(20);
        
        return view('dashboard.company.products', compact('user', 'company', 'products'));
    }
    
    /**
     * Show the form for creating a new product.
     */
    public function createProduct()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        SEO::setTitle('Create Product - ' . $company->company_name);
        SEO::setDescription('Add a new product to your catalog.');
        
        return view('dashboard.company.products.create', compact('user', 'company'));
    }
    
    /**
     * Store a newly created product.
     */
    public function storeProduct(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }
        
        Product::create([
            'company_id' => $company->id,
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity ?? 0,
            'category' => $request->category,
            'image_path' => $imagePath,
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        
        return redirect()->route('company.products')->with('success', 'Product created successfully!');
    }
    
    /**
     * Show the form for editing a product.
     */
    public function editProduct(Product $product)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company || $product->company_id !== $company->id) {
            return redirect()->route('company.products')->with('error', 'Product not found.');
        }
        
        SEO::setTitle('Edit Product - ' . $product->name);
        SEO::setDescription('Edit product information.');
        
        return view('dashboard.company.products.edit', compact('user', 'company', 'product'));
    }
    
    /**
     * Update the specified product.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company || $product->company_id !== $company->id) {
            return redirect()->route('company.products')->with('error', 'Product not found.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);
        
        $imagePath = $product->image_path;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }
        
        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity ?? 0,
            'category' => $request->category,
            'image_path' => $imagePath,
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        
        return redirect()->route('company.products')->with('success', 'Product updated successfully!');
    }
    
    /**
     * Remove the specified product.
     */
    public function destroyProduct(Product $product)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company || $product->company_id !== $company->id) {
            return redirect()->route('company.products')->with('error', 'Product not found.');
        }
        
        // Delete image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();
        
        return redirect()->route('company.products')->with('success', 'Product deleted successfully!');
    }
    
    /**
     * Show company orders.
     */
    public function orders()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        SEO::setTitle('Mes Commandes - ' . $company->company_name);
        SEO::setDescription('Gérez les commandes de vos produits.');
        
        $orders = Order::where('company_id', $company->id)
            ->with('user')
            ->latest()
            ->paginate(20);
        
        // Calculate statistics
        $stats = [
            'total_orders' => Order::where('company_id', $company->id)->count(),
            'pending_orders' => Order::where('company_id', $company->id)->where('status', 'pending')->count(),
            'processing_orders' => Order::where('company_id', $company->id)->where('status', 'processing')->count(),
            'completed_orders' => Order::where('company_id', $company->id)->whereIn('status', ['delivered', 'shipped'])->count(),
            'total_revenue' => Order::where('company_id', $company->id)->where('payment_status', 'paid')->sum('total_amount'),
        ];
        
        return view('dashboard.company.orders', compact('user', 'company', 'orders', 'stats'));
    }
    
    /**
     * Show order details.
     */
    public function showOrder(Order $order)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        if ($order->company_id !== $company->id) {
            return redirect()->route('company.orders')->with('error', 'Order not found.');
        }
        
        SEO::setTitle('Order Details - ' . $order->order_number);
        SEO::setDescription('View detailed information about this order.');
        
        $order->load('orderItems.product');
        
        return view('dashboard.company.orders.show', compact('user', 'company', 'order'));
    }
    
    /**
     * Show company job listings.
     */
    public function jobs(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        SEO::setTitle('Job Listings - ' . $company->company_name);
        SEO::setDescription('Manage your company job listings and track applications.');
        
        // Build query with filters
        $query = JobListing::where('company_id', $company->id)
            ->withCount('jobApplications');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show all except draft
            $query->where('status', '!=', 'draft');
        }
        
        // Filter by job type
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }
        
        // Filter by employment type
        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }
        
        // Search by title, description, or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        $jobs = $query->latest()->paginate(20)->withQueryString();
        
        // Calculate statistics
        $stats = [
            'total_jobs' => JobListing::where('company_id', $company->id)->count(),
            'active_jobs' => JobListing::where('company_id', $company->id)
                ->where('status', 'active')
                ->where('application_deadline', '>=', now()->toDateString())
                ->count(),
            'total_applications' => JobApplication::whereHas('jobListing', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->count(),
            'pending_applications' => JobApplication::whereHas('jobListing', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->where('status', 'pending')->count(),
        ];
        
        return view('dashboard.company.jobs', compact('user', 'company', 'jobs', 'stats'));
    }

    /**
     * Display all job applications for the company.
     */
    public function jobApplications(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        SEO::setTitle('Job Applications - ' . $company->company_name);
        SEO::setDescription('Review and manage job applications submitted to your listings.');
        
        $jobs = JobListing::where('company_id', $company->id)
            ->orderBy('title')
            ->get(['id', 'title', 'status']);

        $applicationsQuery = JobApplication::whereHas('jobListing', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->with(['candidate', 'jobListing']);

        if ($request->filled('status')) {
            $applicationsQuery->where('status', $request->status);
        }

        if ($request->filled('job_id')) {
            $applicationsQuery->where('job_listing_id', $request->job_id);
        }

        if ($request->filled('date_from')) {
            $applicationsQuery->whereDate('applied_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $applicationsQuery->whereDate('applied_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $applicationsQuery->where(function ($query) use ($search) {
                $query->whereHas('candidate', function ($candidateQuery) use ($search) {
                    $candidateQuery->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })->orWhereHas('jobListing', function ($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', '%' . $search . '%');
                });
            });
        }

        $applications = $applicationsQuery
            ->orderByDesc('applied_at')
            ->paginate(20)
            ->withQueryString();

        $statsBaseQuery = JobApplication::whereHas('jobListing', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        });

        $stats = [
            'total_applications' => (clone $statsBaseQuery)->count(),
            'pending_applications' => (clone $statsBaseQuery)->where('status', 'pending')->count(),
            'reviewed_applications' => (clone $statsBaseQuery)->where('status', 'reviewed')->count(),
            'accepted_applications' => (clone $statsBaseQuery)->where('status', 'accepted')->count(),
            'rejected_applications' => (clone $statsBaseQuery)->where('status', 'rejected')->count(),
            'this_week_applications' => (clone $statsBaseQuery)->where('applied_at', '>=', now()->subDays(7))->count(),
        ];

        return view('dashboard.company.applications', compact('user', 'company', 'applications', 'stats', 'jobs'));
    }

    /**
     * Display a specific job application.
     */
    public function showJobApplication(JobApplication $application)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.setup');
        }

        if ($application->jobListing->company_id !== $company->id) {
            return redirect()->route('company.applications')->with('error', 'Application not found.');
        }

        SEO::setTitle('Application #' . $application->id . ' - ' . $company->company_name);
        SEO::setDescription('Detailed information about this job application.');

        $application->load(['candidate', 'jobListing']);

        return view('dashboard.company.applications.show', compact('user', 'company', 'application'));
    }
    
    /**
     * Show the form for creating a new job listing.
     */
    public function createJob()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        SEO::setTitle('Create Job Listing - ' . $company->company_name);
        SEO::setDescription('Post a new job listing.');
        
        return view('dashboard.company.jobs.create', compact('user', 'company'));
    }
    
    /**
     * Store a newly created job listing.
     */
    public function storeJob(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('company.setup');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'requirements' => 'nullable|string|max:2000',
            'responsibilities' => 'nullable|string|max:2000',
            'job_type' => 'required|in:full-time,part-time,contract,internship',
            'employment_type' => 'required|in:permanent,temporary,contract',
            'experience_level' => 'required|in:entry,junior,mid,senior,executive',
            'education_required' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'is_remote' => 'nullable|boolean',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'nullable|string|max:3',
            'benefits' => 'nullable|string|max:1000',
            'application_deadline' => 'required|date|after:today',
            'start_date' => 'nullable|date|after:today',
            'status' => 'required|in:active,paused,closed,draft',
        ]);
        
        // Handle skills arrays
        $requiredSkills = [];
        $preferredSkills = [];
        
        if ($request->filled('required_skills_input')) {
            $requiredSkills = array_filter(array_map('trim', explode(',', $request->required_skills_input)));
        } elseif ($request->filled('required_skills')) {
            $requiredSkills = $request->required_skills;
        }
        
        if ($request->filled('preferred_skills_input')) {
            $preferredSkills = array_filter(array_map('trim', explode(',', $request->preferred_skills_input)));
        } elseif ($request->filled('preferred_skills')) {
            $preferredSkills = $request->preferred_skills;
        }
        
        JobListing::create([
            'company_id' => $company->id,
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'responsibilities' => $request->responsibilities,
            'job_type' => $request->job_type,
            'employment_type' => $request->employment_type,
            'experience_level' => $request->experience_level,
            'education_required' => $request->education_required,
            'required_skills' => $requiredSkills,
            'preferred_skills' => $preferredSkills,
            'location' => $request->location,
            'city' => $request->city,
            'region' => $request->region,
            'is_remote' => $request->has('is_remote') ? true : false,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_currency' => $request->salary_currency ?? 'MAD',
            'currency' => $request->salary_currency ?? 'MAD',
            'benefits' => $request->benefits,
            'application_deadline' => $request->application_deadline,
            'start_date' => $request->start_date,
            'status' => $request->status,
            'views_count' => 0,
            'applications_count' => 0,
        ]);
        
        return redirect()->route('company.jobs')->with('success', 'Job listing created successfully!');
    }
    
    /**
     * Show the form for editing a job listing.
     */
    public function editJob(JobListing $job)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company || $job->company_id !== $company->id) {
            return redirect()->route('company.jobs')->with('error', 'Job listing not found.');
        }
        
        SEO::setTitle('Edit Job Listing - ' . $job->title);
        SEO::setDescription('Edit job listing information.');
        
        return view('dashboard.company.jobs.edit', compact('user', 'company', 'job'));
    }
    
    /**
     * Update the specified job listing.
     */
    public function updateJob(Request $request, JobListing $job)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company || $job->company_id !== $company->id) {
            return redirect()->route('company.jobs')->with('error', 'Job listing not found.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'requirements' => 'nullable|string|max:2000',
            'responsibilities' => 'nullable|string|max:2000',
            'job_type' => 'required|in:full-time,part-time,contract,internship',
            'employment_type' => 'required|in:permanent,temporary,contract',
            'experience_level' => 'required|in:entry,junior,mid,senior,executive',
            'education_required' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'is_remote' => 'nullable|boolean',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'nullable|string|max:3',
            'benefits' => 'nullable|string|max:1000',
            'application_deadline' => 'required|date',
            'start_date' => 'nullable|date',
            'status' => 'required|in:active,paused,closed,draft',
        ]);
        
        // Handle skills arrays
        $requiredSkills = [];
        $preferredSkills = [];
        
        if ($request->filled('required_skills_input')) {
            $requiredSkills = array_filter(array_map('trim', explode(',', $request->required_skills_input)));
        } elseif ($request->filled('required_skills')) {
            $requiredSkills = $request->required_skills;
        }
        
        if ($request->filled('preferred_skills_input')) {
            $preferredSkills = array_filter(array_map('trim', explode(',', $request->preferred_skills_input)));
        } elseif ($request->filled('preferred_skills')) {
            $preferredSkills = $request->preferred_skills;
        }
        
        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'responsibilities' => $request->responsibilities,
            'job_type' => $request->job_type,
            'employment_type' => $request->employment_type,
            'experience_level' => $request->experience_level,
            'education_required' => $request->education_required,
            'required_skills' => $requiredSkills,
            'preferred_skills' => $preferredSkills,
            'location' => $request->location,
            'city' => $request->city,
            'region' => $request->region,
            'is_remote' => $request->has('is_remote') ? true : false,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_currency' => $request->salary_currency ?? 'MAD',
            'currency' => $request->salary_currency ?? 'MAD',
            'benefits' => $request->benefits,
            'application_deadline' => $request->application_deadline,
            'start_date' => $request->start_date,
            'status' => $request->status,
        ]);
        
        return redirect()->route('company.jobs')->with('success', 'Job listing updated successfully!');
    }
    
    /**
     * Remove the specified job listing.
     */
    public function destroyJob(JobListing $job)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company || $job->company_id !== $company->id) {
            return redirect()->route('company.jobs')->with('error', 'Job listing not found.');
        }
        
        $job->delete();
        
        return redirect()->route('company.jobs')->with('success', 'Job listing deleted successfully!');
    }

    /**
     * Display company documents.
     */
    public function documents(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.setup');
        }

        SEO::setTitle('Company Documents - ' . $company->company_name);
        SEO::setDescription('Manage official company documents and compliance files.');

        $documentTypes = [
            'registration' => 'Registration',
            'tax' => 'Tax Certificate',
            'compliance' => 'Compliance',
            'financial' => 'Financial',
            'other' => 'Other',
        ];

        $documentsQuery = CompanyDocument::where('company_id', $company->id);

        if ($request->filled('type')) {
            $documentsQuery->where('document_type', $request->type);
        }

        if ($request->filled('status')) {
            $documentsQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $documentsQuery->where('document_name', 'like', '%' . $request->search . '%');
        }

        $documents = $documentsQuery->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_documents' => CompanyDocument::where('company_id', $company->id)->count(),
            'approved_documents' => CompanyDocument::where('company_id', $company->id)->where('status', 'approved')->count(),
            'pending_documents' => CompanyDocument::where('company_id', $company->id)->where('status', 'pending')->count(),
            'rejected_documents' => CompanyDocument::where('company_id', $company->id)->where('status', 'rejected')->count(),
            'storage_used' => CompanyDocument::where('company_id', $company->id)->sum('file_size'),
        ];

        return view('dashboard.company.documents', compact('user', 'company', 'documents', 'stats', 'documentTypes'));
    }

    /**
     * Store a newly uploaded company document.
     */
    public function storeDocument(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.setup');
        }

        $request->validate([
            'document_type' => 'required|string|in:registration,tax,compliance,financial,other',
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('company-documents/' . $company->id, 'public');

        CompanyDocument::create([
            'company_id' => $company->id,
            'document_type' => $request->document_type,
            'document_name' => $request->document_name,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->extension(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'status' => 'pending',
        ]);

        return redirect()->route('company.documents')->with('success', 'Document uploaded successfully and pending verification.');
    }

    /**
     * Remove the specified company document.
     */
    public function destroyDocument(CompanyDocument $document)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $document->company_id !== $company->id) {
            return redirect()->route('company.documents')->with('error', 'Document not found.');
        }

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('company.documents')->with('success', 'Document deleted successfully.');
    }

    /**
     * Display company notifications.
     */
    public function notifications(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.setup');
        }

        SEO::setTitle('Notifications - ' . $company->company_name);
        SEO::setDescription('View messages, alerts and system updates for your company.');

        $notificationsQuery = Notification::where('user_id', $user->id);

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $notificationsQuery->where('is_read', false);
            } elseif ($request->status === 'read') {
                $notificationsQuery->where('is_read', true);
            }
        }

        if ($request->filled('category')) {
            $notificationsQuery->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $notificationsQuery->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $notifications = $notificationsQuery->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
        ];

        return view('dashboard.company.notifications', compact('user', 'company', 'notifications', 'stats'));
    }

    /**
     * Mark a notification as read.
     */
    public function markNotificationRead(Notification $notification)
    {
        $user = Auth::user();

        if ($notification->user_id !== $user->id) {
            return redirect()->route('company.notifications')->with('error', 'Notification not found.');
        }

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return redirect()->route('company.notifications')->with('success', 'Notification marked as read.');
    }

    /**
     * Display company settings.
     */
    public function settings()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.setup');
        }

        SEO::setTitle('Company Settings - ' . $company->company_name);
        SEO::setDescription('Manage preferences, contact details, and visibility options.');

        return view('dashboard.company.settings', compact('user', 'company'));
    }

    /**
     * Update company settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.setup');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string|max:2000',
        ]);

        $company->update([
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('company.settings')->with('success', 'Settings updated successfully.');
    }
}








