<x-mail::message>
Hi {{ $inquiry->name }},

{!! nl2br(e($replyMessage)) !!}

<x-mail::panel>
**Your original message:**<br>
{{ $inquiry->message }}
</x-mail::panel>

Thanks,<br>
The {{ setting('site_name') ?: config('app.name') }} Team
</x-mail::message>
