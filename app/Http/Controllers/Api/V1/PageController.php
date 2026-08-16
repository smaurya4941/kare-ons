<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\ContactInquiry;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('status', true)->firstOrFail();

        return new PageResource($page);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $inquiry = ContactInquiry::create($validated);
        \App\Models\AdminNotification::notifyCustomerMessage($inquiry);

        // Best-effort: the inquiry is already saved, so a mail failure must
        // not turn into an error response for the caller.
        try {
            $adminEmail = setting('site_email');
            if ($adminEmail) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\AdminContactInquiryNotification($inquiry));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['message' => 'Thank you! Your inquiry has been sent.'], 201);
    }
}
