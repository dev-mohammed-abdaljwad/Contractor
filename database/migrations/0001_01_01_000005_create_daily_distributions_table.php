<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->date('distribution_date');
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->decimal('daily_wage_snapshot', 10, 2);
            $table->timestamps();
            
            // Unique constraint: one worker per company per day
            $table->unique(['distribution_date', 'worker_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_distributions');
    }
};
