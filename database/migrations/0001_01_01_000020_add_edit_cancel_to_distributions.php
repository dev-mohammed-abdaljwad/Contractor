<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create table for logging distribution actions (edits, cancellations)
        Schema::create('distribution_actions_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('daily_distribution_id')->constrained('daily_distributions')->cascadeOnDelete();
            $table->enum('action', ['created', 'updated', 'cancelled'])->default('created');
            $table->text('reason')->nullable();
            $table->json('old_data')->nullable(); // For edits, store what changed
            $table->json('new_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution_actions_log');
    }
};
