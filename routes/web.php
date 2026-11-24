<?php

use App\Livewire\PengaturanTranskrip;
use App\Livewire\TranskripForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Grup route yang butuh login
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', function () {
        $transcripts = auth()->user()->transcripts();
        $totalTranscripts = (clone $transcripts)->count();
        $totalStudents = (clone $transcripts)->distinct('student_nim')->count('student_nim');
        $averageIpk = (float) ((clone $transcripts)->avg('ipk') ?? 0);
        $latestTranscript = (clone $transcripts)->latest()->first();
        $recentTranscripts = (clone $transcripts)->latest()->limit(5)->get();

        return view('dashboard', compact(
            'totalTranscripts',
            'totalStudents',
            'averageIpk',
            'latestTranscript',
            'recentTranscripts',
        ));
    })->name('dashboard');

    // 2. Transkrip (Aplikasi Utama Anda)
    Route::get('/transkrip', TranskripForm::class)->name('transkrip');
    Route::get('/transkrip/{transcript}', TranskripForm::class)->name('transkrip.edit');
    Route::get('/pengaturan-transkrip', PengaturanTranskrip::class)->name('transkrip.settings');
    Route::get('/history-transkrip', function () {
        $search = trim((string) request('search', ''));
        $query = auth()->user()->transcripts()->latest();

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('student_name', 'like', "%{$search}%")
                    ->orWhere('student_nim', 'like', "%{$search}%")
                    ->orWhere('header->no_transkrip', 'like', "%{$search}%");
            });
        }

        $transcripts = $query->paginate(12)->withQueryString();

        return view('transcripts.index', compact('transcripts', 'search'));
    })->name('transcripts.index');

    Route::delete('/history-transkrip/{transcript}', function (int $transcript) {
        auth()->user()->transcripts()->findOrFail($transcript)->delete();

        return redirect()->route('transcripts.index', [
            'search' => request('search'),
        ])->with('status', 'Transkrip berhasil dihapus.');
    })->name('transcripts.destroy');
});

require __DIR__.'/settings.php';

// --- PERBAIKAN ---
// Baris "require ... auth.php" DIHAPUS karena file-nya tidak ada.
// Laravel Fortify (yang Anda pakai) sudah otomatis mengatur route login/register sendiri.
