<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Transkrip | Teknik Informatika UNIMA</title>
    <link rel="icon" href="{{ asset('images/logo-unima.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f9fc] text-slate-900 antialiased">
    <nav class="border-b border-white/10 bg-[#34415f] text-white">
        <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo-unima.png') }}" alt="Logo Universitas Negeri Manado" class="h-11 w-11 object-contain">
                <span>
                    <span class="block text-[10px] font-bold uppercase tracking-[0.2em] text-orange-200">Teknik Informatika UNIMA</span>
                    <span class="block text-lg font-extrabold">E-Transkrip</span>
                </span>
            </a>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-[#ff862d] px-4 py-2.5 text-sm font-bold transition hover:bg-[#f4771f]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-200 transition hover:bg-white/10 hover:text-white">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        <section class="relative overflow-hidden bg-[#34415f] text-white">
            <div class="pointer-events-none absolute -right-28 -top-32 h-96 w-96 rounded-full bg-orange-400/20 blur-3xl"></div>
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-20 sm:px-6 sm:py-28 lg:grid-cols-[1.2fr_.8fr] lg:items-center lg:px-8">
                <div class="relative">
                    <span class="inline-flex items-center gap-2 rounded-full border border-orange-200/20 bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-orange-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#ff862d]"></span> Administrasi Akademik Digital
                    </span>
                    <h1 class="mt-6 max-w-3xl text-4xl font-black tracking-tight sm:text-6xl">Transkrip akademik <span class="text-[#ff862d]">lebih mudah.</span></h1>
                    <p class="mt-6 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">E-Transkrip membantu pengelolaan transkrip mahasiswa Teknik Informatika Universitas Negeri Manado agar lebih rapi, cepat, dan siap dicetak kapan saja.</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('transkrip') }}" class="rounded-xl bg-[#ff862d] px-5 py-3.5 text-sm font-bold shadow-lg shadow-orange-950/25 transition hover:bg-[#f4771f]">Buat Transkrip Sekarang →</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-xl bg-[#ff862d] px-5 py-3.5 text-sm font-bold shadow-lg shadow-orange-950/25 transition hover:bg-[#f4771f]">Mulai Menggunakan →</a>
                        @endauth
                        <a href="#fitur" class="rounded-xl border border-white/20 bg-white/10 px-5 py-3.5 text-sm font-bold transition hover:bg-white/20">Pelajari Fitur</a>
                    </div>
                </div>
                <div class="relative flex justify-center">
                    <div class="absolute h-72 w-72 rounded-full bg-orange-400/10 blur-2xl"></div>
                    <div class="relative flex h-64 w-64 items-center justify-center rounded-full border border-white/15 bg-white/10 shadow-2xl sm:h-80 sm:w-80">
                        <img src="{{ asset('images/logo-unima.png') }}" alt="Logo Universitas Negeri Manado" class="h-48 w-48 object-contain drop-shadow-2xl sm:h-60 sm:w-60">
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#ff7627]">Satu platform untuk administrasi akademik</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-[#0d1b3e] sm:text-4xl">Dibuat untuk alur kerja Teknik Informatika UNIMA</h2>
                <p class="mt-4 text-sm leading-6 text-slate-500 sm:text-base">Kurangi pekerjaan berulang dan fokus pada pelayanan akademik yang akurat.</p>
            </div>
            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['01', 'Impor data nilai', 'Salin data mata kuliah dari Excel dan periksa nilai, bobot, semester, serta jenis mata kuliah dengan cepat.'],
                    ['02', 'Hitung otomatis', 'Total SKS, total nilai, IPK, nilai yudisium, dan predikat kelulusan dihitung dalam satu alur.'],
                    ['03', 'Simpan & cetak ulang', 'Arsipkan transkrip per akun dan buka kembali dokumen untuk diperiksa atau dicetak kapan saja.'],
                ] as [$number, $title, $description])
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)]">
                        <span class="text-sm font-black text-[#ff862d]">{{ $number }}</span>
                        <h3 class="mt-5 text-xl font-extrabold text-[#0d1b3e]">{{ $title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-500">{{ $description }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="border-y border-slate-200 bg-white">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 lg:grid-cols-[1fr_auto] lg:items-center lg:px-8">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#ff7627]">Teknik Informatika UNIMA</p>
                    <h2 class="mt-3 text-2xl font-black text-[#0d1b3e]">Mendukung layanan akademik yang tertib dan modern.</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">E-Transkrip dirancang untuk membantu pengelola akademik menyiapkan dokumen transkrip dengan format yang konsisten, data yang tersimpan, dan proses cetak yang praktis.</p>
                </div>
                <img src="{{ asset('images/logo-unima.png') }}" alt="Logo Universitas Negeri Manado" class="h-28 w-28 object-contain">
            </div>
        </section>
    </main>

    <footer class="bg-[#0d1b3e] px-4 py-8 text-slate-300 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 text-sm sm:flex-row sm:items-center sm:justify-between">
            <p class="font-bold text-white">E-Transkrip Teknik Informatika UNIMA</p>
            <p>Universitas Negeri Manado</p>
        </div>
    </footer>
</body>
</html>
