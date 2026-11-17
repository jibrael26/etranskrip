<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('transcript_style')->nullable();
        });

        Schema::table('transcripts', function (Blueprint $table) {
            $table->json('style')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transcripts', function (Blueprint $table) {
            $table->dropColumn('style');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('transcript_style');
        });
    }
};
