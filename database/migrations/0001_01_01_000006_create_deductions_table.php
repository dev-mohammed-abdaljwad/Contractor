<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->foreignId('distribution_id')->constrained('daily_distributions')->cascadeOnDelete();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['quarter', 'half', 'full']);
            $table->decimal('amount', 10, 2);
            $table->text('reason')->nullable();
            $table->boolean('is_reversed')->default(false);
            $table->timestamp('reversed_at')->nullable();
            $table->foreignId('reversed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reversal_reason')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index('worker_id');
            $table->index('distribution_id');
            $table->index('contractor_id');
            $table->index('is_reversed');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deductions');
    }
};
