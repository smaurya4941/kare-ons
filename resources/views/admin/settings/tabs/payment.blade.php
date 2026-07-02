@extends('admin.settings.layout')

@section('title', 'Payment Gateway Settings')

@section('settings_content')
<div class="p-6 space-y-6">
    <div class="border-b pb-3">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600">payments</span>
            Payment Gateway Keys
        </h3>
        <p class="text-sm text-gray-500">Configure your payment processors.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Razorpay Key ID</label>
        <input type="text" name="razorpay_key" value="{{ old('razorpay_key', $settings->razorpay_key) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Razorpay Secret</label>
        <input type="password" name="razorpay_secret" value="{{ old('razorpay_secret', $settings->razorpay_secret) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">
        <p class="text-xs text-gray-500 mt-1">Leave unchanged unless you are updating the secret.</p>
    </div>

    <div class="border-t pt-6 mt-2">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600">webhook</span>
            Webhook
        </h3>
        <p class="text-sm text-gray-500 mb-4">Lets Razorpay confirm orders even if the customer closes the browser after paying. Strongly recommended for production.</p>

        <div class="bg-gray-50 border border-gray-200 rounded-md p-3 mb-4">
            <label class="block text-xs font-medium text-gray-500 mb-1">Webhook URL (add this in your Razorpay Dashboard → Settings → Webhooks)</label>
            <code class="block text-sm text-gray-800 font-mono break-all">{{ route('webhooks.razorpay') }}</code>
            <p class="text-xs text-gray-500 mt-2">Subscribe to events: <span class="font-mono">payment.captured</span>, <span class="font-mono">payment.failed</span>, <span class="font-mono">order.paid</span>.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Secret</label>
            <input type="password" name="razorpay_webhook_secret" value="{{ old('razorpay_webhook_secret', $settings->razorpay_webhook_secret) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">
            <p class="text-xs text-gray-500 mt-1">Must exactly match the secret you set when creating the webhook in Razorpay.</p>
        </div>
    </div>
</div>
@endsection
