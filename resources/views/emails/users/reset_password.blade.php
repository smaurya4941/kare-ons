<x-mail::message>
# Reset Your Password

Hi {{ $notifiable->name ?? 'there' }},

We received a request to reset the password for your {{ setting('site_name') }} account. Click the button below to choose a new password.

<x-mail::button :url="$url">
Reset Password
</x-mail::button>

This password reset link will expire in 60 minutes.

If you did not request a password reset, no further action is required — your password will remain unchanged.

Thanks,<br>
The {{ setting('site_name') }} Team
</x-mail::message>
