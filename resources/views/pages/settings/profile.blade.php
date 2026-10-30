<?php

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

}; ?>

<section class="min-h-[calc(100vh-72px)] bg-[#f7f9fc] px-4 py-10 sm:px-6 lg:px-8">
<div class="mx-auto max-w-7xl">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile Settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="grid gap-5 rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)] sm:p-7">
            <label class="grid gap-2 text-sm font-bold text-slate-600">
                {{ __('Name') }}
                <input wire:model="name" type="text" required autofocus autocomplete="name" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#ff862d] focus:bg-white focus:ring-4 focus:ring-orange-100">
                @error('name') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
            </label>

            <div>
                <label class="grid gap-2 text-sm font-bold text-slate-600">
                    {{ __('Email') }}
                    <input wire:model="email" type="email" required autocomplete="email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#ff862d] focus:bg-white focus:ring-4 focus:ring-orange-100">
                    @error('email') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                </label>

                @if (Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                    <div class="mt-4 rounded-2xl border border-orange-200 bg-orange-50 p-4 text-sm text-orange-800">
                        <p>
                            {{ __('Your email address is unverified.') }}

                            <button type="button" class="font-bold underline hover:text-orange-950" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-bold text-green-700">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-4 border-t border-slate-100 pt-5">
                <button type="submit" class="rounded-xl bg-[#ff862d] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-orange-900/20 transition hover:bg-[#f4771f]" data-test="update-profile-button">
                    {{ __('Save') }}
                </button>

                <x-action-message class="text-sm font-bold text-green-600" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @if (! Auth::user() instanceof MustVerifyEmail || Auth::user()->hasVerifiedEmail())
            <livewire:settings.⚡delete-user-form />
        @endif
    </x-pages::settings.layout>
</div>
</section>
