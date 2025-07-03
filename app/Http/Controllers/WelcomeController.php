<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('front_page');
    }

    public function contact(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Save to database
        Contact::create([
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
        ]);

        Mail::to('info@pitgtech.com')->send(new ContactFormMail($validated));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Thank you! Your message has been sent.']);
        }

        return redirect()->back()->with('success', 'Thank you! Your message has been sent.');
    }
}