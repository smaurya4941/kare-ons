<x-customer-layout>
    <x-slot name="title">Account Settings</x-slot>

    <div class="space-y-6">
        <div class="p-4 sm:p-6 bg-surface rounded-xl border border-outline-variant shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-surface rounded-xl border border-outline-variant shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-6 bg-white border border-error/30 rounded-xl shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-customer-layout>
