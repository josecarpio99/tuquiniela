<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiniela_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiniela_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_1_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('team_2_id')->constrained('teams')->cascadeOnDelete();
            $table->dateTime('match_date');
            $table->integer('sort_order')->default(0);
            $table->integer('team_1_score')->nullable();
            $table->integer('team_2_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiniela_matches');
    }
};
