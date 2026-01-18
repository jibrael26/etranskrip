<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight no-print">
        {{ __('Input Data Transkrip') }}
    </h2>
</x-slot>

<div class="py-12" x-data="{
    parsePaste(e) {
        let pastedData = e.clipboardData.getData('text');
        let rows = pastedData.split('\n');
        let tempMataKuliah = [];

        rows.forEach((row) => {
            // Pecah berdasarkan TAB (Standar Excel)
            let cols = row.split('\t');

            // LOGIKA PARSING SESUAI GAMBAR EXCEL ANDA:
            // Excel Anda punya banyak kolom. Kita ambil berdasarkan index:
            // Index 0 = No
            // Index 1 = Kode (Opsional, tidak diminta di output tapi ada di excel)
            // Index 2 = Nama Matakuliah (INFORMATION THEORY, dll)
            // Index 3 = SKS (2, 4, dll)
            // Index Terakhir = Nilai Huruf (A, B, dll)

            if (cols.length >= 4) {
                // Ambil nilai huruf (biasanya kolom terakhir di blok copy)
                let grade = cols[cols.length - 1] ? cols[cols.length - 1].trim() : '';

                // Jika kolom terakhir kosong (mungkin spasi), coba cek kolom sebelumnya
                if (grade == '') {
                    grade = cols[cols.length - 2] ? cols[cols.length - 2].trim() : '';
                }

                tempMataKuliah.push({
                    no: cols[0].trim(),
                    nama: cols[2].trim(), // Kolom ke-3 di Excel adalah Nama
                    sks: cols[3].trim(), // Kolom ke-4 di Excel adalah SKS
                    nilai: grade // Kolom terakhir adalah Nilai
                });
            }
        });

        // Kirim data ke Livewire Controller
        $wire.set('mataKuliah', tempMataKuliah);
    }
}">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8 no-print">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-blue-200">
            <h3 class="font-bold text-lg mb-4 text-blue-800">1. Identitas Mahasiswa & Surat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <input type="text" wire:model.live="header.nama" placeholder="Nama Lengkap" class="border p-2 rounded">
                <input type="text" wire:model.live="header.nim" placeholder="NIM" class="border p-2 rounded">
                <input type="text" wire:model.live="header.ttl" placeholder="Tempat, Tanggal Lahir"
                    class="border p-2 rounded">
                <input type="text" wire:model.live="header.no_ijazah" placeholder="Nomor Ijazah"
                    class="border p-2 rounded">
                <input type="text" wire:model.live="header.tgl_lulus" placeholder="Tanggal Lulus"
                    class="border p-2 rounded">
                <input type="text" wire:model.live="header.no_transkrip" placeholder="Nomor Transkrip"
                    class="border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block font-bold text-sm mb-1 text-blue-800">2. Paste Data Excel (Ctrl+V)</label>
                <textarea @paste.prevent="parsePaste($event)"
                    class="w-full h-32 border border-blue-300 p-2 rounded bg-gray-50 focus:ring-2 focus:ring-blue-500"
                    placeholder="Blok tabel di Excel (mulai dari kolom No sampai Nilai Huruf), Copy, lalu Klik disini dan Paste..."></textarea>
                <p class="text-xs text-red-500 mt-1">*Fitur ini otomatis mendeteksi: Kolom 1(No), Kolom 3(Nama), Kolom
                    4(SKS), Kolom Terakhir(Nilai)</p>
            </div>

            <button onclick="window.print()"
                class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700">
                CETAK PDF (FOLIO/F4)
            </button>
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
                            <th class="border border-black px-1 py-1 w-6">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kolomKiri as $mk)
                            <tr>
                                <td class="border border-black px-1 text-center">{{ $mk['no'] }}</td>
                                <td class="border border-black px-1 truncate max-w-[180px]">{{ $mk['nama'] }}</td>
                                <td class="border border-black px-1 text-center">{{ $mk['sks'] }}</td>
                                <td class="border border-black px-1 text-center font-bold">{{ $mk['nilai'] }}</td>
                            </tr>
                        @endforeach
                        @if (count($kolomKiri) < 1)
                            <tr>
                                <td colspan="4" class="border border-black p-4 text-center italic">Belum ada data
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
                            <th class="border border-black px-1 py-1 w-6">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kolomKanan as $mk)
                            <tr>
                                <td class="border border-black px-1 text-center">{{ $mk['no'] }}</td>
                                <td class="border border-black px-1 truncate max-w-[180px]">{{ $mk['nama'] }}</td>
                                <td class="border border-black px-1 text-center">{{ $mk['sks'] }}</td>
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
