<div class="grid gap-6 lg:grid-cols-[220px_1fr]">
    <div class="w-full">
        <div class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-[#ff7627]">Pengaturan akun</div>
        <nav aria-label="{{ __('Settings') }}" class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
            <a href="{{ route('profile.edit') }}" wire:navigate class="block rounded-xl px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('profile.edit') ? 'bg-[#34415f] text-white' : 'text-slate-600 hover:bg-orange-50 hover:text-[#ed6f24]' }}">{{ __('Profile') }}</a>
            <a href="{{ route('user-password.edit') }}" wire:navigate class="block rounded-xl px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('user-password.edit') ? 'bg-[#34415f] text-white' : 'text-slate-600 hover:bg-orange-50 hover:text-[#ed6f24]' }}">{{ __('Password') }}</a>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a href="{{ route('two-factor.show') }}" wire:navigate class="block rounded-xl px-3 py-2.5 text-sm font-bold transition {{ request()->routeIs('two-factor.show') ? 'bg-[#34415f] text-white' : 'text-slate-600 hover:bg-orange-50 hover:text-[#ed6f24]' }}">{{ __('Two-Factor Auth') }}</a>
            @endif
        </nav>
    </div>

    <flux:separator class="md:hidden" />

    <div class="min-w-0">
        <h2 class="text-2xl font-black tracking-tight text-[#0d1b3e]">{{ $heading ?? '' }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $subheading ?? '' }}</p>

        <div class="mt-6 w-full">
            {{ $slot }}
        </div>
    </div>
</div>
