<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transcript extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_name',
        'student_nim',
        'header',
        'pejabat',
        'style',
        'mata_kuliah',
        'total_sks',
        'total_bobot',
        'ipk',
        'komprehensif',
        'nilai_yudisium',
        'predikat',
    ];

    protected function casts(): array
    {
        return [
            'header' => 'array',
            'pejabat' => 'array',
            'style' => 'array',
            'mata_kuliah' => 'array',
            'total_sks' => 'float',
            'total_bobot' => 'float',
            'ipk' => 'float',
            'komprehensif' => 'float',
            'nilai_yudisium' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
