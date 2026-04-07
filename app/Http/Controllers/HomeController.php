<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Artesaos\SEOTools\Facades\SEOTools;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        // Set SEO meta tags for public homepage
        SEOTools::setTitle('I.M System - Plateforme d\'Entrepreneuriat au Maroc');
        SEOTools::setDescription('Connectez-vous avec des investisseurs, institutions publiques et porteurs de projets. Plateforme complète pour l\'entrepreneuriat et l\'investissement au Maroc.');
        SEOTools::setCanonical(url()->current());
        
        // Open Graph
        SEOTools::opengraph()->setTitle('I.M System - Plateforme d\'Entrepreneuriat au Maroc')
            ->setDescription('Connectez-vous avec des investisseurs, institutions publiques et porteurs de projets. Plateforme complète pour l\'entrepreneuriat et l\'investissement au Maroc.')
            ->setUrl(url()->current())
            ->setType('website')
            ->setSiteName('I.M System');
        
        // Twitter Card
        SEOTools::twitter()->setTitle('I.M System - Plateforme d\'Entrepreneuriat au Maroc')
            ->setDescription('Connectez-vous avec des investisseurs, institutions publiques et porteurs de projets. Plateforme complète pour l\'entrepreneuriat et l\'investissement au Maroc');

        // Show public homepage for guests
        return view('home');
    }
}
