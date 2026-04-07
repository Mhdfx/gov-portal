<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SEO;

class AutoEntrepreneurController extends Controller
{
    /**
     * Show the auto-entrepreneur form page.
     */
    public function index()
    {
        // Set SEO meta tags
        SEO::setTitle('Auto-Entrepreneur Registration | Boiema Platform');
        SEO::setDescription('Register as an auto-entrepreneur on Boiema Platform. Submit your business idea and get support for your entrepreneurial journey in Morocco.');
        SEO::setCanonical(url()->current());
        
        // Open Graph
        SEO::opengraph()->setTitle('Auto-Entrepreneur Registration | Boiema Platform')
            ->setDescription('Register as an auto-entrepreneur on Boiema Platform. Submit your business idea and get support for your entrepreneurial journey in Morocco.')
            ->setUrl(url()->current())
            ->setType('website')
            ->setSiteName('Boiema Platform');
        
        // Twitter Card
        SEO::twitter()->setTitle('Auto-Entrepreneur Registration | Boiema Platform')
            ->setDescription('Register as an auto-entrepreneur on Boiema Platform. Submit your business idea and get support for your entrepreneurial journey in Morocco.');

        return view('forms.auto-entrepreneur');
    }
}






























