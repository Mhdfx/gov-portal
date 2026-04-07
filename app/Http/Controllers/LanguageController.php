<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch application language.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:fr,ar,en',
        ]);

        Session::put('locale', $request->locale);

        return redirect()->back();
    }
}
