<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\NewsletterSubscription;
use App\Mail\NewsletterMail;
use SEO;

class NewsletterController extends Controller
{
    /**
     * Display the newsletter subscription form.
     */
    public function showSubscriptionForm()
    {
        SEO::setTitle('Newsletter Subscription | Boiema Platform');
        SEO::setDescription('Subscribe to our newsletter to stay updated with the latest news and updates from Boiema Platform.');
        
        return view('newsletter.subscribe');
    }

    /**
     * Handle newsletter subscription.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        // Check if email already exists
        $existingSubscription = NewsletterSubscription::where('email', $request->email)->first();

        if ($existingSubscription) {
            if ($existingSubscription->status === 'unsubscribed') {
                // Reactivate subscription
                $existingSubscription->reactivate();
                return redirect()->back()->with('success', 'Your newsletter subscription has been reactivated!');
            } elseif ($existingSubscription->status === 'active') {
                return redirect()->back()->with('info', 'You are already subscribed to our newsletter.');
            }
        }

        // Create new subscription
        NewsletterSubscription::create([
            'email' => $request->email,
            'name' => $request->name,
            'status' => 'active',
            'subscribed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
    }

    /**
     * Handle newsletter unsubscription.
     */
    public function unsubscribe(Request $request, $email = null)
    {
        if ($request->has('email')) {
            $email = $request->email;
        }

        if (!$email) {
            return redirect()->back()->with('error', 'Email address is required.');
        }

        $subscription = NewsletterSubscription::where('email', $email)->first();

        if (!$subscription) {
            return redirect()->back()->with('error', 'Email address not found in our newsletter list.');
        }

        if ($subscription->status === 'unsubscribed') {
            return redirect()->back()->with('info', 'You are already unsubscribed from our newsletter.');
        }

        $subscription->unsubscribe();

        return redirect()->back()->with('success', 'You have been successfully unsubscribed from our newsletter.');
    }

    /**
     * Display newsletter management interface (admin only).
     */
    public function index(Request $request)
    {
        SEO::setTitle('Newsletter Management | Admin Dashboard');
        SEO::setDescription('Manage newsletter subscriptions and send newsletters.');

        $query = NewsletterSubscription::query();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('subscribed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('subscribed_at', '<=', $request->date_to);
        }

        $subscriptions = $query->orderBy('subscribed_at', 'desc')->paginate(20);

        // Get statistics
        $stats = [
            'total' => NewsletterSubscription::count(),
            'active' => NewsletterSubscription::active()->count(),
            'inactive' => NewsletterSubscription::inactive()->count(),
            'unsubscribed' => NewsletterSubscription::unsubscribed()->count(),
            'recent' => NewsletterSubscription::recent(30)->count(),
        ];

        return view('newsletter.index', compact('subscriptions', 'stats'));
    }

    /**
     * Show the form for creating a new newsletter.
     */
    public function create()
    {
        SEO::setTitle('Create Newsletter | Admin Dashboard');
        SEO::setDescription('Create and send a new newsletter to subscribers.');

        return view('newsletter.create');
    }

    /**
     * Send newsletter to subscribers.
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipients' => 'required|in:all,active,recent',
            'test_email' => 'nullable|email',
        ]);

        // If test email is provided, send test newsletter
        if ($request->filled('test_email')) {
            $this->sendTestNewsletter($request->test_email, $request->subject, $request->content);
            return redirect()->back()->with('success', 'Test newsletter sent successfully!');
        }

        // Get recipients based on selection
        $recipients = $this->getRecipients($request->recipients);

        if ($recipients->isEmpty()) {
            return redirect()->back()->with('error', 'No recipients found for the selected criteria.');
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($recipients as $subscription) {
            try {
                Mail::to($subscription->email)->send(
                    new NewsletterMail($request->subject, $request->content, $subscription)
                );
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to send newsletter to ' . $subscription->email . ': ' . $e->getMessage());
                $failedCount++;
            }
        }

        $message = "Newsletter sent successfully! Sent: {$sentCount}, Failed: {$failedCount}";
        return redirect()->back()->with('success', $message);
    }

    /**
     * Get recipients based on criteria.
     */
    private function getRecipients($criteria)
    {
        switch ($criteria) {
            case 'active':
                return NewsletterSubscription::active()->get();
            case 'recent':
                return NewsletterSubscription::active()->recent(30)->get();
            case 'all':
            default:
                return NewsletterSubscription::active()->get();
        }
    }

    /**
     * Send test newsletter.
     */
    private function sendTestNewsletter($email, $subject, $content)
    {
        $testSubscription = new NewsletterSubscription([
            'email' => $email,
            'name' => 'Test User',
            'status' => 'active',
        ]);

        Mail::to($email)->send(
            new NewsletterMail($subject, $content, $testSubscription)
        );
    }

    /**
     * Export newsletter subscribers.
     */
    public function export(Request $request)
    {
        $query = NewsletterSubscription::query();

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $subscriptions = $query->orderBy('subscribed_at', 'desc')->get();

        $filename = 'newsletter_subscribers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($subscriptions) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Email', 'Name', 'Status', 'Subscribed At', 'Unsubscribed At']);
            
            // Add data rows
            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $subscription->email,
                    $subscription->name,
                    $subscription->status,
                    $subscription->subscribed_at->format('Y-m-d H:i:s'),
                    $subscription->unsubscribed_at ? $subscription->unsubscribed_at->format('Y-m-d H:i:s') : '',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get newsletter statistics for API.
     */
    public function statistics()
    {
        $stats = [
            'total_subscribers' => NewsletterSubscription::count(),
            'active_subscribers' => NewsletterSubscription::active()->count(),
            'inactive_subscribers' => NewsletterSubscription::inactive()->count(),
            'unsubscribed' => NewsletterSubscription::unsubscribed()->count(),
            'recent_subscribers' => NewsletterSubscription::recent(30)->count(),
            'monthly_growth' => NewsletterSubscription::recent(30)->count() - NewsletterSubscription::where('subscribed_at', '>=', now()->subDays(60))->where('subscribed_at', '<', now()->subDays(30))->count(),
        ];

        return response()->json($stats);
    }
}