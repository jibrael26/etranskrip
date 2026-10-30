<section class="min-h-[calc(100vh-72px)] bg-[#f7f9fc] px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        @include('partials.settings-heading')

        <x-pages::settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
            <form wire:submit="updatePassword" class="grid gap-5 rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)] sm:p-7">
                @foreach ([
                    ['current_password', __('Current password'), 'current-password'],
                    ['password', __('New password'), 'new-password'],
                    ['password_confirmation', __('Confirm Password'), 'new-password'],
                ] as [$field, $label, $autocomplete])
                    <label class="grid gap-2 text-sm font-bold text-slate-600">
                        {{ $label }}
                        <input wire:model="{{ $field }}" type="password" required autocomplete="{{ $autocomplete }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#ff862d] focus:bg-white focus:ring-4 focus:ring-orange-100">
                        @error($field) <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                @endforeach

                <div class="flex flex-wrap items-center gap-4 border-t border-slate-100 pt-5">
                    <button type="submit" class="rounded-xl bg-[#ff862d] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-orange-900/20 transition hover:bg-[#f4771f]" data-test="update-password-button">
                        {{ __('Save') }}
                    </button>

                    <x-action-message class="text-sm font-bold text-green-600" on="password-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </form>
        </x-pages::settings.layout>
    </div>
</section>
