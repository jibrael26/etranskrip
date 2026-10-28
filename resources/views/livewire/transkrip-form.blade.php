<div x-data="{
    tableOpen: true,
    showAll: false,
    visibleLimit: 12,
    searchTerm: '',
    filterPaket: '',
    filterNilai: '',
    rowMatches(row) {
        const search = this.searchTerm.trim().toLowerCase();
        const name = (row.dataset.search || '').toLowerCase();
        const paket = row.dataset.paket || '';
        const nilai = row.dataset.nilai || '';

        return (!search || name.includes(search))
            && (!this.filterPaket || paket === this.filterPaket)
            && (!this.filterNilai || nilai === this.filterNilai);
    },
    rowVisible(row) {
        if (!this.rowMatches(row)) {
            return false;
        }

        if (this.showAll) {
            return true;
        }

        const rows = [...this.$root.querySelectorAll('tbody[data-course-body] tr')];
        return rows.filter((item) => this.rowMatches(item)).indexOf(row) < this.visibleLimit;
    },
    hasVisibleRows() {
        return [...this.$root.querySelectorAll('tbody[data-course-body] tr')]
            .some((row) => this.rowMatches(row));
    },
    parsePaste(e) {
        let pastedData = e.clipboardData.getData('text');
        let rows = pastedData.split('\n');
        let tempMataKuliah = [];

        rows.forEach((row) => {
            // Pecah berdasarkan TAB (Standar Excel)
            let cols = row.split('\t').map((col) => col.trim().replace(/^\uFEFF/, ''));

            // LOGIKA PARSING SESUAI GAMBAR EXCEL ANDA:
            // Excel Anda punya banyak kolom. Kita ambil berdasarkan index:
            // Index 0 = No, 2 = Nama, 3 = Total SKS, 5 = Paket Sem.,
            // 8 = Bobot Nilai (3.00, 4.00, dan seterusnya),
            // 9 = Kode Nilai (A, B, C, dan seterusnya).

            if (cols.length >= 10 && cols[0].toLowerCase() !== 'no') {
                tempMataKuliah.push({
                    no: cols[0].trim(),
                    nama: cols[2].trim(),
                    sks: cols[3].trim(),
                    paket: cols[5].trim(),
                    bobot: cols[8].trim(),
                    nilai: cols[9].trim()
                });
            }
        });

        // Kirim data ke Livewire Controller
        $wire.set('mataKuliah', tempMataKuliah);
    }
}">

    <div class="relative overflow-hidden bg-[#f7f9fc] py-10 sm:py-14 no-print">
        <div class="pointer-events-none absolute -right-24 -top-32 h-80 w-80 rounded-full bg-orange-100/70 blur-3xl"></div>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-9 max-w-3xl">
                <span class="mb-4 inline-flex items-center gap-2 rounded-full border border-orange-200 bg-orange-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[#ed6f24]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#ff862d]"></span> Administrasi Akademik
                </span>
                <h1 class="text-3xl font-black tracking-tight text-[#0d1b3e] sm:text-5xl">Buat transkrip akademik <span class="text-[#ff7627]">lebih mudah.</span></h1>
                <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-500 sm:text-base">Lengkapi identitas mahasiswa, masukkan data mata kuliah, lalu cetak dokumen resmi dalam format yang siap digunakan.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.55fr_1fr]">
                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)] sm:p-7">
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <div>
                            <p class="mb-1 text-xs font-bold uppercase tracking-[0.16em] text-[#ff7627]">Langkah 01</p>
                            <h2 class="text-xl font-extrabold text-[#0d1b3e]">Identitas mahasiswa</h2>
                        </div>
                        <span class="rounded-xl bg-orange-50 px-3 py-2 text-xs font-bold text-[#ed6f24]">Data utama</span>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <label class="field-label">Nama Lengkap<input type="text" wire:model.live="header.nama" placeholder="Masukkan nama lengkap" class="modern-input"></label>
                        <label class="field-label">NIM<input type="text" wire:model.live="header.nim" placeholder="Nomor induk mahasiswa" class="modern-input"></label>
                        <label class="field-label">Tempat, Tanggal Lahir<input type="text" wire:model.live="header.ttl" placeholder="Contoh: Manado, 12 Mei 2002" class="modern-input"></label>
                        <label class="field-label">Nomor Ijazah<input type="text" wire:model.live="header.no_ijazah" placeholder="Nomor ijazah" class="modern-input"></label>
                        <label class="field-label">Tanggal Lulus<input type="text" wire:model.live="header.tgl_lulus" placeholder="Tanggal lulus" class="modern-input"></label>
                        <label class="field-label">Nomor Transkrip<input type="text" wire:model.live="header.no_transkrip" placeholder="Nomor transkrip" class="modern-input"></label>
                    </div>
                </section>

                <section class="rounded-3xl bg-[#34415f] p-5 text-white shadow-[0_18px_50px_-20px_rgba(15,35,75,.55)] sm:p-7">
                    <div class="mb-6">
                        <p class="mb-1 text-xs font-bold uppercase tracking-[0.16em] text-orange-300">Langkah 02</p>
                        <h2 class="text-xl font-extrabold">Impor data nilai</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Salin tabel dari Excel, lalu tempel di area berikut.</p>
                    </div>
                    <textarea @paste.prevent="parsePaste($event)" class="h-32 w-full resize-none rounded-2xl border border-white/15 bg-white/10 p-4 text-sm text-white outline-none placeholder:text-slate-400 focus:border-orange-300 focus:ring-2 focus:ring-orange-300/30" placeholder="Klik lalu tekan Ctrl + V untuk menempel data Excel..."></textarea>
                    <p class="mt-3 text-xs leading-5 text-slate-300"><span class="font-bold text-orange-300">Tips:</span> Nama, Total SKS, Paket Sem., Bobot Nilai, dan Kode Nilai akan dideteksi otomatis dari tabel Excel.</p>
                    <button onclick="window.print()" class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-[#ff862d] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-orange-900/20 transition hover:bg-[#f4771f]">
                        Cetak PDF <span aria-hidden="true">→</span>
                    </button>
                </section>
            </div>

            <section class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)]">
                <div class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-7">
                    <div>
                        <p class="mb-1 text-xs font-bold uppercase tracking-[0.16em] text-[#ff7627]">Langkah 03</p>
                        <h2 class="text-xl font-extrabold text-[#0d1b3e]">Periksa & edit data mata kuliah</h2>
                        <p class="mt-1 text-sm text-slate-500">Data hasil impor dapat diperbaiki sebelum dicetak. Perubahan IPK akan dihitung otomatis.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click.prevent="tableOpen = !tableOpen" :aria-expanded="tableOpen.toString()" aria-controls="course-review-content" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-[#34415f] transition hover:border-[#34415f]">
                            <span x-text="tableOpen ? 'Sembunyikan tabel' : 'Tampilkan tabel'"></span>
                            <span class="text-base" x-text="tableOpen ? '−' : '+'"></span>
                        </button>
                        <button type="button" wire:click="tambahMataKuliah" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#34415f] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#26324d]">
                            <span class="text-lg leading-none">+</span> Tambah Mata Kuliah
                        </button>
                    </div>
                </div>

                @if (count($mataKuliah) > 0)
                    <div id="course-review-content" x-show="tableOpen" x-transition.opacity x-cloak>
                        <div class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50 p-4 sm:flex-row sm:items-center">
                            <label class="relative w-full min-w-0 grow basis-0 sm:min-w-[280px]">
                                <span class="sr-only">Cari mata kuliah</span>
                                <input type="search" x-model="searchTerm" class="search-input" placeholder="Cari nama atau nomor mata kuliah..." aria-label="Cari mata kuliah">
                                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/>
                                </svg>
                                <button type="button" x-show="searchTerm" x-cloak @click="searchTerm = ''" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg px-2 py-1 text-xs font-bold text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Hapus pencarian">×</button>
                            </label>
                            <select x-model="filterPaket" class="modern-input w-full sm:w-44">
                                <option value="">Semua paket</option>
                                @foreach (range(1, 8) as $paket)
                                    <option value="{{ $paket }}">Paket {{ $paket }}</option>
                                @endforeach
                            </select>
                            <select x-model="filterNilai" class="modern-input w-full sm:w-40">
                                <option value="">Semua nilai</option>
                                @foreach (['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'E'] as $nilai)
                                    <option value="{{ $nilai }}">{{ $nilai }}</option>
                                @endforeach
                            </select>
                            <button type="button" @click="showAll = !showAll" class="w-full whitespace-nowrap rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-bold text-[#34415f] transition hover:border-[#ff862d] hover:text-[#ed6f24] sm:w-auto" x-text="showAll ? 'Tampilkan sebagian' : 'Tampilkan semua'"></button>
                        </div>
                        <div class="max-h-[620px] overflow-auto">
                        <table class="w-full min-w-[920px] text-left text-sm">
                            <thead class="sticky top-0 z-10 bg-[#f1f5f9] text-[11px] uppercase tracking-wider text-slate-500 shadow-sm">
                                <tr>
                                    <th class="w-16 px-5 py-3 font-bold">No</th>
                                    <th class="px-3 py-3 font-bold">Nama Mata Kuliah</th>
                                    <th class="w-24 px-3 py-3 font-bold">SKS</th>
                                    <th class="w-28 px-3 py-3 font-bold">Paket Sem.</th>
                                    <th class="w-28 px-3 py-3 font-bold">Bobot Nilai</th>
                                    <th class="w-32 px-3 py-3 font-bold">Nilai</th>
                                    <th class="w-20 px-5 py-3 text-right font-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody data-course-body class="divide-y divide-slate-100">
                                @foreach ($mataKuliah as $index => $mk)
                                    <tr x-show="rowVisible($el)" x-cloak data-index="{{ $index }}" data-search="{{ strtolower(($mk['no'] ?? '') . ' ' . ($mk['nama'] ?? '')) }}" data-paket="{{ $mk['paket'] ?? '' }}" data-nilai="{{ $mk['nilai'] ?? '' }}" wire:key="mata-kuliah-{{ $index }}" class="odd:bg-white even:bg-slate-50/60 transition hover:bg-orange-50">
                                        <td class="px-5 py-3.5">
                                            <input type="text" wire:model.live="mataKuliah.{{ $index }}.no" class="edit-input text-center" aria-label="Nomor mata kuliah">
                                        </td>
                                        <td class="px-3 py-3.5">
                                            <input type="text" wire:model.live="mataKuliah.{{ $index }}.nama" class="edit-input" placeholder="Nama mata kuliah">
                                        </td>
                                        <td class="px-3 py-3.5">
                                            <input type="number" min="0" step="0.5" wire:model.live="mataKuliah.{{ $index }}.sks" class="edit-input" placeholder="0">
                                        </td>
                                        <td class="px-3 py-3.5">
                                            <input type="text" wire:model.live="mataKuliah.{{ $index }}.paket" class="edit-input" placeholder="1">
                                        </td>
                                        <td class="px-3 py-3.5">
                                            <input type="number" min="0" max="4" step="0.01" wire:model.live="mataKuliah.{{ $index }}.bobot" class="edit-input" placeholder="0.00">
                                        </td>
                                        <td class="px-3 py-3.5">
                                            <select wire:model.live="mataKuliah.{{ $index }}.nilai" class="edit-input">
                                                <option value="">Pilih nilai</option>
                                                @foreach (['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'E'] as $nilai)
                                                    <option value="{{ $nilai }}">{{ $nilai }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <button type="button" wire:click="hapusMataKuliah({{ $index }})" class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600" title="Hapus baris" aria-label="Hapus baris">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 7h12m-9 0V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7m-7 0 .7 12.2a1.5 1.5 0 0 0 1.5 1.3h3.6a1.5 1.5 0 0 0 1.5-1.3L16 7M10 11v6m4-6v6"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div x-show="!hasVisibleRows()" x-cloak class="p-8 text-center text-sm text-slate-500">
                        Tidak ada mata kuliah yang sesuai dengan pencarian atau filter.
                    </div>
                    </div>
                    <div x-show="tableOpen" x-cloak class="grid gap-3 border-t border-slate-100 bg-slate-50 p-4 sm:grid-cols-3 sm:p-6">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Mata kuliah</p>
                            <p class="mt-1 text-xl font-black text-[#0d1b3e]">{{ count($mataKuliah) }}</p>
                        </div>
                        <label class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <span class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                Total SKS
                                <span class="normal-case tracking-normal text-slate-400">edit manual</span>
                            </span>
                            <input type="number" min="0" step="0.5" wire:model.live="totalSks" @disabled(!$ringkasanManual) class="summary-input mt-2" aria-label="Total SKS">
                        </label>
                        <label class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <span class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                IPK
                                <span class="normal-case tracking-normal text-slate-400">edit manual</span>
                            </span>
                            <input type="number" min="0" max="4" step="0.01" wire:model.live="ipk" @disabled(!$ringkasanManual) class="summary-input mt-2" aria-label="IPK">
                        </label>
                        <div class="flex items-center gap-3 sm:col-span-3">
                            <input id="ringkasan-manual" type="checkbox" wire:model.live="ringkasanManual" class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]">
                            <label for="ringkasan-manual" class="text-xs font-semibold text-slate-600">Aktifkan edit manual Total SKS dan IPK</label>
                            <span class="text-xs text-slate-400">{{ $ringkasanManual ? 'Nilai manual aktif' : 'Nilai dihitung dari tabel' }}</span>
                        </div>
                    </div>
                @else
                    <div class="p-8 text-center sm:p-12">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-2xl text-[#ff862d]">✎</div>
                        <h3 class="mt-4 font-bold text-[#0d1b3e]">Belum ada data untuk diedit</h3>
                        <p class="mt-1 text-sm text-slate-500">Impor data Excel atau tambahkan mata kuliah secara manual.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <div class="paper-folio mx-auto bg-white text-black leading-tight relative">

        <div class="flex items-center justify-center border-b-2 border-black pb-2 mb-2">
            <div class="absolute left-10 top-8">
                <div
                    class="w-16 h-16 border border-gray-400 flex items-center justify-center text-xs text-center rounded-full">
                    LOGO
                </div>
            </div>

            <div class="text-center w-full">
                <h3 class="text-sm font-bold uppercase">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</h3>
                <h1 class="text-xl font-bold uppercase">UNIVERSITAS NEGERI MANADO</h1>
                <h2 class="text-lg font-bold uppercase">FAKULTAS TEKNIK</h2>
                <p class="text-xs italic">Alamat: Kampus UNIMA Tondano 95618 Telepon: (0431) 322543, Fax: (0431) 322543
                </p>
            </div>
        </div>

        <div class="text-center mb-4">
            <h2 class="font-bold text-lg underline">TRANSKRIP AKADEMIK</h2>
            <div class="flex justify-center gap-8 text-xs font-bold">
                <p>PROGRAM: SARJANA</p>
                <p>NOMOR TRANSKRIP: {{ $header['no_transkrip'] }}</p>
            </div>
        </div>

        <div class="mb-2 text-xs font-bold">
            <div class="flex">
                <div class="w-32">Nama</div>
                <div class="w-4">:</div>
                <div class="flex-1">{{ $header['nama'] }}</div>

                <div class="w-32">Nomor Ijazah</div>
                <div class="w-4">:</div>
                <div class="w-48">{{ $header['no_ijazah'] }}</div>
            </div>
            <div class="flex">
                <div class="w-32">Tempat/Tanggal Lahir</div>
                <div class="w-4">:</div>
                <div class="flex-1">{{ $header['ttl'] }}</div>

                <div class="w-32">Tanggal Lulus</div>
                <div class="w-4">:</div>
                <div class="w-48">{{ $header['tgl_lulus'] }}</div>
            </div>
            <div class="flex">
                <div class="w-32">NIM</div>
                <div class="w-4">:</div>
                <div class="flex-1">{{ $header['nim'] }}</div>
            </div>
            <div class="flex">
                <div class="w-32">Program Studi</div>
                <div class="w-4">:</div>
                <div class="flex-1 uppercase">{{ $header['prodi'] }}</div>
            </div>
        </div>

        <div class="flex gap-4 items-start h-[210mm] overflow-hidden">

            <div class="w-1/2">
                <table class="w-full border-collapse border border-black text-[9px]">
                    <thead>
                        <tr class="bg-gray-100 text-center">
                            <th class="border border-black px-1 py-1 w-6">No</th>
                            <th class="border border-black px-1 py-1">Matakuliah</th>
                            <th class="border border-black px-1 py-1 w-6">SKS</th>
                            <th class="border border-black px-1 py-1 w-8">Bobot</th>
                            <th class="border border-black px-1 py-1 w-6">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kolomKiri as $mk)
                            <tr>
                                <td class="border border-black px-1 text-center">{{ $mk['no'] }}</td>
                                <td class="border border-black px-1 truncate max-w-[180px]">{{ $mk['nama'] }}</td>
                                <td class="border border-black px-1 text-center">{{ $mk['sks'] }}</td>
                                <td class="border border-black px-1 text-center">{{ $mk['bobot'] ?? '' }}</td>
                                <td class="border border-black px-1 text-center font-bold">{{ $mk['nilai'] }}</td>
                            </tr>
                        @endforeach
                        @if (count($kolomKiri) < 1)
                            <tr>
                                <td colspan="5" class="border border-black p-4 text-center italic">Belum ada data
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="w-1/2">
                <table class="w-full border-collapse border border-black text-[9px]">
                    <thead>
                        <tr class="bg-gray-100 text-center">
                            <th class="border border-black px-1 py-1 w-6">No</th>
                            <th class="border border-black px-1 py-1">Matakuliah</th>
                            <th class="border border-black px-1 py-1 w-6">SKS</th>
                            <th class="border border-black px-1 py-1 w-8">Bobot</th>
                            <th class="border border-black px-1 py-1 w-6">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kolomKanan as $mk)
                            <tr>
                                <td class="border border-black px-1 text-center">{{ $mk['no'] }}</td>
                                <td class="border border-black px-1 truncate max-w-[180px]">{{ $mk['nama'] }}</td>
                                <td class="border border-black px-1 text-center">{{ $mk['sks'] }}</td>
                                <td class="border border-black px-1 text-center">{{ $mk['bobot'] ?? '' }}</td>
                                <td class="border border-black px-1 text-center font-bold">{{ $mk['nilai'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-2 border-t-2 border-black pt-2 flex text-xs">
            <div class="w-1/2 pr-4">
                <table class="w-full font-bold">
                    <tr>
                        <td width="120">Total SKS</td>
                        <td>: {{ $totalSks }} SKS</td>
                    </tr>
                    <tr>
                        <td>Total Poin</td>
                        <td>: {{ $totalBobot }}</td>
                    </tr>
                    <tr>
                        <td>Indeks Prestasi (IPK)</td>
                        <td>: {{ $ipk }}</td>
                    </tr>
                    <tr>
                        <td>Predikat Kelulusan</td>
                        <td>: {{ $predikat }}</td>
                    </tr>
                </table>
            </div>

            <div class="w-1/2 text-center relative">
                <p>Tondano, {{ $pejabat['tgl_cetak'] }}</p>
                <p class="font-bold">{{ $pejabat['jabatan'] }}</p>

                <div class="h-16"></div>

                <p class="font-bold underline">{{ $pejabat['nama'] }}</p>
                <p>NIP. {{ $pejabat['nip'] }}</p>

                <div
                    class="absolute top-8 left-4 w-20 h-24 border border-black flex items-center justify-center text-[10px] text-gray-500">
                    Pas Foto<br>3 x 4
                </div>
            </div>
        </div>

    </div>

    <style>
        [x-cloak] { display: none !important; }
        .field-label { display: grid; gap: .5rem; color: #475569; font-size: .75rem; font-weight: 700; }
        .modern-input { width: 100%; border: 1px solid #e2e8f0; border-radius: .75rem; background: #f8fafc; padding: .75rem .875rem; color: #0f172a; font-size: .875rem; outline: none; transition: .2s; }
        .modern-input::placeholder { color: #94a3b8; font-weight: 400; }
        .modern-input:focus { border-color: #ff862d; background: white; box-shadow: 0 0 0 3px rgba(255, 134, 45, .15); }
        .search-input { width: 100%; min-height: 2.75rem; border: 1px solid #dbe3ee; border-radius: .75rem; background: white; padding: .7rem 2.5rem .7rem 2.5rem; color: #0f172a; font-size: .875rem; outline: none; transition: .2s; }
        .search-input::placeholder { color: #94a3b8; }
        .search-input:focus { border-color: #ff862d; box-shadow: 0 0 0 3px rgba(255, 134, 45, .14); }
        .edit-input { width: 100%; border: 1px solid #e2e8f0; border-radius: .65rem; background: #f8fafc; padding: .6rem .7rem; color: #0f172a; font-size: .875rem; outline: none; transition: .2s; }
        .edit-input:focus { border-color: #ff862d; background: white; box-shadow: 0 0 0 3px rgba(255, 134, 45, .12); }
        .summary-input { width: 100%; border: 1px solid #e2e8f0; border-radius: .75rem; background: #f8fafc; padding: .65rem .75rem; color: #0f172a; font-size: 1rem; font-weight: 800; outline: none; transition: .2s; }
        .summary-input:focus { border-color: #ff862d; background: white; box-shadow: 0 0 0 3px rgba(255, 134, 45, .12); }
        .summary-input:disabled { cursor: not-allowed; opacity: .6; }
        .paper-folio {
            width: 215mm;
            height: 330mm;
            padding: 10mm 15mm;
            /* Atur margin sesuai printer */
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            font-family: 'Times New Roman', serif;
        }

        @media print {
            @page {
                size: 215mm 330mm;
                margin: 0;
            }

            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
            }

            .no-print,
            nav,
            header {
                display: none !important;
            }

            .paper-folio {
                box-shadow: none;
                margin: 0;
                width: 100%;
                height: 100%;
                page-break-after: always;
            }
        }
    </style>
</div>
