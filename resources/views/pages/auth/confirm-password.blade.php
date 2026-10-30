<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-5">
            @csrf

            <label class="grid gap-2 text-sm font-bold text-slate-600">
                {{ __('Password') }}
                <input name="password" type="password" required autocomplete="current-password" placeholder="{{ __('Password') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#ff862d] focus:bg-white focus:ring-4 focus:ring-orange-100">
                @error('password') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
            </label>

            <button type="submit" class="w-full rounded-xl bg-[#ff862d] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-orange-900/20 transition hover:bg-[#f4771f]" data-test="confirm-password-button">
                {{ __('Confirm') }}
            </button>
        </form>
    </div>
</x-layouts::auth>
