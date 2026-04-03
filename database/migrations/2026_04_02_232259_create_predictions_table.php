<?php

declare(strict_types=1);

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
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiniela_match_id')->constrained()->cascadeOnDelete();
            $table->string('predicted_result')->nullable()->comment('team1, team2, draw');
            $table->integer('predicted_team_1_score')->nullable();
            $table->integer('predicted_team_2_score')->nullable();
            $table->integer('points_earned')->nullable();
            $table->timestamps();
            $table->unique(['ticket_id', 'quiniela_match_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
