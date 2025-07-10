<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);
        
        $contact = Contact::create($validated);
        
        // Send email notification
        Mail::raw("New contact form submission:\n\nName: {$contact->name}\nEmail: {$contact->email}\nSubject: {$contact->subject}\n\nMessage:\n{$contact->message}", function ($message) use ($contact) {
            $message->to(config('mail.from.address'))
                   ->subject('New Contact Form Submission: ' . $contact->subject);
        });
        
        return redirect()->route('contact')
                        ->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
