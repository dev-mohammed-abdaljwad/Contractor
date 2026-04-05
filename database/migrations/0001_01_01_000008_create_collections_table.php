<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('total_days_worked');
            $table->decimal('total_wages', 10, 2);
            $table->decimal('total_deductions', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'cheque'])->nullable();
            $table->date('payment_date')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
