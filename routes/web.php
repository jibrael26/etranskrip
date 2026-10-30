<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\TranskripForm;

Route::get('/', function () {
    return view('welcome');
});

// Grup route yang butuh login
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // 2. Transkrip (Aplikasi Utama Anda)
    Route::get('/transkrip', TranskripForm::class)->name('transkrip');
    Route::get('/transkrip/{transcript}', TranskripForm::class)->name('transkrip.edit');
    Route::get('/history-transkrip', function () {
        $transcripts = auth()->user()->transcripts()->latest()->paginate(12);

        return view('transcripts.index', compact('transcripts'));
    })->name('transcripts.index');
});

require __DIR__.'/settings.php';

// --- PERBAIKAN ---
// Baris "require ... auth.php" DIHAPUS karena file-nya tidak ada.
// Laravel Fortify (yang Anda pakai) sudah otomatis mengatur route login/register sendiri.
