@extends('admin.settings.layout')

@section('title', 'Invoice Settings')

@section('settings_content')
<div class="p-6 space-y-6">
    <div class="border-b pb-3">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600">receipt_long</span>
            Invoice Settings
        </h3>
        <p class="text-sm text-gray-500">Manage billing and invoice format details.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Prefix</label>
        <input type="text" name="invoice_prefix" value="{{ old('invoice_prefix', $settings->invoice_prefix) }}" placeholder="e.g. KO-" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono uppercase">
        <p class="text-xs text-gray-500 mt-1">Example: If KO- is used, invoices will be KO-001, KO-002.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Company Details (For Invoice)</label>
        <textarea name="invoice_company_details" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Company Name, Address, Contact Info...">{{ old('invoice_company_details', $settings->invoice_company_details) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">GST/VAT Number</label>
        <input type="text" name="invoice_gst_number" value="{{ old('invoice_gst_number', $settings->invoice_gst_number) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm uppercase">
        <p class="text-xs text-gray-500 mt-1">This will be printed on customer invoices.</p>
    </div>
</div>
@endsection
