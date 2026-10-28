<nav x-data="{ open: false, profileOpen: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 8" @keydown.escape.window="open = false; profileOpen = false" class="sticky top-0 z-30 border-b border-white/10 bg-[#34415f] text-white transition-shadow duration-300" :class="scrolled ? 'shadow-xl shadow-slate-900/25' : 'shadow-lg shadow-slate-900/10'">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-[72px] items-center justify-between gap-6">
            <a href="{{ route('dashboard') }}" class="flex shrink-0 items-center gap-3" wire:navigate>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-lg font-black text-[#ff862d] ring-1 ring-white/15">ET</span>
                <span class="hidden sm:block">
                    <span class="block text-[10px] font-bold uppercase tracking-[0.25em] text-orange-200">Portal Akademik</span>
                    <span class="block text-lg font-extrabold tracking-tight">E-Transkrip</span>
                </span>
            </a>

            <div class="hidden items-center gap-1 md:flex">
                <a href="{{ route('dashboard') }}" wire:navigate class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-[#ff862d] text-white shadow-lg shadow-orange-900/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">Dashboard</a>
                <a href="{{ route('transkrip') }}" wire:navigate class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request()->routeIs('transkrip') ? 'bg-[#ff862d] text-white shadow-lg shadow-orange-900/20' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">Buat Transkrip</a>
            </div>

            <div class="relative hidden sm:block">
                <button @click="profileOpen = !profileOpen" :aria-expanded="profileOpen" class="flex items-center gap-3 rounded-2xl p-1.5 pr-2 text-left transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-orange-300/60">
                    <div class="hidden lg:block">
                        <p class="text-sm font-bold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-300">Administrator</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#ffac3f] font-bold text-[#34415f]">{{ auth()->user()->initials() }}</span>
                    <svg class="hidden h-4 w-4 text-slate-300 lg:block transition-transform" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-show="profileOpen" x-transition @click.outside="profileOpen = false" class="absolute right-0 top-14 w-64 origin-top-right rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-xl shadow-slate-900/20" style="display: none;">
                    <div class="border-b border-slate-100 px-3 py-3">
                        <p class="truncate text-sm font-bold text-[#0d1b3e]">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" wire:navigate class="mt-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition hover:bg-orange-50 hover:text-[#ed6f24]">
                        <svg class="h-5 w-5 text-[#ff862d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.2 7.8a3.2 3.2 0 1 1-6.4 0 3.2 3.2 0 0 1 6.4 0ZM4.8 19.2a5.2 5.2 0 0 1 10.4 0M16 5.5h3.5M17.75 3.75v3.5"/></svg>
                        Pengaturan Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100 pt-1">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-600 transition hover:bg-red-50 hover:text-red-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m17 16 4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <button @click="open = !open" class="rounded-xl p-2 text-slate-200 hover:bg-white/10 md:hidden" aria-label="Buka menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <div x-show="open" x-transition class="border-t border-white/10 pb-4 pt-3 md:hidden">
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="block rounded-xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-[#ff862d]' : 'text-slate-200 hover:bg-white/10' }}">Dashboard</a>
                <a href="{{ route('transkrip') }}" wire:navigate class="block rounded-xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('transkrip') ? 'bg-[#ff862d]' : 'text-slate-200 hover:bg-white/10' }}">Buat Transkrip</a>
            </div>
            <div class="mt-3 flex items-center justify-between border-t border-white/10 pt-3">
                <div><p class="text-sm font-bold">{{ auth()->user()->name }}</p><p class="text-xs text-slate-300">{{ auth()->user()->email }}</p></div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('profile.edit') }}" wire:navigate class="rounded-lg px-3 py-2 text-xs font-bold text-orange-200 hover:bg-white/10">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="rounded-lg px-3 py-2 text-xs font-bold text-orange-200 hover:bg-white/10">Keluar</button></form>
                </div>
            </div>
        </div>
    </div>
</nav>
