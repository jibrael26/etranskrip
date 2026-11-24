<x-layouts.app>
    <div class="min-h-[calc(100vh-72px)] bg-[#f7f9fc] py-10 sm:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-[#ff7627]">Arsip dokumen</p>
                    <h1 class="text-3xl font-black tracking-tight text-[#0d1b3e]">Riwayat Transkrip</h1>
                    <p class="mt-2 text-sm text-slate-500">Cari dan kelola transkrip yang pernah disimpan.</p>
                </div>
                <a href="{{ route('transkrip') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl bg-[#ff862d] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-orange-900/20 transition hover:bg-[#f4771f]">
                    + Buat Transkrip
                </a>
            </div>

            @if (session('status'))
                <div role="status" class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('transcripts.index') }}" class="mb-6 flex flex-col gap-3 sm:flex-row">
                <label for="search" class="sr-only">Cari transkrip</label>
                <input
                    id="search"
                    name="search"
                    type="search"
                    value="{{ $search }}"
                    placeholder="Cari nama mahasiswa, NIM, atau nomor transkrip"
                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-orange-400 focus:ring-orange-400 sm:max-w-xl"
                >
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#34415f] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#26324d]">
                    Cari
                </button>
                @if ($search !== '')
                    <a href="{{ route('transcripts.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                        Hapus pencarian
                    </a>
                @endif
            </form>

            @if ($transcripts->isEmpty() && $search !== '')
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)]">
                    <h2 class="font-extrabold text-[#0d1b3e]">Transkrip tidak ditemukan</h2>
                    <p class="mt-2 text-sm text-slate-500">Coba gunakan nama mahasiswa, NIM, atau nomor transkrip yang berbeda.</p>
                </div>
            @elseif ($transcripts->isEmpty())
                <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)]">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-50 text-2xl text-[#ff862d]">▤</div>
                    <h2 class="mt-4 font-extrabold text-[#0d1b3e]">Belum ada transkrip tersimpan</h2>
                    <p class="mt-2 text-sm text-slate-500">Simpan transkrip yang sudah selesai dibuat agar dapat dibuka dan dicetak kembali kapan saja.</p>
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
                                    <th class="px-6 py-4 font-bold">Terakhir Disimpan</th>
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
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('transkrip.edit', $transcript) }}" wire:navigate class="inline-flex items-center rounded-xl bg-[#34415f] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#26324d]">
                                                    Lihat & Cetak
                                                </a>
                                                <form method="POST" action="{{ route('transcripts.destroy', $transcript) }}" onsubmit="return confirm('Hapus transkrip ini secara permanen?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="search" value="{{ $search }}">
                                                    <button type="submit" class="inline-flex items-center rounded-xl border border-red-200 px-3 py-2 text-xs font-bold text-red-700 transition hover:bg-red-50">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
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
