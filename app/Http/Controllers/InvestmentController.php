<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Artesaos\SEOTools\Facades\SEOTools;

class InvestmentController extends Controller
{
    /**
     * Show the investment form page.
     */
    public function index()
    {
        // Set SEO meta tags
        SEOTools::setTitle('Demande d\'Investissement | Boiema Platform');
        SEOTools::setDescription('Soumettez votre demande d\'investissement sur la plateforme Boiema. Obtenez du financement pour votre projet au Maroc.');
        SEOTools::setCanonical(url()->current());
        
        // Open Graph
        SEOTools::opengraph()->setTitle('Demande d\'Investissement | Boiema Platform')
            ->setDescription('Soumettez votre demande d\'investissement sur la plateforme Boiema. Obtenez du financement pour votre projet au Maroc.')
            ->setUrl(url()->current())
            ->setType('website')
            ->setSiteName('Boiema Platform');
        
        // Twitter Card
        SEOTools::twitter()->setTitle('Demande d\'Investissement | Boiema Platform')
            ->setDescription('Soumettez votre demande d\'investissement sur la plateforme Boiema. Obtenez du financement pour votre projet au Maroc.');

        return view('forms.investment');
    }
}




























