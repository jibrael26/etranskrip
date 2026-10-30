<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <label class="grid gap-2 text-sm font-bold text-slate-600">
                {{ __('Email address') }}
                <input name="email" value="{{ old('email') }}" type="email" required autofocus autocomplete="email" placeholder="email@example.com" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#ff862d] focus:bg-white focus:ring-4 focus:ring-orange-100">
                @error('email') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
            </label>

            <!-- Password -->
            <div class="relative">
                <label class="grid gap-2 text-sm font-bold text-slate-600">
                    {{ __('Password') }}
                    <input name="password" type="password" required autocomplete="current-password" placeholder="{{ __('Password') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#ff862d] focus:bg-white focus:ring-4 focus:ring-orange-100">
                    @error('password') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                </label>

                @if (Route::has('password.request'))
                    <a class="absolute end-0 top-0 text-xs font-bold text-[#ed6f24] hover:underline" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <!-- Remember Me -->
            <label class="flex items-center gap-2 text-sm text-slate-600"><input name="remember" type="checkbox" @checked(old('remember')) class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]"> {{ __('Remember me') }}</label>

            <div class="flex items-center justify-end">
                <button type="submit" class="w-full rounded-xl bg-[#ff862d] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-orange-900/20 transition hover:bg-[#f4771f]" data-test="login-button">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>

    </div>
</x-layouts::auth>
