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

    public function mount()
    {
        $this->pejabat['tgl_cetak'] = now()->format('d F Y');
    }

    // Fungsi dijalankan setiap kali data mataKuliah berubah (setelah paste)
    public function updatedMataKuliah()
    {
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

            if (isset($bobotNilai[$nilaiHuruf])) {
                $poin = $bobotNilai[$nilaiHuruf];
                $totalSks += $sks;
                $totalPoin += ($sks * $poin);
            }
        }

        $this->totalSks = $totalSks;
        $this->totalBobot = $totalPoin;

        if ($totalSks > 0) {
            $this->ipk = number_format($totalPoin / $totalSks, 2);
        } else {
            $this->ipk = 0.00;
        }

        // Tentukan Predikat Sederhana
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
