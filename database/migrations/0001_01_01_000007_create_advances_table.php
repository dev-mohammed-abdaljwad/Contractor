<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->enum('recovery_method', ['immediately', 'installments', 'manually'])->default('immediately');
            $table->string('reason')->nullable();
            
            // Installment details (for US-19)
            $table->enum('installment_period', ['weekly', 'biweekly'])->nullable();
            $table->integer('installment_count')->nullable();
            $table->decimal('installment_amount', 10, 2)->nullable();
            
            // Status tracking
            $table->decimal('amount_collected', 10, 2)->default(0);
            $table->decimal('amount_pending', 10, 2);
            $table->boolean('is_fully_collected')->default(false);
            $table->timestamp('fully_collected_at')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('worker_id');
            $table->index('contractor_id');
            $table->index('date');
            $table->index('is_fully_collected');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
