<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SEO;

class PorteurProjetController extends Controller
{
    /**
     * Show the porteur de projet form page.
     */
    public function index()
    {
        // Set SEO meta tags
        SEO::setTitle('Porteur de Projet | Boiema Platform');
        SEO::setDescription('Soumettez votre projet sur la plateforme Boiema. Obtenez du financement et du soutien pour développer votre projet au Maroc.');
        SEO::setCanonical(url()->current());
        
        // Open Graph
        SEO::opengraph()->setTitle('Porteur de Projet | Boiema Platform')
            ->setDescription('Soumettez votre projet sur la plateforme Boiema. Obtenez du financement et du soutien pour développer votre projet au Maroc.')
            ->setUrl(url()->current())
            ->setType('website')
            ->setSiteName('Boiema Platform');
        
        // Twitter Card
        SEO::twitter()->setTitle('Porteur de Projet | Boiema Platform')
            ->setDescription('Soumettez votre projet sur la plateforme Boiema. Obtenez du financement et du soutien pour développer votre projet au Maroc.');

        return view('forms.porteur-projet');
    }
}






























