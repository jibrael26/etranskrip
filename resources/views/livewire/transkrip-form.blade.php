<div x-data="{
    tableOpen: true,
    showAll: false,
    visibleLimit: 12,
    searchTerm: '',
    filterPaket: '',
    filterNilai: '',
    filterJenis: '',
    rowMatches(row) {
        const search = this.searchTerm.trim().toLowerCase();
        const name = (row.dataset.search || '').toLowerCase();
        const paket = row.dataset.paket || '';
        const nilai = row.dataset.nilai || '';
        const jenis = row.dataset.jenis || '';

        return (!search || name.includes(search))
            && (!this.filterPaket || paket === this.filterPaket)
            && (!this.filterNilai || nilai === this.filterNilai)
            && (!this.filterJenis || jenis === this.filterJenis);
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
        const validGrades = ['A', 'B', 'C', 'D', 'E'];

        rows.forEach((row) => {
            // Pecah berdasarkan TAB (Standar Excel)
            let cols = row.split('\t').map((col) => col.trim().replace(/^\uFEFF/, '').replace(/\r$/, ''));

            // LOGIKA PARSING SESUAI GAMBAR EXCEL ANDA:
            // Excel Anda punya banyak kolom. Kita ambil berdasarkan index:
            // Index 0 = No, 2 = Nama, 3 = Total SKS, 5 = Paket Sem.,
            // Index 4 = Kode W/P (Wajib/Pilihan),
            // 8 = Bobot Nilai (3.00, 4.00, dan seterusnya),
            // 9 = Kode Nilai (A, B, C, dan seterusnya).

            if (cols.length >= 10 && cols[0].toLowerCase() !== 'no') {
                // Cari kode nilai dari isinya agar tetap terbaca saat kolom Excel bergeser.
                const nilaiIndex = cols.reduce((lastIndex, col, index) => {
                    return validGrades.includes(col.toUpperCase()) ? index : lastIndex;
                }, -1);
                const nilai = nilaiIndex >= 0 ? cols[nilaiIndex].toUpperCase() : '';
                const bobotIndex = nilaiIndex > 0
                    ? [...Array(nilaiIndex).keys()].reverse().find((index) => {
                        const value = Number.parseFloat(cols[index].replace(',', '.'));
                        return Number.isFinite(value) && value >= 0 && value <= 4;
                    })
                    : -1;

                tempMataKuliah.push({
                    no: cols[0].trim(),
                    nama: cols[2].trim(),
                    sks: cols[3].trim(),
                    jenis: ['W', 'P'].includes((cols[4] || '').toUpperCase()) ? cols[4].toUpperCase() : '',
                    paket: cols[5].trim(),
                    bobot: bobotIndex >= 0 ? cols[bobotIndex].replace(',', '.') : '',
                    nilai
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
                        <label class="field-label">Nomor Ijazah/NPISN<input type="text" wire:model.live="header.no_ijazah" placeholder="Nomor ijazah" class="modern-input"></label>
                        <label class="field-label">Tanggal Lulus<input type="text" wire:model.live="header.tgl_lulus" placeholder="Tanggal lulus" class="modern-input"></label>
                        <label class="field-label">Nomor Transkrip<input type="text" wire:model.live="header.no_transkrip" placeholder="Nomor transkrip" class="modern-input"></label>
                        <label class="field-label">Nilai Komprehensif<input type="number" min="0" max="4" step="0.01" wire:model.live="komprehensif" placeholder="0.00" class="modern-input"></label>
                        <label class="field-label">Predikat Kelulusan
                            <select wire:model.live="predikat" class="modern-input">
                                <option value="">Pilih predikat kelulusan</option>
                                <option value="Pujian">Pujian</option>
                                <option value="Sangat Memuaskan">Sangat Memuaskan</option>
                                <option value="Memuaskan">Memuaskan</option>
                            </select>
                        </label>
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
                    <div class="mt-6 grid gap-2 sm:grid-cols-2">
                        <button type="button" wire:click="simpanTranskrip" wire:loading.attr="disabled" class="flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20 disabled:cursor-wait disabled:opacity-60">
                            <span wire:loading.remove wire:target="simpanTranskrip">Simpan Transkrip</span>
                            <span wire:loading wire:target="simpanTranskrip">Menyimpan...</span>
                        </button>
                        <button type="button" onclick="window.print()" class="flex items-center justify-center gap-2 rounded-xl bg-[#ff862d] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-orange-900/20 transition hover:bg-[#f4771f]">
                            Cetak PDF <span aria-hidden="true">→</span>
                        </button>
                    </div>
                    @if ($savedMessage)
                        <p class="mt-3 text-center text-xs font-bold text-orange-200" role="status">{{ $savedMessage }}</p>
                    @endif
                    @if ($errors->any())
                        <p class="mt-3 text-center text-xs font-bold text-red-200" role="alert">{{ $errors->first() }}</p>
                    @endif
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
                                @foreach (['A', 'B', 'C', 'D', 'E'] as $nilai)
                                    <option value="{{ $nilai }}">{{ $nilai }}</option>
                                @endforeach
                            </select>
                            <select x-model="filterJenis" class="modern-input w-full sm:w-40">
                                <option value="">Semua jenis</option>
                                <option value="W">Wajib (W)</option>
                                <option value="P">Pilihan (P)</option>
                            </select>
                            <button type="button" @click="showAll = !showAll" class="w-full whitespace-nowrap rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-bold text-[#34415f] transition hover:border-[#ff862d] hover:text-[#ed6f24] sm:w-auto" x-text="showAll ? 'Tampilkan sebagian' : 'Tampilkan semua'"></button>
                        </div>
                        <div class="max-h-[620px] overflow-auto">
                        <table class="w-full min-w-[1020px] text-left text-sm">
                            <thead class="sticky top-0 z-10 bg-[#f1f5f9] text-[11px] uppercase tracking-wider text-slate-500 shadow-sm">
                                <tr>
                                    <th class="w-16 px-5 py-3 font-bold">No</th>
                                    <th class="px-3 py-3 font-bold">Nama Mata Kuliah</th>
                                    <th class="w-20 px-3 py-3 font-bold">W/P</th>
                                    <th class="w-24 px-3 py-3 font-bold">SKS</th>
                                    <th class="w-28 px-3 py-3 font-bold">Paket Sem.</th>
                                    <th class="w-28 px-3 py-3 font-bold">Bobot Nilai</th>
                                    <th class="w-32 px-3 py-3 font-bold">Nilai</th>
                                    <th class="w-20 px-5 py-3 text-right font-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody data-course-body class="divide-y divide-slate-100">
                                @foreach ($mataKuliah as $index => $mk)
                                    <tr x-show="rowVisible($el)" x-cloak data-index="{{ $index }}" data-search="{{ strtolower(($mk['no'] ?? '') . ' ' . ($mk['nama'] ?? '')) }}" data-paket="{{ $mk['paket'] ?? '' }}" data-nilai="{{ $mk['nilai'] ?? '' }}" data-jenis="{{ $mk['jenis'] ?? '' }}" wire:key="mata-kuliah-{{ $index }}" class="odd:bg-white even:bg-slate-50/60 transition hover:bg-orange-50">
                                        <td class="px-5 py-3.5">
                                            <input type="text" wire:model.live="mataKuliah.{{ $index }}.no" class="edit-input text-center" aria-label="Nomor mata kuliah">
                                        </td>
                                        <td class="px-3 py-3.5">
                                            <input type="text" wire:model.live="mataKuliah.{{ $index }}.nama" class="edit-input" placeholder="Nama mata kuliah">
                                        </td>
                                        <td class="px-3 py-3.5">
                                            <div class="edit-input bg-slate-100 text-center font-bold" aria-label="Kode jenis mata kuliah">
                                                {{ $mk['jenis'] ?? '-' }}
                                            </div>
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
                                                @foreach (['A', 'B', 'C', 'D', 'E'] as $nilai)
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
                    <div x-show="tableOpen" x-cloak class="grid gap-3 border-t border-slate-100 bg-slate-50 p-4 sm:grid-cols-4 sm:p-6">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Mata kuliah</p>
                            <p class="mt-1 text-xl font-black text-[#0d1b3e]">{{ count($mataKuliah) }}</p>
                        </div>
                        <label class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <span class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                Total SKS
                                <span class="normal-case tracking-normal text-slate-400">edit manual</span>
                            </span>
                            <input type="number" min="0" step="1" wire:model.live="totalSks" @disabled(!$ringkasanManual) class="summary-input mt-2" aria-label="Total SKS">
                        </label>
                        <label class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <span class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                Total Nilai
                                <span class="normal-case tracking-normal text-slate-400">edit manual</span>
                            </span>
                            <input type="number" min="0" step="1" wire:model.live="totalBobot" @disabled(!$ringkasanManual) class="summary-input mt-2" aria-label="Total Nilai">
                        </label>
                        <label class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <span class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                IPK
                                <span class="normal-case tracking-normal text-slate-400">edit manual</span>
                            </span>
                            <input type="number" min="0" max="4,00" step="0.01" wire:model.live="ipk" @disabled(!$ringkasanManual) class="summary-input mt-2" aria-label="IPK">
                        </label>
                        <div class="flex items-center gap-3 sm:col-span-3">
                            <input id="ringkasan-manual" type="checkbox" wire:model.live="ringkasanManual" class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]">
                            <label for="ringkasan-manual" class="text-xs font-semibold text-slate-600">Aktifkan edit manual Total SKS, Total Nilai, dan IPK</label>
                            <span class="text-xs text-slate-400">{{ $ringkasanManual ? 'Nilai manual aktif' : 'Nilai dihitung dari tabel' }}</span>
                        </div>
                        <div class="flex items-center gap-3 sm:col-span-3">
                            <input id="yudisium-manual" type="checkbox" wire:model.live="yudisiumManual" class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]">
                            <label for="yudisium-manual" class="text-xs font-semibold text-slate-600">Aktifkan edit manual Nilai Yudisium</label>
                            <input type="number" min="0" max="4" step="0.01" wire:model.live="nilaiYudisium" @disabled(!$yudisiumManual) class="summary-input max-w-32" aria-label="Nilai Yudisium">
                            <span class="text-xs text-slate-400">{{ $yudisiumManual ? 'Nilai manual aktif' : 'Nilai dihitung otomatis' }}</span>
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

    <div class="paper-preview flex justify-center overflow-x-auto bg-slate-100 px-4 py-8">
        <div class="paper-folio bg-white text-black leading-tight relative">

        <div class="transcript-header flex items-center border-b-[3px] border-black pb-2 mb-2">
            <div class="transcript-logo absolute left-[5mm] top-1/2 h-[24mm] w-[24mm] -translate-y-1/2">
                <img src="{{ asset('images/logo-unima.png') }}" alt="Logo Universitas Negeri Manado"
                    class="h-full w-full object-contain">
            </div>

            <div class="transcript-header-content text-center">
                <h3 class="text-sm font-bold uppercase">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</h3>
                <h1 class="text-xl font-bold uppercase">UNIVERSITAS NEGERI MANADO</h1>
                <h2 class="text-lg font-bold uppercase">FAKULTAS TEKNIK</h2>
                <p class="text-xs italic">Alamat: Kampus UNIMA Tondano 95618 Telepon: (0431) 322543, Fax: (0431) 322543
                </p>
            </div>
        </div>

        <div class="transcript-text mb-1 text-center">
            <h2 class="transcript-text-heading text-lg font-bold">TRANSKRIP AKADEMIK</h2>
            <p class="text-xs">PROGRAM : SARJANA</p>
            <p class="text-xs font-bold">NOMOR TRANSKRIP : {{ $header['no_transkrip'] }}</p>
            <p class="text-xs font-bold">NOMOR IJAZAH : {{ $header['no_ijazah'] }}</p>
        </div>

        <div class="transcript-text relative left-[15mm] mx-auto mb-2 grid w-[120mm] grid-cols-[45mm_0.75rem_1fr] gap-y-0.5 text-xs">
            <div>Nama</div>
            <div class="left-0">:</div>
            <div class="uppercase">{{ $header['nama'] }}</div>

            <div>Tempat / Tanggal Lahir</div>
            <div class="left-0">:</div>
            <div class="uppercase">{{ $header['ttl'] }}</div>

            <div>NIM</div>
            <div class="left-0">:</div>
            <div>{{ $header['nim'] }}</div>

            <div>Nama &amp; Jenjang Program Studi</div>
            <div class="left-0">:</div>
            <div class="uppercase">{{ $header['prodi'] }}</div>

            <div>Tanggal Lulus</div>
            <div class="left-0">:</div>
            <div class="uppercase">{{ $header['tgl_lulus'] }}</div>
        </div>

        <div class="transcript-tables flex items-stretch gap-4">
            <img
                class="transcript-table-watermark"
                src="{{ asset('images/logo-unima.png') }}"
                alt=""
                aria-hidden="true">

            <div class="transcript-column relative z-10 w-1/2">
                <table class="transcript-table h-full w-full border-collapse border border-black text-[13px]">
                    <thead>
                        <tr class="bg-gray-100 text-center">
                            <th class="border border-black px-1 py-1 w-6">NO</th>
                            <th class="border border-black px-1 py-1">MATAKULIAH</th>
                            <th class="border border-black px-1 py-1 w-6">SKS</th>
                            <th class="border border-black px-1 py-1 w-6">NILAI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($semesterKiri as $semester => $mataKuliahSemester)
                            @if (count($mataKuliahSemester) > 0)
                                <tr>
                                    <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                    <td class="semester-row-cell border border-black px-1 pt-2 pb-1">SEMESTER {{ $semester }}</td>
                                    <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                    <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                </tr>
                                @foreach ($mataKuliahSemester as $mk)
                                    <tr>
                                        <td class="border border-black px-1 text-center">{{ $mk['no'] }}</td>
                                        <td class="border border-black px-1 truncate max-w-[180px]">{{ $mk['nama'] }}</td>
                                        <td class="border border-black px-1 text-center">{{ $mk['sks'] }}</td>
                                        <td class="border border-black px-1 text-center">{{ $mk['nilai'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        @if (count($semesterLainnya) > 0)
                            <tr>
                                <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                <td class="semester-row-cell border border-black px-1 pt-2 pb-1">SEMESTER -</td>
                                <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                            </tr>
                        @endif
                        @foreach ($semesterLainnya as $mk)
                            <tr>
                                <td class="border border-black px-1 text-center">{{ $mk['no'] }}</td>
                                <td class="border border-black px-1 truncate max-w-[180px]">{{ $mk['nama'] }}</td>
                                <td class="border border-black px-1 text-center">{{ $mk['sks'] }}</td>
                                <td class="border border-black px-1 text-center">{{ $mk['nilai'] }}</td>
                            </tr>
                        @endforeach
                        @if (! $hasMataKuliah)
                            <tr>
                                <td colspan="4" class="border border-black p-4 text-center italic">Belum ada data</td>
                            </tr>
                        @endif
                        <tr class="ipk-summary ipk-summary-start">
                            <td rowspan="2" class="border border-black"></td>
                            <td rowspan="2" class="border border-black px-1"></td>
                            <td class="border border-black"></td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr class="ipk-summary">
                            <td class="border border-black px-1"></td>
                            <td class="border border-black px-1"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="transcript-column relative z-10 w-1/2">
                <table class="transcript-table h-full w-full border-collapse border border-black text-[12px]">
                    <thead>
                        <tr class="bg-gray-100 text-center">
                            <th class="border border-black px-1 py-1 w-6">NO</th>
                            <th class="border border-black px-1 py-1">MATAKULIAH</th>
                            <th class="border border-black px-1 py-1 w-6">SKS</th>
                            <th class="border border-black px-1 py-1 w-6">NILAI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($semesterKanan as $semester => $mataKuliahSemester)
                            @if (count($mataKuliahSemester) > 0)
                                <tr>
                                    <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                    <td class="semester-row-cell border border-black px-1 pt-2 pb-1">SEMESTER {{ $semester }}</td>
                                    <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                    <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                </tr>
                                @foreach ($mataKuliahSemester as $mk)
                                    <tr>
                                        <td class="border border-black px-1 text-center">{{ $mk['no'] }}</td>
                                        <td class="border border-black px-1 truncate max-w-[180px]">{{ $mk['nama'] }}</td>
                                        <td class="border border-black px-1 text-center">{{ $mk['sks'] }}</td>
                                        <td class="border border-black px-1 text-center">{{ $mk['nilai'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        @if (! $hasMataKuliah)
                            <tr>
                                <td colspan="4" class="border border-black p-4 text-center italic">Belum ada data</td>
                            </tr>
                        @endif
                        <tr class="ipk-summary ipk-summary-start">
                            <td rowspan="2" class="border border-black"></td>
                            <td rowspan="2" class="border border-black px-1 text-right">
                                <span class="ipk-summary-content">
                                    <span>IPK :</span>
                                    <u>Total Nilai</u>
                                    <span>=</span>
                                    <u>{{ number_format((float) $totalBobot, 0, ',', '.') }}</u>
                                    <span></span>
                                    <span class="text-center">SKS</span>
                                    <span></span>
                                    <span>{{ number_format((float) $totalSks, 0, ',', '.') }}</span>
                                </span>
                            </td>
                            <td class="border border-black"></td>
                        </tr>
                        <tr class="ipk-summary">
                            <td class="border border-black px-1 text-center">=</td>
                            <td class="border border-black px-1 text-center">{{ $ipk }}</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="transcript-index border border-black text-center">
            <div class="font-bold">Indeks Prestasi :</div>
            <div class="transcript-index-formula">
                <span class="transcript-index-fraction font-bold font-size= 9px; !important;">
                    <span class="font-bold"><u class="font-size= 9px; !important;">IPK (7) + Komprehensif (3)</u></span>
                    <span class="font-size= 9px; font-weight: bold !important;">10</span>
                </span>
                <span>=</span>
                <span class="transcript-index-fraction font-bold">
                    <span><u>{{ number_format((float) $ipk, 2, ',', '') }} (7) + {{ number_format((float) $komprehensif, 2, ',', '') }} (3)</u></span>
                    <span class="font-size= 9px; font-weight: bold !important;">10</span>
                </span>
                <span>=</span>
                <strong class="transcript-index-value font-bold">{{ number_format((float) $nilaiYudisium, 2, ',', '') }}</strong>
            </div>
        </div>

        <div class="mt-2 pt-2 flex text-xs">
            <div class=" pr-7">
                <div class="transcript-summary font-bold">
                    <div class="transcript-summary-row">
                        <span>Total SKS</span>
                        <span class="transcript-summary-row-value text-center">:</span>
                        <span class="transcript-summary-row-value-text">{{ number_format((float) $totalSks, 0, ',', '.') }} SKS</span>
                    </div>
                    <div class="transcript-summary-row">
                        <span>Indeks Prestasi</span>
                        <span class="transcript-summary-row-value text-center">:</span>
                        <span class="transcript-summary-row-value-text">{{ number_format((float) $nilaiYudisium, 2, ',', '') }}</span>
                    </div>
                    <div class="transcript-summary-row">
                        <span>Predikat Kelulusan</span>
                        <span class="transcript-summary-row-value text-center">:</span>
                        <span class="transcript-summary-row-value-text">{{ $predikat }}</span>
                    </div>
                </div>
                <div class="transcript-photo  w-20 h-24 border border-black flex items-center justify-center text-[10px] text-gray-500">
                    Pas Foto<br>3 x 4
                </div>
            </div>
            <div class="transcript-sign ml-auto w-1/2 text-center relative">
                <p class="font-bold">Tondano, {{ $header['tgl_lulus'] }}</p>
                <p class="font-bold">{{ $pejabat['jabatan'] }}</p>

                <div class="h-16"></div>

                <p class="font-bold underline">{{ $pejabat['nama'] }}</p>
                <p class="font-bold">NIP. {{ $pejabat['nip'] }}</p>
            </div>
        </div>

    </div>

</div>
