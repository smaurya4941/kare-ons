@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Global Settings</h1>
    <p class="text-gray-600">Manage application-wide settings logically organized into categories.</p>
</div>

<div class="flex flex-col md:flex-row gap-6">
    <!-- Settings Sidebar -->
    <div class="w-full md:w-64 flex-shrink-0">
        <nav class="flex flex-col space-y-1 bg-white rounded-lg shadow-sm border border-gray-100 p-2">
            @php
                $tabs = [
                    'general' => ['icon' => 'settings_suggest', 'label' => 'General Settings'],
                    'homepage' => ['icon' => 'home', 'label' => 'Homepage Setup'],
                    'contact' => ['icon' => 'contact_mail', 'label' => 'Contact Details'],
                    'social' => ['icon' => 'share', 'label' => 'Social Links'],
                    'footer' => ['icon' => 'web', 'label' => 'Footer & Content'],
                    'payment' => ['icon' => 'payments', 'label' => 'Payment Gateway'],
                    'shipping' => ['icon' => 'local_shipping', 'label' => 'Shipping Settings'],
                    'seo' => ['icon' => 'travel_explore', 'label' => 'Global SEO'],
                    'email' => ['icon' => 'mail', 'label' => 'Email (SMTP)'],
                    'whatsapp' => ['icon' => 'forum', 'label' => 'WhatsApp API'],
                    'invoice' => ['icon' => 'receipt_long', 'label' => 'Invoice Settings'],
                ];
            @endphp
            
            @foreach($tabs as $key => $info)
                <a href="{{ route('admin.settings.index', $key) }}" class="px-4 py-3 text-sm font-medium rounded-lg flex items-center {{ $tab === $key ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors' }}">
                    <span class="material-symbols-outlined mr-3 text-[20px] {{ $tab === $key ? 'text-indigo-600' : 'text-gray-400' }}">{{ $info['icon'] }}</span>
                    {{ $info['label'] }}
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Settings Content -->
    <div class="flex-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.settings.update', $tab) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                @yield('settings_content')

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@yield('scripts')
