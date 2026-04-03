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
        Schema::create('prize_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiniela_id')->constrained()->cascadeOnDelete();
            $table->integer('position');
            $table->decimal('percentage', 5, 2);
            $table->timestamps();
            $table->unique(['quiniela_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prize_positions');
    }
};
