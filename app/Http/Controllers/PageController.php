<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ContactInquiry;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }
    
    public function show($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->where('status', true)->firstOrFail();
        return view('pages.show', compact('page'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            $inquiry = ContactInquiry::create($validated);
            \App\Models\AdminNotification::notifyCustomerMessage($inquiry);

            // Best-effort: the inquiry is already saved, so a mail failure
            // must not turn into an error response for the customer.
            try {
                $adminEmail = setting('site_email');
                if ($adminEmail) {
                    \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\AdminContactInquiryNotification($inquiry));
                }
            } catch (\Throwable $e) {
                report($e);
            }

            return redirect()->back()->with('success', 'Thank you! Your inquiry has been sent.');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->withInput()->with('error', 'Failed to send inquiry due to an unexpected error.');
        }
    }
}
