<?php

namespace App\Livewire;

use App\Models\Transcript;
use Livewire\Component;

class TranskripForm extends Component
{
    public ?int $transcriptId = null;
    public string $savedMessage = '';

    // Data Mahasiswa & Dokumen (Sesuai PDF)
    public $header = [
        'nama' => '',
        'nim' => '',
        'ttl' => '', // Tempat Tanggal Lahir
        'prodi' => 'S1-TEKNIK INFORMATIKA',
        'no_transkrip' => '0033/S1/2026',
        'no_ijazah' => '',
        'tgl_lulus' => '',
    ];

    // Data Pejabat Penandatangan
    public $pejabat = [
        'nama' => 'Dr. Hendro M. Sumual, ST., M.Pd., M.Eng',
        'nip' => '19840522 200801 1 005',
        'jabatan' => 'Dekan',
        'tgl_cetak' => '',
    ];

    // Penampung Data Mata Kuliah
    public $mataKuliah = [];

    // Variabel Hitungan
    public $totalSks = 0;
    public $totalBobot = 0;
    public $ipk = 0.00;
    public $komprehensif = 0.00;
    public $nilaiYudisium = 0.00;
    public $predikat = '';
    public $predikatManual = false;
    public $ringkasanManual = false;
    public $yudisiumManual = false;

    public function mount(?Transcript $transcript = null)
    {
        if ($transcript?->exists) {
            abort_unless($transcript->user_id === auth()->id(), 404);

            $this->transcriptId = $transcript->id;
            $this->header = array_merge($this->header, $transcript->header);
            $this->pejabat = array_merge($this->pejabat, $transcript->pejabat);
            $this->mataKuliah = $transcript->mata_kuliah;
            $this->totalSks = round($transcript->total_sks);
            $this->totalBobot = round($transcript->total_bobot);
            $this->ipk = $transcript->ipk;
            $this->komprehensif = $transcript->komprehensif;
            $this->nilaiYudisium = $transcript->nilai_yudisium;
            $this->predikat = $transcript->predikat ?? '';
            $this->predikatManual = true;
        }

        if ($this->pejabat['tgl_cetak'] === '') {
            $this->pejabat['tgl_cetak'] = now()->format('d F Y');
        }

        if (! $this->transcriptId) {
            $this->header['no_transkrip'] = $this->nomorTranskripBerikutnya();
            $this->hitungYudisium();
        }
    }

    private function nomorTranskripBerikutnya(): string
    {
        $nomorAwal = '0033/S1/2026';
        $terakhir = auth()->user()->transcripts()->latest('created_at')->value('header');

        if (! is_array($terakhir) || ! isset($terakhir['no_transkrip'])) {
            return $nomorAwal;
        }

        $nomorTerakhir = trim((string) $terakhir['no_transkrip']);

        if (! preg_match('/^(\\d+)(\\/.*)?$/', $nomorTerakhir, $matches)) {
            return $nomorAwal;
        }

        $angkaBerikutnya = (int) $matches[1] + 1;
        $formatSisa = $matches[2] ?? '/S1/'.now()->year;

        return str_pad((string) $angkaBerikutnya, max(4, strlen($matches[1])), '0', STR_PAD_LEFT).$formatSisa;
    }

    public function simpanTranskrip(): void
    {
        $this->validate([
            'header.nama' => ['required', 'string', 'max:255'],
            'header.nim' => ['required', 'string', 'max:100'],
            'mataKuliah' => ['required', 'array', 'min:1'],
        ], [
            'header.nama.required' => 'Nama mahasiswa wajib diisi.',
            'header.nim.required' => 'NIM wajib diisi.',
            'mataKuliah.min' => 'Tambahkan minimal satu mata kuliah sebelum menyimpan.',
        ]);

        $transcript = $this->transcriptId
            ? auth()->user()->transcripts()->findOrFail($this->transcriptId)
            : new Transcript(['user_id' => auth()->id()]);

        $transcript->fill([
            'student_name' => $this->header['nama'],
            'student_nim' => $this->header['nim'],
            'header' => $this->header,
            'pejabat' => $this->pejabat,
            'mata_kuliah' => array_values($this->mataKuliah),
            'total_sks' => $this->totalSks,
            'total_bobot' => $this->totalBobot,
            'ipk' => $this->ipk,
            'komprehensif' => $this->komprehensif,
            'nilai_yudisium' => $this->nilaiYudisium,
            'predikat' => $this->predikat,
        ])->save();

        $this->transcriptId = $transcript->id;
        $this->savedMessage = 'Transkrip berhasil disimpan.';
    }

    // Fungsi dijalankan setiap kali data mataKuliah berubah (setelah paste)
    public function updatedMataKuliah()
    {
        $this->hitungIPK();
    }

    public function updatedRingkasanManual(): void
    {
        if (! $this->ringkasanManual) {
            $this->hitungIPK();
        }
    }

    public function updatedIpk(): void
    {
        $this->ipk = min(4, max(0, (float) $this->ipk));
        $this->hitungYudisium();
        if (! $this->predikatManual) {
            $this->perbaruiPredikat();
        }
    }

    public function updatedKomprehensif(): void
    {
        $this->komprehensif = min(4, max(0, (float) $this->komprehensif));
        $this->hitungYudisium();
    }

    public function updatedYudisiumManual(): void
    {
        if (! $this->yudisiumManual) {
            $this->hitungYudisium();
        }
    }

    public function updatedNilaiYudisium(): void
    {
        $this->nilaiYudisium = min(4, max(0, (float) $this->nilaiYudisium));
    }

    public function updatedTotalSks(): void
    {
        $this->totalSks = max(0, (int) round((float) $this->totalSks));
    }

    public function updatedTotalBobot(): void
    {
        $this->totalBobot = max(0, (int) round((float) $this->totalBobot));
    }

    public function updatedPredikat(): void
    {
        $this->predikatManual = true;
    }

    public function tambahMataKuliah(): void
    {
        $this->mataKuliah[] = [
            'no' => (string) (count($this->mataKuliah) + 1),
            'nama' => '',
            'jenis' => '',
            'sks' => '',
            'paket' => '',
            'bobot' => '',
            'nilai' => '',
        ];

        $this->hitungIPK();
    }

    public function hapusMataKuliah(int $index): void
    {
        if (! array_key_exists($index, $this->mataKuliah)) {
            return;
        }

        unset($this->mataKuliah[$index]);
        $this->mataKuliah = array_values($this->mataKuliah);
        $this->hitungIPK();
    }

    public function hitungIPK()
    {
        $totalSks = 0;
        $totalPoin = 0;

        // Bobot angka dari Excel menjadi nilai utama. Nilai huruf hanya
        // digunakan sebagai fallback ketika bobot angka tidak tersedia.
        $rentangNilai = [
            'A' => [3.60, 4.00],
            'B' => [2.75, 3.59],
            'C' => [2.00, 2.74],
            'D' => [0.01, 1.99],
            'E' => [0.00, 0.00],
        ];

        foreach ($this->mataKuliah as $mk) {
            $sks = floatval($mk['sks']);
            $nilaiHuruf = strtoupper(trim($mk['nilai']));
            $bobotImpor = $mk['bobot'] ?? '';

            $bobot = is_numeric($bobotImpor) ? (float) $bobotImpor : null;

            if ($bobot !== null && $bobot >= 0 && $bobot <= 4) {
                $poin = $bobot;
            } elseif (isset($rentangNilai[$nilaiHuruf])) {
                [$minimum, $maksimum] = $rentangNilai[$nilaiHuruf];
                $poin = $minimum === $maksimum
                    ? $minimum
                    : round(($minimum + $maksimum) / 2, 2);
            } else {
                continue;
            }

            if ($sks > 0) {
                $totalSks += $sks;
                $totalPoin += ($sks * $poin);
            }
        }

        if (! $this->ringkasanManual) {
            $this->totalBobot = round($totalPoin);
        }
        $computedIpk = $totalSks > 0 ? round($totalPoin / $totalSks, 2) : 0.00;

        if (! $this->ringkasanManual) {
            $this->totalSks = round($totalSks);
            $this->ipk = $computedIpk;
        }

        $this->hitungYudisium();
        if (! $this->predikatManual) {
            $this->perbaruiPredikat();
        }
    }

    private function hitungYudisium(): void
    {
        if (! $this->yudisiumManual) {
            $this->nilaiYudisium = round((($this->ipk * 7) + ($this->komprehensif * 3)) / 10, 2);
        }
    }

    private function perbaruiPredikat(): void
    {
        if ($this->ipk >= 3.51) $this->predikat = 'Pujian';
        elseif ($this->ipk >= 3.01) $this->predikat = 'Sangat Memuaskan';
        elseif ($this->ipk >= 2.76) $this->predikat = 'Memuaskan';
        else $this->predikat = '-';
    }

    public function render()
    {
        $mataKuliah = array_values($this->mataKuliah);
        $semesterKiri = array_fill(1, 4, []);
        $semesterKanan = array_fill(5, 4, []);
        $semesterLainnya = [];

        foreach ($mataKuliah as $mk) {
            $semester = (int) preg_replace('/\D+/', '', (string) ($mk['paket'] ?? ''));

            if ($semester >= 1 && $semester <= 4) {
                $semesterKiri[$semester][] = $mk;
            } elseif ($semester >= 5 && $semester <= 8) {
                $semesterKanan[$semester][] = $mk;
            } else {
                $semesterLainnya[] = $mk;
            }
        }

        return view('livewire.transkrip-form', [
            'semesterKiri' => $semesterKiri,
            'semesterKanan' => $semesterKanan,
            'semesterLainnya' => $semesterLainnya,
            'hasMataKuliah' => count($mataKuliah) > 0,
        ])->layout('components.app-layout');
    }
}
