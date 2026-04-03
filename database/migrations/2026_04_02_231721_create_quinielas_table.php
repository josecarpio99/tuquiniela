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
        Schema::create('quinielas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('prediction_type')->comment('result, score');
            $table->decimal('ticket_cost', 10, 2);
            $table->dateTime('closing_at');
            $table->string('status')->comment('draft, open, closed, completed');
            $table->integer('points_correct_result')->default(1);
            $table->integer('points_exact_score')->default(4);
            $table->integer('points_wrong')->default(-1);
            $table->string('prize_type')->comment('fixed, percentage');
            $table->decimal('prize_pool_amount', 10, 2)->nullable();
            $table->decimal('prize_pool_percentage', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quinielas');
    }
};
