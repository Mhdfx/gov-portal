<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SEO;

class INDHController extends Controller
{
    /**
     * Show the INDH form page.
     */
    public function index()
    {
        // Set SEO meta tags
        SEO::setTitle('Projet INDH | Boiema Platform');
        SEO::setDescription('Soumettez votre projet d\'Initiative Nationale pour le Développement Humain sur la plateforme Boiema. Obtenez du financement pour vos projets communautaires au Maroc.');
        SEO::setCanonical(url()->current());
        
        // Open Graph
        SEO::opengraph()->setTitle('Projet INDH | Boiema Platform')
            ->setDescription('Soumettez votre projet d\'Initiative Nationale pour le Développement Humain sur la plateforme Boiema. Obtenez du financement pour vos projets communautaires au Maroc.')
            ->setUrl(url()->current())
            ->setType('website')
            ->setSiteName('Boiema Platform');
        
        // Twitter Card
        SEO::twitter()->setTitle('Projet INDH | Boiema Platform')
            ->setDescription('Soumettez votre projet d\'Initiative Nationale pour le Développement Humain sur la plateforme Boiema. Obtenez du financement pour vos projets communautaires au Maroc.');

        return view('forms.indh');
    }
}






























