<x-customer-layout>
    <x-slot name="title">Account Settings</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="space-y-6">
            <div class="p-5 bg-surface rounded-lg border border-outline-variant shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>
            
            <div class="p-5 bg-white border border-error/30 rounded-lg shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <div class="p-5 bg-surface rounded-lg border border-outline-variant shadow-sm">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-customer-layout>
