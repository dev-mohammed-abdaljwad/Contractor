<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribution_action_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('daily_distribution_id')->constrained('daily_distributions')->cascadeOnDelete();
            $table->string('action'); // created, updated, cancelled
            $table->string('reason')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->timestamps();

            // Index for common queries
            $table->index(['daily_distribution_id', 'created_at']);
            $table->index(['contractor_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution_action_logs');
    }
};
