<x-mail::message>
# Welcome to {{ setting('site_name') }} 🌿

Hi {{ $user->name }},

Your account has been successfully created. We're thrilled to have you join the {{ setting('site_name') }} family.

Start exploring our range of premium Ayurvedic and herbal wellness products, crafted with nature's wisdom and backed by science.

<x-mail::button :url="url('/shop')">
Start Shopping
</x-mail::button>

If you have any questions, just reply to this email — we're always happy to help.

Thanks,<br>
The {{ setting('site_name') }} Team
</x-mail::message>
