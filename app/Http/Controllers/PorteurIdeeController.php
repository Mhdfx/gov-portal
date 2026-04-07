<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SEO;

class PorteurIdeeController extends Controller
{
    /**
     * Show the porteur d'idée form page.
     */
    public function index()
    {
        // Set SEO meta tags
        SEO::setTitle('Porteur d\'Idée | Boiema Platform');
        SEO::setDescription('Soumettez votre idée innovante sur la plateforme Boiema. Obtenez du soutien et des conseils pour développer votre concept au Maroc.');
        SEO::setCanonical(url()->current());
        
        // Open Graph
        SEO::opengraph()->setTitle('Porteur d\'Idée | Boiema Platform')
            ->setDescription('Soumettez votre idée innovante sur la plateforme Boiema. Obtenez du soutien et des conseils pour développer votre concept au Maroc.')
            ->setUrl(url()->current())
            ->setType('website')
            ->setSiteName('Boiema Platform');
        
        // Twitter Card
        SEO::twitter()->setTitle('Porteur d\'Idée | Boiema Platform')
            ->setDescription('Soumettez votre idée innovante sur la plateforme Boiema. Obtenez du soutien et des conseils pour développer votre concept au Maroc.');

        return view('forms.porteur-idee');
    }
}






























