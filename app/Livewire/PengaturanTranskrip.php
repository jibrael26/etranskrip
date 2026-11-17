<?php

namespace App\Livewire;

use Livewire\Component;

class PengaturanTranskrip extends Component
{
    public array $header = [];

    public array $pejabat = [];

    public array $headerStyle = [];

    public array $pejabatStyle = [];

    public array $contentStyle = [];

    public string $savedMessage = '';

    public function mount(): void
    {
        $user = auth()->user();

        $this->header = array_merge($this->defaultHeader(), $user->transcript_header ?? []);
        $this->pejabat = array_merge($this->defaultPejabat(), $user->transcript_signatory ?? []);
        $this->headerStyle = array_replace_recursive($this->defaultHeaderStyle(), $user->transcript_style['header'] ?? []);
        $this->pejabatStyle = array_replace_recursive($this->defaultPejabatStyle(), $user->transcript_style['pejabat'] ?? []);
        $this->contentStyle = array_replace_recursive($this->defaultContentStyle(), $user->transcript_style['content'] ?? []);
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
        ] + $this->styleRules(), [
            '*.required' => 'Kolom ini wajib diisi.',
        ]);

        auth()->user()->update([
            'transcript_header' => $this->header,
            'transcript_signatory' => $this->pejabat,
            'transcript_style' => [
                'header' => $this->headerStyle,
                'pejabat' => $this->pejabatStyle,
                'content' => $this->contentStyle,
            ],
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

    private function defaultHeaderStyle(): array
    {
        return [
            'kementerian' => $this->defaultStyle(18, 400, false, 'Times New Roman'),
            'universitas' => $this->defaultStyle(16, 700, false, 'Times New Roman'),
            'fakultas' => $this->defaultStyle(16, 700, false, 'Times New Roman'),
            'alamat' => $this->defaultStyle(9, 400, false, 'Times New Roman'),
        ];
    }

    private function defaultPejabatStyle(): array
    {
        return [
            'tgl_cetak' => $this->defaultStyle(9, 700, false, 'Calibri', 'left'),
            'jabatan' => $this->defaultStyle(9, 700, false, 'Calibri', 'left'),
            'nama' => $this->defaultStyle(9, 700, true, 'Calibri', 'left'),
            'nip' => $this->defaultStyle(9, 700, false, 'Calibri', 'left'),
        ];
    }

    private function defaultContentStyle(): array
    {
        return [
            'title' => $this->defaultStyle(10, 700, false, 'Calibri', 'center'),
            'document_meta' => $this->defaultStyle(8, 400, false, 'Calibri', 'center'),
            'identity' => $this->defaultStyle(8, 400, false, 'Calibri', 'left'),
            'table' => $this->defaultStyle(8, 400, false, 'Calibri', 'left'),
            'table_header' => $this->defaultStyle(8, 700, false, 'Calibri', 'center'),
            'formula' => $this->defaultStyle(6, 400, false, 'Cambria Math', 'center'),
            'summary' => $this->defaultStyle(8, 700, false, 'Calibri', 'left'),
            'photo' => $this->defaultStyle(9, 400, false, 'Calibri', 'center'),
        ];
    }

    private function defaultStyle(int $fontSize, int $fontWeight, bool $underline = false, string $fontFamily = 'Calibri', string $align = 'center'): array
    {
        return [
            'font_family' => $fontFamily,
            'font_size' => $fontSize,
            'font_weight' => $fontWeight,
            'italic' => false,
            'underline' => $underline,
            'align' => $align,
        ];
    }

    private function styleRules(): array
    {
        $rules = [];

        foreach ([
            'headerStyle' => array_keys($this->defaultHeaderStyle()),
            'pejabatStyle' => array_keys($this->defaultPejabatStyle()),
            'contentStyle' => array_keys($this->defaultContentStyle()),
        ] as $property => $fields) {
            foreach ($fields as $field) {
                $rules["{$property}.{$field}.font_family"] = ['required', 'string', 'in:Calibri,Arial,Times New Roman,Georgia,Cambria Math'];
                $rules["{$property}.{$field}.font_size"] = ['required', 'integer', 'min:8', 'max:24'];
                $rules["{$property}.{$field}.font_weight"] = ['required', 'integer', 'in:400,700'];
                $rules["{$property}.{$field}.italic"] = ['boolean'];
                $rules["{$property}.{$field}.underline"] = ['boolean'];
                $rules["{$property}.{$field}.align"] = ['required', 'in:left,center,right'];
            }
        }

        return $rules;
    }

    public function styleAttribute(string $section, string $field): string
    {
        $style = match ($section) {
            'header' => $this->headerStyle[$field] ?? [],
            'pejabat' => $this->pejabatStyle[$field] ?? [],
            default => $this->contentStyle[$field] ?? [],
        };
        $fontFamily = in_array($style['font_family'] ?? '', ['Calibri', 'Arial', 'Times New Roman', 'Georgia', 'Cambria Math'], true)
            ? $style['font_family']
            : 'Calibri';
        $fontSize = max(8, min(24, (int) ($style['font_size'] ?? 12)));
        $fontWeight = in_array((int) ($style['font_weight'] ?? 400), [400, 700], true)
            ? (int) $style['font_weight']
            : 400;
        $align = in_array($style['align'] ?? '', ['left', 'center', 'right'], true)
            ? $style['align']
            : 'center';

        return sprintf(
            'font-family: "%s"; font-size: %dpt; font-weight: %d; font-style: %s; text-decoration: %s; text-align: %s;',
            $fontFamily,
            $fontSize,
            $fontWeight,
            ! empty($style['italic']) ? 'italic' : 'normal',
            ! empty($style['underline']) ? 'underline' : 'none',
            $align,
        );
    }

    public function render()
    {
        return view('livewire.pengaturan-transkrip')
            ->layout('components.app-layout');
    }
}
