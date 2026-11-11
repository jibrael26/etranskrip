<?php

namespace App\Livewire;

use Livewire\Component;

class PengaturanTranskrip extends Component
{
    public array $header = [];
    public array $pejabat = [];
    public string $savedMessage = '';

    public function mount(): void
    {
        $user = auth()->user();

        $this->header = array_merge($this->defaultHeader(), $user->transcript_header ?? []);
        $this->pejabat = array_merge($this->defaultPejabat(), $user->transcript_signatory ?? []);
    }

    public function simpanPengaturan(): void
    {
        $this->validate([
            'header.kementerian' => ['required', 'string', 'max:255'],
            'header.universitas' => ['required', 'string', 'max:255'],
            'header.fakultas' => ['required', 'string', 'max:255'],
            'header.alamat' => ['required', 'string', 'max:500'],
            'pejabat.nama' => ['required', 'string', 'max:255'],
            'pejabat.nip' => ['required', 'string', 'max:100'],
            'pejabat.jabatan' => ['required', 'string', 'max:255'],
        ], [
            '*.required' => 'Kolom ini wajib diisi.',
        ]);

        auth()->user()->update([
            'transcript_header' => $this->header,
            'transcript_signatory' => $this->pejabat,
        ]);

        $this->savedMessage = 'Pengaturan transkrip berhasil disimpan.';
    }

    private function defaultHeader(): array
    {
        return [
            'kementerian' => 'Kementerian Pendidikan Tinggi, Sains, dan Teknologi',
            'universitas' => 'Universitas Negeri Manado',
            'fakultas' => 'Fakultas Teknik',
            'alamat' => 'Alamat: Kampus UNIMA Tondano 95618 Telepon: (0431) 322543, Fax: (0431) 322543',
        ];
    }

    private function defaultPejabat(): array
    {
        return [
            'nama' => 'Dr. Hendro M. Sumual, ST., M.Pd., M.Eng',
            'nip' => '19840522 200801 1 005',
            'jabatan' => 'Dekan',
        ];
    }

    public function render()
    {
        return view('livewire.pengaturan-transkrip')
            ->layout('components.app-layout');
    }
}
