<x-layouts.app>
    <div x-data="{ activePanel: 'ringkasan' }" class="min-h-[calc(100vh-72px)] bg-[#f7f9fc] py-10 sm:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative mb-8 overflow-hidden rounded-3xl bg-[#34415f] p-6 text-white shadow-[0_20px_55px_-25px_rgba(15,35,75,.65)] sm:p-8">
                <div class="pointer-events-none absolute -right-20 -top-32 h-80 w-80 rounded-full bg-orange-400/20 blur-3xl"></div>
                <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-orange-300">Portal Akademik</p>
                        <h1 class="text-3xl font-black tracking-tight sm:text-4xl">Selamat datang, {{ auth()->user()->name }}.</h1>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">Kelola, simpan, dan cetak kembali transkrip akademik dengan lebih cepat dari satu tempat.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('transkrip') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl bg-[#ff862d] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-orange-950/20 transition hover:bg-[#f4771f]">
                            + Buat Transkrip
                        </a>
                        <a href="{{ route('transcripts.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                            Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>

            <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_15px_40px_-25px_rgba(15,35,75,.3)]">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total transkrip</p>
                            <p class="mt-2 text-3xl font-black text-[#0d1b3e]">{{ $totalTranscripts }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-lg text-[#ff862d]">▤</span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">Dokumen tersimpan di akun Anda</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_15px_40px_-25px_rgba(15,35,75,.3)]">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Mahasiswa</p>
                            <p class="mt-2 text-3xl font-black text-[#0d1b3e]">{{ $totalStudents }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-lg text-blue-600">◎</span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">Identitas unik pada arsip</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_15px_40px_-25px_rgba(15,35,75,.3)]">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Rata-rata IPK</p>
                            <p class="mt-2 text-3xl font-black text-[#0d1b3e]">{{ number_format($averageIpk, 2, ',', '.') }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-lg text-emerald-600">↗</span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">Dari seluruh transkrip tersimpan</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_15px_40px_-25px_rgba(15,35,75,.3)]">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status arsip</p>
                            <p class="mt-2 text-3xl font-black text-[#0d1b3e]">Aktif</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-lg text-violet-600">✓</span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">Siap digunakan untuk cetak ulang</p>
                </div>
            </div>

            <div class="mb-5 flex flex-wrap gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
                <button type="button" @click="activePanel = 'ringkasan'" :class="activePanel === 'ringkasan' ? 'bg-[#34415f] text-white' : 'text-slate-500 hover:bg-slate-50'" class="rounded-xl px-4 py-2.5 text-sm font-bold transition">Ringkasan</button>
                <button type="button" @click="activePanel = 'panduan'" :class="activePanel === 'panduan' ? 'bg-[#34415f] text-white' : 'text-slate-500 hover:bg-slate-50'" class="rounded-xl px-4 py-2.5 text-sm font-bold transition">Panduan cepat</button>
            </div>

            <div x-show="activePanel === 'ringkasan'" x-transition.opacity class="grid gap-6 lg:grid-cols-[1.35fr_1fr]">
                <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)]">
                    <div class="flex items-center justify-between border-b border-slate-100 p-5 sm:p-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#ff7627]">Aktivitas terbaru</p>
                            <h2 class="mt-1 text-xl font-extrabold text-[#0d1b3e]">Transkrip terakhir dibuat</h2>
                        </div>
                        <a href="{{ route('transcripts.index') }}" wire:navigate class="text-xs font-bold text-[#ed6f24] hover:underline">Lihat semua</a>
                    </div>
                    @if ($latestTranscript)
                        <div class="p-5 sm:p-6">
                            <div class="rounded-2xl bg-[#34415f] p-5 text-white">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-orange-300">Dokumen terbaru</p>
                                        <h3 class="mt-2 text-xl font-black">{{ $latestTranscript->student_name }}</h3>
                                        <p class="mt-1 text-sm text-slate-300">NIM {{ $latestTranscript->student_nim }}</p>
                                    </div>
                                    <span class="rounded-lg bg-white/10 px-2.5 py-1 text-xs font-bold">{{ $latestTranscript->header['no_transkrip'] ?? '-' }}</span>
                                </div>
                                <div class="mt-5 grid grid-cols-3 gap-3 border-t border-white/10 pt-4">
                                    <div><p class="text-[11px] text-slate-400">IPK</p><p class="mt-1 font-black">{{ number_format((float) $latestTranscript->ipk, 2, ',', '.') }}</p></div>
                                    <div><p class="text-[11px] text-slate-400">Total SKS</p><p class="mt-1 font-black">{{ number_format((float) $latestTranscript->total_sks, 0, ',', '.') }}</p></div>
                                    <div><p class="text-[11px] text-slate-400">Predikat</p><p class="mt-1 truncate font-black">{{ $latestTranscript->predikat ?: '-' }}</p></div>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between gap-3">
                                <p class="text-xs text-slate-400">Diperbarui {{ $latestTranscript->updated_at->format('d M Y, H:i') }}</p>
                                <a href="{{ route('transkrip.edit', $latestTranscript) }}" wire:navigate class="rounded-xl bg-[#ff862d] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#f4771f]">Buka & Cetak</a>
                            </div>
                        </div>
                    @else
                        <div class="p-10 text-center">
                            <p class="font-bold text-[#0d1b3e]">Belum ada aktivitas</p>
                            <p class="mt-1 text-sm text-slate-500">Buat transkrip pertama Anda untuk mulai mengisi arsip.</p>
                        </div>
                    @endif
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)] sm:p-6">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#ff7627]">Arsip terbaru</p>
                    <h2 class="mt-1 text-xl font-extrabold text-[#0d1b3e]">Daftar singkat</h2>
                    <div class="mt-5 space-y-3">
                        @forelse ($recentTranscripts as $transcript)
                            <a href="{{ route('transkrip.edit', $transcript) }}" wire:navigate class="flex items-center gap-3 rounded-2xl border border-slate-100 p-3 transition hover:border-orange-200 hover:bg-orange-50/50">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-xs font-black text-[#ed6f24]">{{ strtoupper(substr($transcript->student_name, 0, 1)) }}</span>
                                <span class="min-w-0 grow"><span class="block truncate text-sm font-bold text-[#0d1b3e]">{{ $transcript->student_name }}</span><span class="mt-0.5 block text-xs text-slate-400">{{ $transcript->student_nim }}</span></span>
                                <span class="text-xs font-bold text-[#34415f]">{{ number_format((float) $transcript->ipk, 2, ',', '.') }}</span>
                            </a>
                        @empty
                            <p class="rounded-2xl bg-slate-50 p-5 text-center text-sm text-slate-500">Belum ada data transkrip.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div x-show="activePanel === 'panduan'" x-cloak x-transition.opacity class="grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['01', 'Isi identitas', 'Lengkapi nama, NIM, tanggal lulus, dan data dokumen mahasiswa.'],
                    ['02', 'Impor data nilai', 'Salin tabel dari Excel, tempelkan ke area impor, lalu periksa hasilnya.'],
                    ['03', 'Simpan & cetak', 'Simpan dokumen agar masuk riwayat dan dapat dicetak kembali kapan saja.'],
                ] as [$number, $title, $description])
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)]">
                        <span class="text-sm font-black text-[#ff862d]">{{ $number }}</span>
                        <h2 class="mt-4 text-lg font-extrabold text-[#0d1b3e]">{{ $title }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
