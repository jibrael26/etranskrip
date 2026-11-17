<div
    x-data="{
        confirmationOpen: false,
        confirmationTitle: '',
        confirmationMessage: '',
        headerFormatOpen: true,
        signatoryFormatOpen: true,
        contentFormatOpen: false,
        openConfirmation() {
            this.confirmationTitle = 'Simpan pengaturan?';
            this.confirmationMessage = 'Perubahan kop surat dan pejabat akan digunakan pada transkrip baru.';
            this.confirmationOpen = true;
        },
        closeConfirmation() {
            this.confirmationOpen = false;
        },
        confirmSave() {
            this.closeConfirmation();
            this.$wire.simpanPengaturan();
        }
    }"
    @keydown.escape.window="closeConfirmation()"
    class="min-h-[calc(100vh-72px)] bg-[#f7f9fc] py-8 sm:py-14"
>
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-[#ff7627]">Konfigurasi dokumen</p>
            <h1 class="text-3xl font-black tracking-tight text-[#0d1b3e]">Pengaturan Transkrip</h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-500">Atur kop surat dan pejabat penandatangan yang akan digunakan sebagai default pada transkrip baru.</p>
        </div>

        @if ($savedMessage)
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700" role="status">
                {{ $savedMessage }}
            </div>
        @endif

        <form @submit.prevent="openConfirmation()" class="space-y-6">
            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)] sm:p-7">
                <div class="mb-6">
                    <p class="mb-1 text-xs font-bold uppercase tracking-[0.16em] text-[#ff7627]">Micro feature 01</p>
                    <h2 class="text-xl font-extrabold text-[#0d1b3e]">Kop surat transkrip</h2>
                    <p class="mt-2 text-sm text-slate-500">Informasi ini tampil di bagian atas dokumen transkrip.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="field-label sm:col-span-2">
                        <span>Nama kementerian <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model.live="header.kementerian" class="modern-input" placeholder="Nama kementerian">
                        @error('header.kementerian') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label">
                        <span>Nama universitas <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model.live="header.universitas" class="modern-input" placeholder="Nama universitas">
                        @error('header.universitas') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label">
                        <span>Nama fakultas <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model.live="header.fakultas" class="modern-input" placeholder="Nama fakultas">
                        @error('header.fakultas') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label sm:col-span-2">
                        <span>Alamat dan kontak <span class="font-normal text-slate-400">(wajib)</span></span>
                        <textarea wire:model.live="header.alamat" rows="2" class="modern-input resize-y" placeholder="Alamat, telepon, dan kontak"></textarea>
                        @error('header.alamat') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>
                <div class="mt-6 border-t border-slate-100 pt-6">
                    <button type="button" @click="headerFormatOpen = !headerFormatOpen" :aria-expanded="headerFormatOpen.toString()" aria-controls="header-format-options" class="flex w-full items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-left transition hover:border-orange-200 hover:bg-orange-50/50">
                        <span>
                            <span class="block text-sm font-extrabold text-[#0d1b3e]">Format teks kop</span>
                            <span class="mt-1 block text-xs font-normal text-slate-500">Atur tampilan setiap baris kop surat.</span>
                        </span>
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-lg font-bold text-[#ff862d] shadow-sm" x-text="headerFormatOpen ? '−' : '+'"></span>
                    </button>
                    <div id="header-format-options" x-show="headerFormatOpen" x-transition.opacity x-cloak class="mt-3 space-y-3">
                        @foreach (['kementerian' => 'Nama kementerian', 'universitas' => 'Nama universitas', 'fakultas' => 'Nama fakultas', 'alamat' => 'Alamat dan kontak'] as $field => $label)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="mb-3 text-xs font-bold text-slate-600">{{ $label }}</p>
                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                                    <label class="field-label">
                                        <span>Font</span>
                                        <select wire:model.live="headerStyle.{{ $field }}.font_family" class="modern-input">
                                            @foreach (['Calibri', 'Arial', 'Times New Roman', 'Georgia'] as $font)
                                                <option value="{{ $font }}">{{ $font }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="field-label">
                                        <span>Ukuran</span>
                                        <select wire:model.live="headerStyle.{{ $field }}.font_size" class="modern-input">
                                            @foreach ([8, 9, 10, 11, 12, 14, 16, 18, 20, 22, 24] as $size)
                                                <option value="{{ $size }}">{{ $size }} pt</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="field-label">
                                        <span>Perataan</span>
                                        <select wire:model.live="headerStyle.{{ $field }}.align" class="modern-input">
                                            <option value="left">Kiri</option>
                                            <option value="center">Tengah</option>
                                            <option value="right">Kanan</option>
                                        </select>
                                    </label>
                                    <div class="flex flex-wrap items-end gap-x-4 gap-y-3 lg:col-span-2">
                                        <label class="field-label min-w-28">
                                            <span>Ketebalan</span>
                                            <select wire:model.live="headerStyle.{{ $field }}.font_weight" class="modern-input">
                                                <option value="400">Normal</option>
                                                <option value="700">Tebal</option>
                                            </select>
                                        </label>
                                        <label class="inline-flex items-center gap-2 pb-3 text-xs font-bold text-slate-600">
                                            <input type="checkbox" wire:model.live="headerStyle.{{ $field }}.italic" class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]">
                                            Miring
                                        </label>
                                        <label class="inline-flex items-center gap-2 pb-3 text-xs font-bold text-slate-600">
                                            <input type="checkbox" wire:model.live="headerStyle.{{ $field }}.underline" class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]">
                                            Garis bawah
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)] sm:p-7">
                <div class="mb-6">
                    <p class="mb-1 text-xs font-bold uppercase tracking-[0.16em] text-[#ff7627]">Micro feature 02</p>
                    <h2 class="text-xl font-extrabold text-[#0d1b3e]">Pejabat penandatangan</h2>
                    <p class="mt-2 text-sm text-slate-500">Nama dan NIP ini akan ditampilkan di area tanda tangan transkrip baru.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="field-label sm:col-span-2">
                        <span>Nama pejabat <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model.live="pejabat.nama" class="modern-input" placeholder="Nama lengkap beserta gelar">
                        @error('pejabat.nama') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label">
                        <span>NIP <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model.live="pejabat.nip" class="modern-input" placeholder="Nomor induk pegawai">
                        @error('pejabat.nip') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label">
                        <span>Jabatan <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model.live="pejabat.jabatan" class="modern-input" placeholder="Contoh: Dekan">
                        @error('pejabat.jabatan') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>
                <div class="mt-6 border-t border-slate-100 pt-6">
                    <button type="button" @click="signatoryFormatOpen = !signatoryFormatOpen" :aria-expanded="signatoryFormatOpen.toString()" aria-controls="signatory-format-options" class="flex w-full items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-left transition hover:border-orange-200 hover:bg-orange-50/50">
                        <span>
                            <span class="block text-sm font-extrabold text-[#0d1b3e]">Format teks tanda tangan</span>
                            <span class="mt-1 block text-xs font-normal text-slate-500">Atur tampilan tanggal, jabatan, nama, dan NIP.</span>
                        </span>
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-lg font-bold text-[#ff862d] shadow-sm" x-text="signatoryFormatOpen ? '−' : '+'"></span>
                    </button>
                    <div id="signatory-format-options" x-show="signatoryFormatOpen" x-transition.opacity x-cloak class="mt-3 space-y-3">
                        @foreach (['tgl_cetak' => 'Tanggal', 'jabatan' => 'Jabatan', 'nama' => 'Nama pejabat', 'nip' => 'NIP'] as $field => $label)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="mb-3 text-xs font-bold text-slate-600">{{ $label }}</p>
                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                                    <label class="field-label">
                                        <span>Font</span>
                                        <select wire:model.live="pejabatStyle.{{ $field }}.font_family" class="modern-input">
                                            @foreach (['Calibri', 'Arial', 'Times New Roman', 'Georgia'] as $font)
                                                <option value="{{ $font }}">{{ $font }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="field-label">
                                        <span>Ukuran</span>
                                        <select wire:model.live="pejabatStyle.{{ $field }}.font_size" class="modern-input">
                                            @foreach ([8, 9, 10, 11, 12, 14, 16, 18, 20, 22, 24] as $size)
                                                <option value="{{ $size }}">{{ $size }} pt</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="field-label">
                                        <span>Perataan</span>
                                        <select wire:model.live="pejabatStyle.{{ $field }}.align" class="modern-input">
                                            <option value="left">Kiri</option>
                                            <option value="center">Tengah</option>
                                            <option value="right">Kanan</option>
                                        </select>
                                    </label>
                                    <div class="flex flex-wrap items-end gap-x-4 gap-y-3 lg:col-span-2">
                                        <label class="field-label min-w-28">
                                            <span>Ketebalan</span>
                                            <select wire:model.live="pejabatStyle.{{ $field }}.font_weight" class="modern-input">
                                                <option value="400">Normal</option>
                                                <option value="700">Tebal</option>
                                            </select>
                                        </label>
                                        <label class="inline-flex items-center gap-2 pb-3 text-xs font-bold text-slate-600">
                                            <input type="checkbox" wire:model.live="pejabatStyle.{{ $field }}.italic" class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]">
                                            Miring
                                        </label>
                                        <label class="inline-flex items-center gap-2 pb-3 text-xs font-bold text-slate-600">
                                            <input type="checkbox" wire:model.live="pejabatStyle.{{ $field }}.underline" class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]">
                                            Garis bawah
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_18px_50px_-25px_rgba(15,35,75,.25)] sm:p-7">
                <div class="mb-6">
                    <p class="mb-1 text-xs font-bold uppercase tracking-[0.16em] text-[#ff7627]">Micro feature 03</p>
                    <h2 class="text-xl font-extrabold text-[#0d1b3e]">Isi transkrip</h2>
                    <p class="mt-2 text-sm text-slate-500">Atur format teks pada judul, identitas, tabel, ringkasan, dan area foto.</p>
                </div>
                <button type="button" @click="contentFormatOpen = !contentFormatOpen" :aria-expanded="contentFormatOpen.toString()" aria-controls="content-format-options" class="flex w-full items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-left transition hover:border-orange-200 hover:bg-orange-50/50">
                    <span>
                        <span class="block text-sm font-extrabold text-[#0d1b3e]">Format teks isi dokumen</span>
                        <span class="mt-1 block text-xs font-normal text-slate-500">Gunakan pengaturan per bagian agar layout dokumen tetap terkontrol.</span>
                    </span>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-lg font-bold text-[#ff862d] shadow-sm" x-text="contentFormatOpen ? '−' : '+'"></span>
                </button>
                <div id="content-format-options" x-show="contentFormatOpen" x-transition.opacity x-cloak class="mt-3 space-y-3">
                    @foreach ([
                        'title' => 'Judul transkrip',
                        'document_meta' => 'Metadata dokumen',
                        'identity' => 'Identitas mahasiswa',
                        'table' => 'Isi tabel mata kuliah',
                        'table_header' => 'Header tabel',
                        'formula' => 'Formula indeks prestasi',
                        'summary' => 'Ringkasan nilai',
                        'photo' => 'Placeholder foto',
                    ] as $field => $label)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="mb-3 text-xs font-bold text-slate-600">{{ $label }}</p>
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                                <label class="field-label">
                                    <span>Font</span>
                                    <select wire:model.live="contentStyle.{{ $field }}.font_family" class="modern-input">
                                        @foreach (['Calibri', 'Arial', 'Times New Roman', 'Georgia', 'Cambria Math'] as $font)
                                            <option value="{{ $font }}">{{ $font }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="field-label">
                                    <span>Ukuran</span>
                                    <select wire:model.live="contentStyle.{{ $field }}.font_size" class="modern-input">
                                        @foreach ([8, 9, 10, 11, 12, 14, 16, 18, 20, 22, 24] as $size)
                                            <option value="{{ $size }}">{{ $size }} pt</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="field-label">
                                    <span>Perataan</span>
                                    <select wire:model.live="contentStyle.{{ $field }}.align" class="modern-input">
                                        <option value="left">Kiri</option>
                                        <option value="center">Tengah</option>
                                        <option value="right">Kanan</option>
                                    </select>
                                </label>
                                <label class="field-label">
                                    <span>Ketebalan</span>
                                    <select wire:model.live="contentStyle.{{ $field }}.font_weight" class="modern-input">
                                        <option value="400">Normal</option>
                                        <option value="700">Tebal</option>
                                    </select>
                                </label>
                                <div class="flex flex-wrap items-end gap-x-4 gap-y-3 pb-3">
                                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-600">
                                        <input type="checkbox" wire:model.live="contentStyle.{{ $field }}.italic" class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]">
                                        Miring
                                    </label>
                                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-600">
                                        <input type="checkbox" wire:model.live="contentStyle.{{ $field }}.underline" class="h-4 w-4 rounded border-slate-300 text-[#ff862d] focus:ring-[#ff862d]">
                                        Garis bawah
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-3xl border border-orange-200 bg-orange-50/60 p-5 shadow-[0_18px_50px_-25px_rgba(15,35,75,.15)] sm:p-7">
                <div class="mb-5">
                    <p class="mb-1 text-xs font-bold uppercase tracking-[0.16em] text-[#ff7627]">Preview</p>
                    <h2 class="text-xl font-extrabold text-[#0d1b3e]">Contoh tampilan</h2>
                    <p class="mt-2 text-sm text-slate-500">Periksa hasil format sebelum menyimpan pengaturan.</p>
                </div>
                <div class="paper-preview flex justify-center overflow-x-auto rounded-xl bg-slate-100 px-4 py-8">
                    <div class="paper-folio relative bg-white text-black leading-tight">
                        <div class="transcript-header mb-2 flex items-center border-b-[3px] border-black pb-2">
                            <div class="transcript-logo absolute left-[5mm] top-1/2 h-[24mm] w-[24mm] -translate-y-1/2">
                                <img src="{{ asset('images/logo-unima.png') }}" alt="Logo Universitas Negeri Manado" class="h-full w-full object-contain">
                            </div>
                            <div class="transcript-header-content text-center">
                                <h3 class="transcript-header-ministry uppercase" style="{{ $this->styleAttribute('header', 'kementerian') }}">{{ $header['kementerian'] }}</h3>
                                <h1 class="transcript-header-university uppercase" style="{{ $this->styleAttribute('header', 'universitas') }}">{{ $header['universitas'] }}</h1>
                                <h2 class="transcript-header-faculty uppercase" style="{{ $this->styleAttribute('header', 'fakultas') }}">{{ $header['fakultas'] }}</h2>
                                <p class="transcript-header-address" style="{{ $this->styleAttribute('header', 'alamat') }}">{{ $header['alamat'] }}</p>
                            </div>
                        </div>

                        <div class="transcript-text mb-1 text-center">
                            <h2 class="transcript-text-heading text-lg font-bold" style="{{ $this->styleAttribute('content', 'title') }}">TRANSKRIP AKADEMIK</h2>
                            <div style="{{ $this->styleAttribute('content', 'document_meta') }}">
                                <p>PROGRAM : SARJANA</p>
                                <p class="font-bold">NOMOR TRANSKRIP : 0033/S1/2026</p>
                                <p class="font-bold">NOMOR IJAZAH : CONTOH</p>
                            </div>
                        </div>

                        <div class="transcript-text relative left-[15mm] mx-auto mb-2 grid w-[120mm] grid-cols-[45mm_0.75rem_1fr] gap-y-0.5 text-xs" style="{{ $this->styleAttribute('content', 'identity') }}">
                            <div class="uppercase">Nama</div><div>:</div><div class="uppercase">CONTOH NAMA MAHASISWA</div>
                            <div class="uppercase">Tempat / Tanggal Lahir</div><div>:</div><div class="uppercase">MANADO, 12 MEI 2002</div>
                            <div class="uppercase">NIM</div><div>:</div><div class="uppercase">20260001</div>
                            <div class="uppercase">Nama &amp; Jenjang Program Studi</div><div>:</div><div class="uppercase">S1-TEKNIK INFORMATIKA</div>
                            <div class="uppercase">Tanggal Lulus</div><div>:</div><div class="uppercase">12 MEI 2026</div>
                        </div>

                        <div class="transcript-tables relative flex items-stretch gap-4">
                            <img class="transcript-table-watermark" src="{{ asset('images/logo-unima.png') }}" alt="" aria-hidden="true">
                            @foreach ([1, 2] as $table)
                                <div class="transcript-column relative z-10 w-1/2">
                                    <table class="transcript-table h-full w-full border-collapse border border-black text-[12px]" style="{{ $this->styleAttribute('content', 'table') }}">
                                        <thead>
                                            <tr class="bg-gray-100 text-center" style="{{ $this->styleAttribute('content', 'table_header') }}">
                                                <th class="w-6 border border-black px-1 py-1">NO</th>
                                                <th class="border border-black px-1 py-1">MATAKULIAH</th>
                                                <th class="w-6 border border-black px-1 py-1">SKS</th>
                                                <th class="w-6 border border-black px-1 py-1">NILAI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                                <td class="semester-row-cell border border-black px-1 pt-2 pb-1">SEMESTER {{ $table }}</td>
                                                <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                                <td class="semester-row-cell border border-black px-1 pt-2 pb-1"></td>
                                            </tr>
                                            <tr>
                                                <td class="border border-black px-1 text-center">1</td>
                                                <td class="border border-black px-1">CONTOH MATA KULIAH</td>
                                                <td class="border border-black px-1 text-center">3</td>
                                                <td class="border border-black px-1 text-center">A</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-black px-1 text-center">2</td>
                                                <td class="border border-black px-1">MATA KULIAH LAINNYA</td>
                                                <td class="border border-black px-1 text-center">2</td>
                                                <td class="border border-black px-1 text-center">B</td>
                                            </tr>
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
                            @endforeach
                        </div>

                        <div class="transcript-index border border-black text-center">
                            <div class="font-bold">Indeks Prestasi :</div>
                            <div class="transcript-index-formula" style="{{ $this->styleAttribute('content', 'formula') }}">IPK (7) + Komprehensif (3) &nbsp; = &nbsp; 3,50</div>
                        </div>

                        <div class="mt-0 flex pt-0 text-xs">
                            <div class="pr-7">
                                <div class="transcript-summary font-bold" style="{{ $this->styleAttribute('content', 'summary') }}">
                                    <div class="transcript-summary-row"><span>Total SKS</span><span class="transcript-summary-row-value text-center">:</span><span class="transcript-summary-row-value-text">5 SKS</span></div>
                                    <div class="transcript-summary-row"><span>Indeks Prestasi</span><span class="transcript-summary-row-value text-center">:</span><span class="transcript-summary-row-value-text">3,50</span></div>
                                    <div class="transcript-summary-row"><span>Predikat Kelulusan</span><span class="transcript-summary-row-value text-center">:</span><span class="transcript-summary-row-value-text">Sangat Memuaskan</span></div>
                                </div>
                                <div class="transcript-photo flex h-24 w-20 items-center justify-center border border-black text-[10px] text-gray-500" style="{{ $this->styleAttribute('content', 'photo') }}">Pas Foto<br>3 x 4</div>
                            </div>
                            <div class="transcript-sign relative ml-auto w-1/2 text-center">
                                <p style="{{ $this->styleAttribute('pejabat', 'tgl_cetak') }}">Tondano, 12 Mei 2026</p>
                                <p style="{{ $this->styleAttribute('pejabat', 'jabatan') }}">{{ $pejabat['jabatan'] }}</p>
                                <div class="h-16"></div>
                                <p style="{{ $this->styleAttribute('pejabat', 'nama') }}">{{ $pejabat['nama'] }}</p>
                                <p style="{{ $this->styleAttribute('pejabat', 'nip') }}">NIP. {{ $pejabat['nip'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('transkrip') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">Kembali ke Buat Transkrip</a>
                <button type="submit" wire:loading.attr="disabled" wire:target="simpanPengaturan" class="inline-flex items-center justify-center rounded-xl bg-[#ff862d] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-orange-900/20 transition hover:bg-[#f4771f] disabled:cursor-wait disabled:opacity-60">
                    <span wire:loading.remove wire:target="simpanPengaturan">Simpan Pengaturan</span>
                    <span wire:loading wire:target="simpanPengaturan">Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>

    <div
        x-show="confirmationOpen"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm"
        role="dialog"
        aria-modal="true"
        aria-labelledby="settings-confirmation-title"
        @click.self="closeConfirmation()"
    >
        <div x-show="confirmationOpen" x-transition class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-xl text-[#ff862d]" aria-hidden="true">!</div>
            <h2 id="settings-confirmation-title" class="mt-5 text-xl font-black text-[#0d1b3e]" x-text="confirmationTitle"></h2>
            <p class="mt-2 text-sm leading-6 text-slate-500" x-text="confirmationMessage"></p>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" @click="closeConfirmation()" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">Batal</button>
                <button type="button" @click="confirmSave()" class="rounded-xl bg-[#ff862d] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#f4771f]">Ya, Simpan</button>
            </div>
        </div>
    </div>
</div>
