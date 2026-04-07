<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SEO;

class TrainingController extends Controller
{
    /**
     * Show the training form page.
     */
    public function index()
    {
        // Set SEO meta tags
        SEO::setTitle('Demande de Formation | Boiema Platform');
        SEO::setDescription('Soumettez votre demande de formation sur la plateforme Boiema. Accédez à des formations professionnelles et développez vos compétences au Maroc.');
        SEO::setCanonical(url()->current());
        
        // Open Graph
        SEO::opengraph()->setTitle('Demande de Formation | Boiema Platform')
            ->setDescription('Soumettez votre demande de formation sur la plateforme Boiema. Accédez à des formations professionnelles et développez vos compétences au Maroc.')
            ->setUrl(url()->current())
            ->setType('website')
            ->setSiteName('Boiema Platform');
        
        // Twitter Card
        SEO::twitter()->setTitle('Demande de Formation | Boiema Platform')
            ->setDescription('Soumettez votre demande de formation sur la plateforme Boiema. Accédez à des formations professionnelles et développez vos compétences au Maroc.');

        return view('forms.training');
    }
}






























