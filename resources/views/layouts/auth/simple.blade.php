<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#f7f9fc] antialiased">
        <div class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <a href="{{ url('/') }}" class="mb-6 flex items-center justify-center gap-3" wire:navigate>
                    <img src="{{ asset('images/logo-unima.png') }}" alt="Logo Universitas Negeri Manado" class="h-12 w-12 object-contain">
                    <span class="text-left">
                        <span class="block text-[10px] font-bold uppercase tracking-[0.2em] text-[#ed6f24]">Portal Akademik</span>
                        <span class="block text-xl font-black tracking-tight text-[#0d1b3e]">E-Transkrip</span>
                    </span>
                </a>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_20px_55px_-25px_rgba(15,35,75,.35)] sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
