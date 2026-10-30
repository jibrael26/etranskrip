<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('student_name');
            $table->string('student_nim');
            $table->json('header');
            $table->json('pejabat');
            $table->json('mata_kuliah');
            $table->decimal('total_sks', 8, 2)->default(0);
            $table->decimal('total_bobot', 8, 2)->default(0);
            $table->decimal('ipk', 4, 2)->default(0);
            $table->decimal('komprehensif', 4, 2)->default(0);
            $table->decimal('nilai_yudisium', 4, 2)->default(0);
            $table->string('predikat')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transcripts');
    }
};
