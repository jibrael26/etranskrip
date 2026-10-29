<?php

namespace App\Livewire;

use Livewire\Component;

class TranskripForm extends Component
{
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

    public function mount()
    {
        $this->pejabat['tgl_cetak'] = now()->format('d F Y');
        $this->hitungYudisium();
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

    public function updatedPredikat(): void
    {
        $this->predikatManual = true;
    }

    public function tambahMataKuliah(): void
    {
        $this->mataKuliah[] = [
            'no' => (string) (count($this->mataKuliah) + 1),
            'nama' => '',
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

        // Konversi Nilai Huruf ke Angka
        $bobotNilai = [
            'A' => 4.00, 'A-' => 3.75,
            'B+' => 3.50, 'B' => 3.00, 'B-' => 2.75,
            'C+' => 2.50, 'C' => 2.00, 'D' => 1.00, 'E' => 0.00
        ];

        foreach ($this->mataKuliah as $mk) {
            $sks = floatval($mk['sks']);
            $nilaiHuruf = strtoupper(trim($mk['nilai']));
            $bobotImpor = $mk['bobot'] ?? '';

            if ($bobotImpor !== '' && is_numeric($bobotImpor)) {
                $poin = (float) $bobotImpor;
            } elseif (isset($bobotNilai[$nilaiHuruf])) {
                $poin = $bobotNilai[$nilaiHuruf];
            } else {
                continue;
            }

            if ($sks > 0) {
                $totalSks += $sks;
                $totalPoin += ($sks * $poin);
            }
        }

        $this->totalBobot = $totalPoin;
        $computedIpk = $totalSks > 0 ? round($totalPoin / $totalSks, 2) : 0.00;

        if (! $this->ringkasanManual) {
            $this->totalSks = $totalSks;
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
