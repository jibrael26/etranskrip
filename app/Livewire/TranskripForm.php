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
    public $predikat = '';
    public $ringkasanManual = false;

    public function mount()
    {
        $this->pejabat['tgl_cetak'] = now()->format('d F Y');
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
        $this->perbaruiPredikat();
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

        $this->perbaruiPredikat();
    }

    private function perbaruiPredikat(): void
    {
        if ($this->ipk >= 3.51) $this->predikat = 'Dengan Pujian (Cum Laude)';
        elseif ($this->ipk >= 3.01) $this->predikat = 'Sangat Memuaskan';
        elseif ($this->ipk >= 2.76) $this->predikat = 'Memuaskan';
        else $this->predikat = '-';
    }

    public function render()
    {
        // Pecah data menjadi 2 bagian untuk kolom Kiri dan Kanan (Layout PDF)
        $jumlahData = count($this->mataKuliah);
        $bagiDua = ceil($jumlahData / 2);

        // Jika data kosong, inisialisasi array kosong
        $kolomKiri = array_slice($this->mataKuliah, 0, $bagiDua);
        $kolomKanan = array_slice($this->mataKuliah, $bagiDua);

        return view('livewire.transkrip-form', [
            'kolomKiri' => $kolomKiri,
            'kolomKanan' => $kolomKanan
        ])->layout('components.app-layout');
    }
}
