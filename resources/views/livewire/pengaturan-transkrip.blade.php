<div
    x-data="{
        confirmationOpen: false,
        confirmationTitle: '',
        confirmationMessage: '',
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
                        <input type="text" wire:model="header.kementerian" class="modern-input" placeholder="Nama kementerian">
                        @error('header.kementerian') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label">
                        <span>Nama universitas <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model="header.universitas" class="modern-input" placeholder="Nama universitas">
                        @error('header.universitas') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label">
                        <span>Nama fakultas <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model="header.fakultas" class="modern-input" placeholder="Nama fakultas">
                        @error('header.fakultas') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label sm:col-span-2">
                        <span>Alamat dan kontak <span class="font-normal text-slate-400">(wajib)</span></span>
                        <textarea wire:model="header.alamat" rows="2" class="modern-input resize-y" placeholder="Alamat, telepon, dan kontak"></textarea>
                        @error('header.alamat') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
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
                        <input type="text" wire:model="pejabat.nama" class="modern-input" placeholder="Nama lengkap beserta gelar">
                        @error('pejabat.nama') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label">
                        <span>NIP <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model="pejabat.nip" class="modern-input" placeholder="Nomor induk pegawai">
                        @error('pejabat.nip') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="field-label">
                        <span>Jabatan <span class="font-normal text-slate-400">(wajib)</span></span>
                        <input type="text" wire:model="pejabat.jabatan" class="modern-input" placeholder="Contoh: Dekan">
                        @error('pejabat.jabatan') <span class="text-xs font-medium text-red-600">{{ $message }}</span> @enderror
                    </label>
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
