<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
        @include('layouts.navigation')

        <main>
            {{ $slot }}
        </main>

        @fluxScripts
    </body>
</html>
