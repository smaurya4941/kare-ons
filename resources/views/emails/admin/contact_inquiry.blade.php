<x-mail::message>
# 📩 New Contact Form Submission

<x-mail::panel>
**From:** {{ $inquiry->name }}<br>
**Email:** {{ $inquiry->email }}<br>
@if($inquiry->subject)
**Subject:** {{ $inquiry->subject }}<br>
@endif
**Received:** {{ $inquiry->created_at->format('M d, Y - h:i A') }}
</x-mail::panel>

### Message:
{{ $inquiry->message }}

<x-mail::button :url="route('admin.inquiries.show', $inquiry->id)">
View & Reply →
</x-mail::button>

{{ setting('site_name') }} Admin Notifications
</x-mail::message>
