<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class StaticController extends Controller
{
    /**
     * Display the About page
     */
    public function about()
    {
        return view('static.about');
    }

    /**
     * Display the Contact page
     */
    public function contact()
    {
        return view('static.contact');
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        try {
            // Send email notification
            Mail::to(config('mail.admin_email', 'admin@boiema.ma'))
                ->send(new ContactFormMail($request->all()));

            return redirect()->back()->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons bientôt.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.');
        }
    }
}