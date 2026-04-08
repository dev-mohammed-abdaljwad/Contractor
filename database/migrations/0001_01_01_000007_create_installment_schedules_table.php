<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advance_id')->constrained('advances')->cascadeOnDelete();
            $table->integer('installment_number'); // 1, 2, 3, etc.
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index('advance_id');
            $table->index('due_date');
            $table->index('is_paid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_schedules');
    }
};
