<x-layouts.app>
    <div class="min-h-[calc(100vh-72px)] bg-[#f7f9fc] py-10 sm:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-[#ff7627]">Arsip dokumen</p>
                    <h1 class="text-3xl font-black tracking-tight text-[#0d1b3e]">History Transkrip</h1>
                    <p class="mt-2 text-sm text-slate-500">Buka kembali transkrip yang pernah disimpan untuk diperiksa atau dicetak ulang.</p>
                </div>
                <a href="{{ route('transkrip') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl bg-[#ff862d] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-orange-900/20 transition hover:bg-[#f4771f]">
                    + Buat Transkrip Baru
                </a>
            </div>

            @if ($transcripts->isEmpty())
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)]">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-50 text-2xl text-[#ff862d]">▤</div>
                    <h2 class="mt-4 font-extrabold text-[#0d1b3e]">Belum ada transkrip tersimpan</h2>
                    <p class="mt-2 text-sm text-slate-500">Simpan transkrip yang sudah selesai dibuat agar dapat dicetak kembali kapan saja.</p>
                </div>
            @else
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)]">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[680px] text-left">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="px-6 py-4 font-bold">Mahasiswa</th>
                                    <th class="px-6 py-4 font-bold">NIM</th>
                                    <th class="px-6 py-4 font-bold">Nomor Transkrip</th>
                                    <th class="px-6 py-4 font-bold">Terakhir disimpan</th>
                                    <th class="px-6 py-4 text-right font-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($transcripts as $transcript)
                                    <tr class="transition hover:bg-orange-50/40">
                                        <td class="px-6 py-5">
                                            <p class="font-bold text-[#0d1b3e]">{{ $transcript->student_name }}</p>
                                            <p class="mt-1 text-xs text-slate-400">{{ $transcript->mata_kuliah ? count($transcript->mata_kuliah) : 0 }} mata kuliah</p>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-slate-600">{{ $transcript->student_nim }}</td>
                                        <td class="px-6 py-5 text-sm text-slate-600">{{ $transcript->header['no_transkrip'] ?? '-' }}</td>
                                        <td class="px-6 py-5 text-sm text-slate-500">{{ $transcript->updated_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-5 text-right">
                                            <a href="{{ route('transkrip.edit', $transcript) }}" wire:navigate class="inline-flex items-center rounded-xl bg-[#34415f] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#26324d]">
                                                Buka & Cetak
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($transcripts->hasPages())
                        <div class="border-t border-slate-100 px-6 py-4">
                            {{ $transcripts->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
